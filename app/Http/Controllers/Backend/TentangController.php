<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Tentang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class TentangController extends Controller
{
    public function index()
    {
        $data = Tentang::first();

        return view('backend.tentang.index', compact('data'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'judul' => 'required|max:255',
            'content' => 'required|max:255',
            'image' => 'mimes:png,jpg,jpeg|max:1024' // opsional
        ], [
            'judul.required' => 'Judul tidak boleh kosong',
            'content.required' => 'Konten tidak boleh kosong',
            'image.required' => 'Gambar tidak boleh kosong',
            'image.mimes' => 'Format gambar harus png,jpg,jpeg',
            'image.max' => 'Format gambar maksimal 1 MB',
        ]);

        // Ambil data yang akan diupdate
        $data = Tentang::findOrFail($id);

        // Update title
        $data->judul = $request->judul;
        $data->content = $request->content;

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            $oldImagePath = storage_path('app/public/' . $data->path);
            if ($data->path && File::exists($oldImagePath)) {
                File::delete($oldImagePath);
            }

            // Generate nama file unik
            $imageName = uniqid() . '.' . $request->file('image')->getClientOriginalExtension();

            // Upload gambar baru ke storage/banner
            $imagePath = $request->file('image')->storeAs('tentang', $imageName, 'public');

            // Update field image dan path
            $data->image = $imageName;
            $data->path = 'tentang/' . $imageName;
        }

        // Simpan perubahan
        $data->save();

        session()->flash('success', 'Tentang berhasil diupdate.');
        // Kembalikan response
        return response()->json([
            'message' => 'Data berhasil diupdate',
            'redirect' => route('tentang.index')
        ]);
    }
}