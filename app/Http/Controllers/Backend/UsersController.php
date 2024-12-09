<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Dokumen;
use App\Models\Mahasiswa;
use App\Models\Pembayaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function datatable()
    {
        $query = User::query()
            ->orderByRaw("CASE 
                WHEN roles = 'superadmin' THEN 1 
                WHEN roles = 'admin' THEN 2 
                ELSE 3 
            END");

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('aksi', function ($row) {
                $btnEdit = '<a href="' . route('users.edit', $row->id) . '" class="btn btn-sm btn-success" title="Edit"><i class="bi bi-pencil"></i></a>';

                // Cek apakah user adalah superadmin
                $isSuperAdmin = false;
                if (is_string($row->roles)) {
                    $isSuperAdmin = $row->roles === 'superadmin';
                } elseif ($row->roles instanceof Collection) {
                    $isSuperAdmin = $row->roles->contains('name', 'superadmin');
                }

                if (!$isSuperAdmin) {
                    $btnDelete = '<button class="btn btn-sm btn-danger btn-hapus" data-id="' . $row->id . '" data-nama="' . $row->name . '" title="Hapus"><i class="bi bi-trash"></i></button>';
                    return $btnEdit . ' ' . $btnDelete;
                }

                return $btnEdit;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    public function index()
    {
        return view('backend.users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['roles'] = [
            'superadmin' => 'Superadmin',
            'admin' => 'Admin',
        ];
        return view('backend.users.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validate = $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users',
            'roles' => 'required',
            'password' => 'required|min:4',
            'password1' => 'required|same:password'
        ], [
            'name.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.unique' => 'Email sudah ada, tolong ganti yang lain',
            'roles.required' => 'Hak akses harus diisi',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 4 kata',
            'password1.required' => 'Ulangi password harus diisi',
            'password1.same' => 'Konfirmasi password tidak cocok.'
        ]);

        DB::beginTransaction();
        try {
            // Buat user dengan data dari request
            $user = User::create([
                'name' => ucwords($request->name),
                'email' => $request->email,
                'roles' => $request->roles,
                'password' => bcrypt($request->password)
            ]);

            DB::commit();
            session()->flash('success', 'Users baru berhasil disimpan.');
            return response()->json(['redirect' => route('users.index')], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['errors' => ['general' => 'Terjadi kesalahan: ' . $e->getMessage()]], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data['user'] = User::find($id);
        $data['roles'] = [
            'superadmin' => 'Superadmin',
            'admin' => 'Admin',
            'mhs' => 'Mahasiswa'
        ];

        return view('backend.users.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        // Validasi berbeda berdasarkan roles
        $rules = [
            'name' => 'required',
            'email' => [
                'required',
                'unique:users,email,' . $id
            ],
            'password' => 'nullable|min:4',
            'password1' => 'nullable|same:password'
        ];

        // Tambahkan validasi roles hanya untuk non-mahasiswa
        if ($user->roles != 'mhs') {
            $rules['roles'] = 'required';
        }

        $validate = $request->validate($rules, [
            'name.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.unique' => 'Email sudah ada, tolong ganti yang lain',
            'roles.required' => 'Hak akses harus diisi',
            'password.min' => 'Password minimal 4 kata',
            'password1.same' => 'Konfirmasi password tidak cocok.'
        ]);

        DB::beginTransaction();
        try {
            // Update data user
            $updateData = [
                'name' => ucwords($request->name),
                'email' => $request->email,
            ];

            // Tambahkan roles hanya untuk non-mahasiswa
            if ($user->roles != 'mhs') {
                $updateData['roles'] = $request->roles;
            }

            // Update password hanya jika diisi
            if ($request->filled('password')) {
                $updateData['password'] = bcrypt($request->password);
            }

            // Lakukan update
            $user->update($updateData);

            DB::commit();
            session()->flash('success', 'Users berhasil diupdate.');
            return response()->json(['redirect' => route('users.index')], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['errors' => ['general' => 'Terjadi kesalahan: ' . $e->getMessage()]], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            // Cari user
            $user = User::findOrFail($id);

            // Cari mahasiswa terkait
            $mahasiswa = Mahasiswa::where('id_user', $user->id)->first();

            if ($mahasiswa) {
                // Hapus dokumen (dengan semua file)
                if ($mahasiswa->id_dokumen) {
                    $dokumen = Dokumen::find($mahasiswa->id_dokumen);
                    if ($dokumen) {
                        // Daftar file yang akan dihapus
                        $files = [
                            'pas_foto' => $dokumen->pas_foto,
                            'ktp' => $dokumen->ktp,
                            'ijazah' => $dokumen->ijazah,
                            'kk' => $dokumen->kk,
                            'daftar_nilai' => $dokumen->daftar_nilai,
                            'kip' => $dokumen->kip
                        ];

                        // Hapus setiap file dari storage
                        foreach ($files as $key => $file) {
                            if ($file) {
                                // Cek apakah file ada di storage
                                if (Storage::disk('public')->exists($file)) {
                                    Storage::disk('public')->delete($file);
                                }

                                // Cek dan hapus direktori jika kosong
                                $directory = dirname($file);
                                if (
                                    Storage::disk('public')->exists($directory) &&
                                    count(Storage::disk('public')->files($directory)) === 0
                                ) {
                                    Storage::disk('public')->deleteDirectory($directory);
                                }
                            }
                        }

                        // Hapus record dokumen
                        $dokumen->delete();
                    }
                }

                // Hapus pembayaran (dengan file bukti)
                if ($mahasiswa->id_pembayaran) {
                    $pembayaran = Pembayaran::find($mahasiswa->id_pembayaran);
                    if ($pembayaran) {
                        // Hapus file bukti pembayaran dari storage
                        if ($pembayaran->bukti_pembayaran) {
                            if (Storage::disk('public')->exists($pembayaran->bukti_pembayaran)) {
                                Storage::disk('public')->delete($pembayaran->bukti_pembayaran);
                            }

                            // Cek dan hapus direktori jika kosong
                            $directory = dirname($pembayaran->bukti_pembayaran);
                            if (
                                Storage::disk('public')->exists($directory) &&
                                count(Storage::disk('public')->files($directory)) === 0
                            ) {
                                Storage::disk('public')->deleteDirectory($directory);
                            }
                        }

                        // Hapus record pembayaran
                        $pembayaran->delete();
                    }
                }

                // Hapus mahasiswa
                $mahasiswa->delete();
            }

            // Hapus user
            $user->delete();

            DB::commit();

            session()->flash('success', 'User dan data terkait berhasil dihapus.');
            return response()->json([
                'status' => 'success',
                'message' => 'User dan data terkait berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            // \Log::error('Error deleting user: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus user: ' . $e->getMessage()
            ], 500);
        }
    }
}
