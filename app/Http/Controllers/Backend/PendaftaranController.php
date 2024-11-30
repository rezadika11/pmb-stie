<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\PendaftaranFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class PendaftaranController extends Controller
{
    public function index()
    {

        return view('backend.pendaftaran.index');
    }

    public function datatable()
    {
        $query = Pendaftaran::query();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('aksi', function ($row) {
                $btnEdit = '<a href="' . route('pendaftaran.edit', $row->id) . '" class="btn btn-sm btn-success" title="Edit"><i class="bi bi-pencil"></i></a>';
                $btnDelete = '<button class="btn btn-sm btn-danger btn-hapus" data-id="' . $row->id . '" data-nama="' . $row->name . '" title="Hapus"><i class="bi bi-trash"></i></button>';

                // Mengembalikan tombol tanpa mengelompokkan
                return $btnEdit . ' ' . $btnDelete; // Menggunakan spasi untuk memisahkan tombol
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        return view('backend.pendaftaran.create');
    }
    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi gambar
        ]);

        if ($request->hasFile('file')) {
            $image = $request->file('file');
            $imageName = time() . '.' . $image->getClientOriginalExtension();

            // Simpan file di storage
            $imagePath = $image->storeAs('pendaftaran', $imageName, 'public');

            // Kembalikan response sesuai format TinyMCE
            return response()->json([
                'location' => asset('storage/' . $imagePath) // Menggunakan storage URL
            ]);
        }

        return response()->json(['error' => 'Image upload failed'], 400);
    }

    public function store(Request $request)
    {
        // Validasi input
        $validate = $request->validate([
            'name' => 'required',
            'content' => 'required',
        ], [
            'name.required' => 'Judul harus diisi',
            'content.required' => 'Isi konten harus diisi',
        ]);

        DB::beginTransaction();
        try {
            // Simpan pendaftaran
            $pendaftaran = Pendaftaran::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name, '-'),
                // Pastikan path gambar benar
                'content' => str_replace('../../storage/', '/storage/', $request->content),
            ]);

            DB::commit();
            session()->flash('success', 'Pendaftaran berhasil disimpan.');
            return response()->json(['redirect' => route('pendaftaran.index')], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return response()->json(['errors' => ['general' => 'Terjadi kesalahan: ' . $e->getMessage()]], 500);
        }
    }

    public function edit($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);
        return view('backend.pendaftaran.edit', compact('pendaftaran'));
    }

    public function update(Request $request, $id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required',
            'content' => 'required'
        ]);

        // Proses penghapusan gambar lama dari content sebelumnya
        // $oldContent = $pendaftaran->content;
        // $newContent = $request->content;

        // // Ekstrak path gambar dari content lama
        // preg_match_all('/\/storage\/[^"\']+\.(jpg|jpeg|png|gif|webp)/i', $oldContent, $oldMatches);
        // $oldImages = $oldMatches[0] ?? [];

        // // Ekstrak path gambar dari content baru
        // preg_match_all('/\/storage\/[^"\']+\.(jpg|jpeg|png|gif|webp)/i', $newContent, $newMatches);
        // $newImages = $newMatches[0] ?? [];

        // // Hapus gambar yang tidak ada di content baru
        // $imagesToDelete = array_diff($oldImages, $newImages);
        // foreach ($imagesToDelete as $imageToDelete) {
        //     // Hapus gambar yang tidak lagi digunakan
        //     $imagePath = str_replace('/storage/', '', $imageToDelete);
        //     if (Storage::disk('public')->exists($imagePath)) {
        //         Storage::disk('public')->delete($imagePath);
        //     }
        // }

        // Normalisasi path gambar di content
        $normalizedContent = str_replace('../../../storage/', '/storage/', $request->content);

        // Update model
        $pendaftaran->fill([
            'name' => $validated['name'],
            'content' => $normalizedContent
        ]);
        $pendaftaran->save();

        session()->flash('success', 'Pendaftaran berhasil diupdate.');
        return response()->json([
            'message' => 'Berhasil diupdate',
            'redirect' => route('pendaftaran.index')
        ]);
    }

    public function destroy($id)
    {
        try {
            $pendaftaran = Pendaftaran::findOrFail($id);
            $pendaftaran->delete();

            session()->flash('success', 'Pendaftaran berhasil dihapus.');
            return response()->json([
                'status' => 'success',
                'message' => 'Pendaftaran berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus pendaftaran: ' . $e->getMessage()
            ], 500);
        }
    }
}
