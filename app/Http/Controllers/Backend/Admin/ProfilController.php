<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfilController extends Controller
{
    public function edit()
    {
        $profil = User::find(Auth::user()->id);

        return view('backend.admin.profile', compact('profil'));
    }

    public function update(Request $request)
    {
        try {
            $profil = User::find(Auth::user()->id);

            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users,email,' . $profil->id,
                'password1' => 'nullable|min:4',
                'password2' => 'nullable|same:password1'
            ], [
                'name.required' => 'Username Wajib diisi',
                'email.required' => 'Email Wajib diisi',
                'email.email' => 'Email harus valid',
                'email.unique' => 'Email sudah digunakan.',
                'password2.same' => 'Konfirmasi password tidak cocok.'
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Gagal memperbarui profil');
            }
            $profil->name = ucwords($request->name);
            $profil->email = $request->email;

            // Update password jika diisi
            if ($request->filled('password1')) {
                $profil->password = Hash::make($request->password1);
            }

            $profil->save();

            return redirect()->route('admin.profile')
                ->with('success', 'Profil berhasil diubah');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
