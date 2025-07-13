<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Testimoni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class TestimoniController extends Controller
{

    public function index()
    {

        return view('backend.testimoni.index');
    }

    public function datatable()
    {
        $query = Testimoni::query();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('aksi', function ($row) {
                $btnEdit = '<a href="' . route('testimoni.edit', $row->id) . '" class="btn btn-sm btn-success" title="Edit"><i class="bi bi-pencil"></i></a>';
                $btnDelete = '<button class="btn btn-sm btn-danger btn-hapus" data-id="' . $row->id . '" data-nama="' . $row->name . '" title="Hapus"><i class="bi bi-trash"></i></button>';

                // Mengembalikan tombol tanpa mengelompokkan
                return $btnEdit . ' ' . $btnDelete; // Menggunakan spasi untuk memisahkan tombol
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        return view('backend.testimoni.create');
    }

    public function store(Request $request)
    {

        // Validasi input
        $validate = $request->validate([
            'name' => 'required',
            'alumni' => 'required',
            'kerja' => 'required',
            'isi' => 'required',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:1024',
        ], [
            'name.required' => 'Nama harus diisi',
            'alumni.required' => 'Alumni harus diisi',
            'kerja.required' => 'Posisi sekarang harus diisi',
            'isi.required' => 'Isi testimoni harus diisi',
            'image.required' => 'Gambar harus diisi',
            'image.image' => 'File yang diunggah harus berupa gambar',
            'image.mimes' => 'Gambar harus berupa file dengan ekstensi jpg, jpeg, png',
            'image.max' => 'Ukuran gambar tidak boleh lebih dari 1MB',
        ]);

        DB::beginTransaction();
        try {

            if ($request->hasFile('image')) {
                // Generate nama file unik
                $imageName = uniqid() . '.' . $request->file('image')->getClientOriginalExtension();

                // Upload gambar baru ke storage/banner
                $imagePath = $request->file('image')->storeAs('testimoni', $imageName, 'public');
            }

            // Simpan pendaftaran
            $testimoni = Testimoni::create([
                'name' => ucwords($request->name),
                'alumni' => ucwords($request->alumni),
                'kerja' => ucwords($request->kerja),
                'isi' => $request->isi,
                'image' => isset($imageName) ? $imageName : null,  // Pastikan image diisi jika ada gambar
                'path' => isset($imagePath) ? $imagePath : null,  // Pastikan path diisi jika ada gambar
            ]);

            DB::commit();
            session()->flash('success', 'Testimoni berhasil disimpan.');
            return response()->json(['redirect' => route('testimoni.index')], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return response()->json(['errors' => ['general' => 'Terjadi kesalahan: ' . $e->getMessage()]], 500);
        }
    }

    public function edit($id)
    {
        $testimoni = Testimoni::findOrFail($id);
        return view('backend.testimoni.edit', compact('testimoni'));
    }

    public function update(Request $request, $id)
    {

        // Validasi input
        $validate = $request->validate([
            'name' => 'required',
            'alumni' => 'required',
            'kerja' => 'required',
            'isi' => 'required',
            'image' => 'image|mimes:jpg,jpeg,png|max:1024',
        ], [
            'name.required' => 'Nama harus diisi',
            'alumni.required' => 'Alumni harus diisi',
            'kerja.required' => 'Posisi sekarang harus diisi',
            'isi.required' => 'Isi testimoni harus diisi',
            'image.image' => 'File yang diunggah harus berupa gambar',
            'image.mimes' => 'Gambar harus berupa file dengan ekstensi jpg, jpeg, png',
            'image.max' => 'Ukuran gambar tidak boleh lebih dari 1MB',
        ]);
        // Ambil data yang akan diupdate
        $data = Testimoni::findOrFail($id);

        // Update title
        $data->name = ucwords($request->name);
        $data->alumni = ucwords($request->alumni);
        $data->kerja = ucwords($request->kerja);
        $data->isi = $request->isi;

        // Proses upload gambar jika ada
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($data->path) {
                $oldImagePath = storage_path('app/public/' . $data->path);
                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
            }

            // Generate nama file unik
            $imageName = uniqid() . '.' . $request->file('image')->getClientOriginalExtension();

            // Upload gambar baru ke storage/testimoni
            $imagePath = $request->file('image')->storeAs('testimoni', $imageName, 'public');

            // Update field image dan path
            $data->image = $imageName;
            $data->path = 'testimoni/' . $imageName;
        }

        // Simpan perubahan ke database
        $data->save();


        // Simpan perubahan
        $data->save();
        session()->flash('success', 'Testimoni berhasil diupdate.');
        return response()->json([
            'message' => 'Berhasil diupdate',
            'redirect' => route('testimoni.index')
        ]);
    }

    public function destroy($id)
    {
        try {
            $data = Testimoni::findOrFail($id);
            if ($data->path) {
                $oldImagePath = storage_path('app/public/' . $data->path);
                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
            }
            $data->delete();

            session()->flash('success', 'Testimoni berhasil dihapus.');
            return response()->json([
                'status' => 'success',
                'message' => 'Testimoni berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus testimoni: ' . $e->getMessage()
            ], 500);
        }
    }
}