<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Brosur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class BrosurController extends Controller
{
    public function index()
    {
        $data = DB::table('brosur')->first();
        return view('backend.brosur.index', compact('data'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'content' => 'required|max:255',
            'image' => 'file|mimes:pdf|max:2048' // opsional
        ], [
            'name.required' => 'Judul tidak boleh kosong',
            'content.required' => 'Konten tidak boleh kosong',
            'image.required' => 'File brosur tidak boleh kosong',
            'image.file' => 'File brosur harus pdf',
            'image.mimes' => 'File brosur harus pdf',
            'image.max' => 'File brosur maksimal 2 MB',
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        // Ambil data yang akan diupdate
        $data = Brosur::findOrFail($id);

        // Update title
        $data->name = $request->name;
        $data->content = $request->content;

        // Proses upload gambar jika ada
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            $oldImagePath = storage_path('app/public/' . $data->path);
            if ($data->path && File::exists($oldImagePath)) {
                File::delete($oldImagePath);
            }

            // Ambil nama asli file
            $originalName = $request->file('image')->getClientOriginalName();

            // Upload gambar dengan nama asli
            $imagePath = $request->file('image')->storeAs('brosur', $originalName, 'public');

            // Update field image dan path
            $data->image = $originalName;
            $data->path = 'brosur/' . $originalName;
        }
        // Simpan perubahan
        $data->save();

        session()->flash('success', 'Brosur berhasil diupdate.');
        // Kembalikan response
        return response()->json([
            'message' => 'Data berhasil diupdate',
            'redirect' => route('brosur.index')
        ]);
    }
}
