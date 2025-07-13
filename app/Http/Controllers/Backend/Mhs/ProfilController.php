<?php

namespace App\Http\Controllers\Backend\Mhs;

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

        return view('backend.mhs.profile', compact('profil'));
    }

    public function update(Request $request)
    {
        try {
            $profil = User::find(Auth::user()->id);

            $validator = Validator::make($request->all(), [
                'email' => 'required|email|unique:users,email,' . $profil->id,
                'password1' => 'nullable|min:6',
                'password2' => 'nullable|same:password1'
            ], [
                'email.unique' => 'Email sudah digunakan.',
                'password2.same' => 'Konfirmasi password tidak cocok.'
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Gagal memperbarui profil');
            }

            $profil->email = $request->email;

            // Update password jika diisi
            if ($request->filled('password1')) {
                $profil->password = Hash::make($request->password1);
            }

            $profil->save();

            return redirect()->route('profile')
                ->with('success', 'Profil berhasil diubah');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
