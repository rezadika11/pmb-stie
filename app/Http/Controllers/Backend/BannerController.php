<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BannerController extends Controller
{
    public function index()
    {
        $data = DB::table('banner')->first();

        return view('backend.banner.index', compact('data'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'title1' => 'required|max:255',
            'title2' => 'required|max:255',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048' // opsional
        ], [
            'title1.required' => 'Judul Pertama tidak boleh kosong',
            'title2.required' => 'Judul Kedua tidak boleh kosong',
            'image.required' => 'Gambar tidak boleh kosong',
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        // Ambil data yang akan diupdate
        $data = Banner::findOrFail($id);

        // Update title
        $data->title1 = $request->title1;
        $data->title2 = $request->title2;
        $data->description = $request->description;

        // Proses upload gambar jika ada
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            $oldImagePath = storage_path('app/public/' . $data->path);
            if ($data->path && File::exists($oldImagePath)) {
                File::delete($oldImagePath);
            }

            // Generate nama file unik
            $imageName = uniqid() . '.' . $request->file('image')->getClientOriginalExtension();

            // Upload gambar baru ke storage/banner
            $imagePath = $request->file('image')->storeAs('banner', $imageName, 'public');

            // Update field image dan path
            $data->image = $imageName;
            $data->path = 'banner/' . $imageName;
        }

        // Simpan perubahan
        $data->save();

        session()->flash('success', 'Banner berhasil diupdate.');
        // Kembalikan response
        return response()->json([
            'message' => 'Data berhasil diupdate',
            'redirect' => route('banner.index')
        ]);
    }
}
