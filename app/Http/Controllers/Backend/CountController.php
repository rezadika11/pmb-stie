<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Count;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CountController extends Controller
{
    public function index()
    {

        return view('backend.count.index');
    }

    public function datatable()
    {
        $query = Count::query();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('aksi', function ($row) {
                $btnEdit = '<a href="' . route('count.edit', $row->id) . '" class="btn btn-sm btn-success" title="Edit"><i class="bi bi-pencil"></i></a>';
                $btnDelete = '<button class="btn btn-sm btn-danger btn-hapus" data-id="' . $row->id . '" data-nama="' . $row->judul . '" title="Hapus"><i class="bi bi-trash"></i></button>';

                // Mengembalikan tombol tanpa mengelompokkan
                return $btnEdit . ' ' . $btnDelete; // Menggunakan spasi untuk memisahkan tombol
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        return view('backend.count.create');
    }

    public function store(Request $request)
    {
        // Validasi input
        $validate = $request->validate([
            'judul' => 'required',
            'jumlah' => 'required',
        ], [
            'judul.required' => 'Judul harus diisi',
            'jumlah.required' => 'Isi konten harus diisi',
        ]);

        DB::beginTransaction();
        try {
            // Simpan pendaftaran
            $pendaftaran = Count::create([
                'judul' => ucwords($request->judul),
                'jumlah' => $request->jumlah
            ]);

            DB::commit();
            session()->flash('success', 'Jumlah berhasil disimpan.');
            return response()->json(['redirect' => route('count.index')], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return response()->json(['errors' => ['general' => 'Terjadi kesalahan: ' . $e->getMessage()]], 500);
        }
    }

    public function edit($id)
    {
        $count = Count::findOrFail($id);
        return view('backend.count.edit', compact('count'));
    }

    public function update(Request $request, $id)
    {
        $count = Count::findOrFail($id);

        $validate = $request->validate([
            'judul' => 'required',
            'jumlah' => 'required',
        ], [
            'judul.required' => 'Judul harus diisi',
            'jumlah.required' => 'Isi konten harus diisi',
        ]);

        // Update model
        $count->update([
            'judul' => $request->judul,
            'jumlah' => $request->jumlah,
        ]);
        $count->save();

        session()->flash('success', 'Jumlah berhasil diupdate.');
        return response()->json([
            'message' => 'Berhasil diupdate',
            'redirect' => route('count.index')
        ]);
    }

    public function destroy($id)
    {
        try {
            $count = Count::findOrFail($id);
            $count->delete();

            session()->flash('success', 'Jumlah berhasil dihapus.');
            return response()->json([
                'status' => 'success',
                'message' => 'Jumlah berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus jumlah: ' . $e->getMessage()
            ], 500);
        }
    }
}