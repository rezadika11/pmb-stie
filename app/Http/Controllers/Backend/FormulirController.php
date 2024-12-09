<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Dokumen;
use App\Models\Mahasiswa;
use App\Models\Ortu;
use App\Models\Pembayaran;
use App\Models\Prodi;
use Barryvdh\DomPDF\Facade\Pdf;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class FormulirController extends Controller
{

    public function getKabupaten(Request $request)
    {
        $kabupaten = DB::table('kabupatens')->where('provinsi_id', $request->provinsi_id)->get();
        return response()->json($kabupaten);
    }

    public function getKecamatan(Request $request)
    {
        $kecamatan = DB::table('kecamatans')->where('kabupaten_id', $request->kabupaten_id)->get();
        return response()->json($kecamatan);
    }

    public function getDesa(Request $request)
    {
        $desa = DB::table('kelurahans')->where('kecamatan_id', $request->kecamatan_id)->get();
        return response()->json($desa);
    }

    //Wilayah Ortu

    public function getKabupatenOrtu(Request $request)
    {
        $kabupaten = DB::table('kabupatens')->where('provinsi_id', $request->provinsi_id)->get();
        return response()->json($kabupaten);
    }

    public function getKecamatanOrtu(Request $request)
    {
        $kecamatan = DB::table('kecamatans')->where('kabupaten_id', $request->kabupaten_id)->get();
        return response()->json($kecamatan);
    }

    public function getDesaOrtu(Request $request)
    {
        $desa = DB::table('kelurahans')->where('kecamatan_id', $request->kecamatan_id)->get();
        return response()->json($desa);
    }
    public function index()
    {

        $user = Auth::user();

        $data['user'] = Auth::user()->name;
        $data['provinsi'] = DB::table('provinsis')->select('name', 'id')->get();
        $data['jk'] = ['L' => 'Laki-laki', 'P' => 'Perempuan'];
        $data['agm'] = ['islam' => 'Islam', 'kristen' => 'Kristen', 'katolik' => 'Katolik', 'hindu' => 'Hindu', 'buddha' => 'Buddha', 'Konghucu' => 'Konghucu', 'dll' => 'Lainnya'];
        $data['warga'] = ['wni' => 'WNI', 'wna' => 'WNA'];
        $data['kawin'] = ['blm' => 'Belum Menikah', 'nikah' => 'Menikah'];

        //ayah
        $data['didik_ayah'] = ['tdk_sekolah' => 'Tidak Sekolah', 'sd' => 'SD', 'smp' => 'SMP', 'sma' => 'SMA', 's1' => 'S1', 's2' => 'S2', 's3' => 'S3'];
        $data['kerja_ayah'] = ['pns' => 'PNS', 'abri' => 'Abri', 'polri' => 'Polri', 'pensiunan' => 'Pensiunan', 'tani' => 'Petani/Nelayan', 'pegawai' => 'Pegawai Swasta', 'pedagang' => 'Pedagang / Pengusaha', 'tdk_keja' => 'Tidak Bekerja', 'dll' => 'Lainnya'];
        $data['hasil_ayah'] = ['kurang_lima' => ' < 500.000', 'lima_sajuta' => '500.000 - 1.000.000', 'sajuta_tigajuta' => '1.000.000 - 3.000.000', 'tigajuta_limajuta' => '3.000.000 - 5.000.000', 'lebih_limajuta' => '> 5.000.000'];

        //ibu
        $data['didik_ibu'] = ['tdk_sekolah' => 'Tidak Sekolah', 'sd' => 'SD', 'smp' => 'SMP', 'sma' => 'SMA', 's1' => 'S1', 's2' => 'S2', 's3' => 'S3'];
        $data['kerja_ibu'] = ['pns' => 'PNS', 'abri' => 'Abri', 'polri' => 'Polri', 'pensiunan' => 'Pensiunan', 'tani' => 'Petani/Nelayan', 'pegawai' => 'Pegawai Swasta', 'pedagang' => 'Pedagang / Pengusaha', 'tdk_keja' => 'Tidak Bekerja', 'dll' => 'Lainnya'];
        $data['hasil_ibu'] = ['kurang_lima' => ' < 500.000', 'lima_sajuta' => '500.000 - 1.000.000', 'sajuta_tigajuta' => '1.000.000 - 3.000.000', 'tigajuta_limajuta' => '3.000.000 - 5.000.000', 'lebih_limajuta' => '> 5.000.000'];
        $data['provinsiOrtuList'] = DB::table('provinsis')->get();

        //Prodi Pilih
        $data['jenis_daftar'] = ['reguler' => 'Reguler', 'kip' => 'KIP'];
        $data['kelas'] = ['pagi' => 'Kelas Pagi', 'sore' => 'Kelas Sore'];
        $data['prodi_studi'] = ['mnj' => 'Manajemen', 'akt' => 'Akutansi'];

        $data['mhs'] = DB::table('mahasiswa')
            ->join('provinsis', 'provinsis.id', 'mahasiswa.id_provinsi')
            ->join('kabupatens', 'kabupatens.id', 'mahasiswa.id_kabupaten')
            ->join('kecamatans', 'kecamatans.id', 'mahasiswa.id_kecamatan')
            ->join('kelurahans', 'kelurahans.id', 'mahasiswa.id_desa')
            ->select('mahasiswa.*', 'provinsis.id as id_prov', 'kabupatens.id as id_kab', 'kecamatans.id as id_kec', 'kelurahans.id as id_kel')
            ->where('id_user', $user->id)
            ->first();

        $data['ortu'] = DB::table('ortu')
            ->join('mahasiswa', 'mahasiswa.id_ortu', 'ortu.id')
            ->join('provinsis', 'provinsis.id', 'ortu.id_provinsi')
            ->join('kabupatens', 'kabupatens.id', 'ortu.id_kabupaten')
            ->join('kecamatans', 'kecamatans.id', 'ortu.id_kecamatan')
            ->join('kelurahans', 'kelurahans.id', 'ortu.id_desa')
            ->select('ortu.*', 'provinsis.id as id_prov', 'kabupatens.id as id_kab', 'kecamatans.id as id_kec', 'kelurahans.id as id_kel')
            ->where('mahasiswa.id_user', $user->id)
            ->first();

        $data['prodi'] = DB::table('program_studi')
            ->join('mahasiswa', 'mahasiswa.id_program_studi', 'program_studi.id')
            ->select('program_studi.*')
            ->where('mahasiswa.id_user', $user->id)
            ->first();

        $data['pembayaran'] = DB::table('pembayaran')
            ->join('mahasiswa', 'mahasiswa.id_pembayaran', 'pembayaran.id')
            ->select('pembayaran.*', 'mahasiswa.id_pembayaran')
            ->where('mahasiswa.id_user', $user->id)
            ->first();

        return view('backend.mhs.formulir', $data);
    }

    public function checkNikUniquenessStep1(Request $request)
    {

        // dd($request->all());
        $nik = $request->nik;
        $idMahasiswa = $request->id_mahasiswa;

        // Validasi input
        if (!$nik) {
            return response()->json([
                'unique' => false,
                'message' => 'NIK tidak valid'
            ], 400);
        }

        // Cek apakah NIK sudah ada di database
        $query = Mahasiswa::where('nik', $nik);

        // Jika id_mahasiswa ada, exclude NIK dari data yang sedang diedit
        if ($idMahasiswa) {
            // Ambil data mahasiswa saat ini
            $currentMahasiswa = Mahasiswa::find($idMahasiswa);

            // Jika NIK tidak berubah, kembalikan true
            if ($currentMahasiswa && $currentMahasiswa->nik === $nik) {
                return response()->json([
                    'unique' => true,
                    'message' => 'NIK valid'
                ]);
            }

            // Lanjutkan pengecekan untuk NIK yang berbeda
            $query->where('id', '!=', $idMahasiswa);
        }

        $exists = $query->exists();

        return response()->json([
            'unique' => !$exists,
            'message' => $exists ? 'NIK sudah digunakan' : 'NIK tersedia'
        ]);
    }

    public function simpanStep1(Request $request)
    {
        // Dapatkan mahasiswa saat ini berdasarkan user yang login
        $mahasiswa = Mahasiswa::where('id_user', Auth::user()->id)->first();

        // Validasi NIK dengan pendekatan yang lebih aman
        $nikValidationRule = [
            'required',
            'digits:16',
            function ($attribute, $value, $fail) use ($mahasiswa) {
                // Jika mahasiswa sudah ada dan NIK tidak berubah, lewati validasi
                if ($mahasiswa && $value === $mahasiswa->nik) {
                    return;
                }

                // Cek apakah NIK sudah digunakan oleh mahasiswa lain
                $exists = Mahasiswa::where('nik', $value)
                    ->when($mahasiswa, function ($query) use ($mahasiswa) {
                        return $query->where('id', '!=', $mahasiswa->id);
                    })
                    ->exists();

                if ($exists) {
                    $fail('NIK sudah digunakan oleh mahasiswa lain');
                }
            }
        ];

        $validator = Validator::make($request->all(), [
            'nik' => $nikValidationRule,
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required',
            'agama' => 'required',
            'kawin' => 'required',
            'kewarganegaraan' => 'required',
            'asal_sekolah' => 'required|string',
            'alamat' => 'required|string',
            'provinsi' => 'required|exists:provinsis,id',
            'kabupaten' => 'required|exists:kabupatens,id',
            'kecamatan' => 'required|exists:kecamatans,id',
            'desa' => 'required|exists:kelurahans,id',
            'kode_pos' => 'required',
            'no_hp' => 'required|numeric',
            'pekerjaan' => 'nullable|string'
        ], [
            'nik.unique' => 'NIK sudah digunakan oleh mahasiswa lain',
            'nik.digits' => 'NIK harus 16 digit'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $currentYear = date('Y');

            // Hanya generate no_pendaftaran jika ini adalah entri baru
            $no_pendaftaran = $mahasiswa
                ? $mahasiswa->no_pendaftaran
                : $this->generateNoPendaftaran($currentYear);

            $dataToSave = [
                'id_user' => Auth::user()->id,
                'nik' => $request->nik,
                'nama' => ucwords($request->nama),
                'pts_transfer' => $request->pts_transfer,
                'tempat_lahir' => ucwords($request->tempat_lahir),
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'agama' => $request->agama,
                'status_kawin' => $request->kawin,
                'kewarganegaraan' => $request->kewarganegaraan,
                'asal_sekolah' => $request->asal_sekolah,
                'alamat' => $request->alamat,
                'id_provinsi' => $request->provinsi,
                'id_kabupaten' => $request->kabupaten,
                'id_kecamatan' => $request->kecamatan,
                'id_desa' => $request->desa,
                'kode_pos' => $request->kode_pos,
                'no_hp' => $request->no_hp,
                'pekerjaan' => $request->pekerjaan ?? null,
                'no_pendaftaran' => $no_pendaftaran,
                'status_step' => 2
            ];

            $mahasiswa = Mahasiswa::updateOrCreate(
                ['id_user' => Auth::user()->id],
                $dataToSave
            );

            return response()->json([
                'success' => true,
                'message' => $mahasiswa->wasRecentlyCreated
                    ? 'Data berhasil disimpan'
                    : 'Data berhasil diperbarui',
                'nextStep' => 2,
                'id_mahasiswa' => $mahasiswa->id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    // Metode terpisah untuk generate nomor pendaftaran
    private function generateNoPendaftaran($currentYear)
    {
        // Find the last registration number for the current year
        $lastRegistration = Mahasiswa::where('no_pendaftaran', 'like', "PMB-$currentYear-%")
            ->orderBy('no_pendaftaran', 'desc')
            ->first();

        // Determine the next sequential number
        if ($lastRegistration) {
            // Extract the sequential number from the last registration number
            $lastNumber = (int) substr($lastRegistration->no_pendaftaran, strrpos($lastRegistration->no_pendaftaran, '-') + 1);
            $nextNumber = $lastNumber + 1;
        } else {
            // If no registration exists for the current year, start with 1
            $nextNumber = 1;
        }

        // Format the new registration number
        return 'PMB-' . $currentYear . '-' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);
    }

    // Metode untuk pengecekan keunikan NIK
    public function checkNikOrtuUniqueness(Request $request)
    {
        $mahasiswa = Mahasiswa::where('id_user', Auth::user()->id)->first();

        if (!$mahasiswa) {
            return response()->json([
                'success' => false,
                'message' => 'Mahasiswa tidak ditemukan'
            ], 404);
        }

        // Cari data ortu yang sudah ada (jika ada)
        $currentOrtu = $mahasiswa->id_ortu
            ? Ortu::find($mahasiswa->id_ortu)
            : null;

        $nik = $request->nik;
        $jenisNik = $request->jenis_nik;
        $idOrtu = $currentOrtu ? $currentOrtu->id : null;

        // Validasi input
        if (!$nik || !$jenisNik) {
            return response()->json([
                'unique' => false,
                'message' => 'NIK tidak valid'
            ], 400);
        }

        // Cek keunikan NIK berdasarkan jenisnya
        $exists = Ortu::where($jenisNik === 'ayah' ? 'nik_ayah' : 'nik_ibu', $nik)
            // Kecualikan ortu milik mahasiswa saat ini jika sudah punya ortu
            ->when($idOrtu, function ($query) use ($idOrtu) {
                return $query->where('id', '!=', $idOrtu);
            })
            ->exists();

        return response()->json([
            'unique' => !$exists,
            'message' => $exists
                ? 'NIK sudah digunakan oleh orang tua lain'
                : 'NIK tersedia'
        ]);
    }
    public function simpanStep2(Request $request)
    {

        // $id_mahasiswa = Mahasiswa::where();
        $mahasiswa = Mahasiswa::where('id_user', Auth::user()->id)->first();

        if (!$mahasiswa) {
            return response()->json([
                'success' => false,
                'message' => 'Mahasiswa tidak ditemukan'
            ], 404);
        }

        // Cari data ortu yang sudah ada (jika ada)
        $currentOrtu = $mahasiswa->id_ortu
            ? Ortu::find($mahasiswa->id_ortu)
            : null;

        $validator = Validator::make($request->all(), [
            'nik_ayah' => [
                'digits:16', // Harus 16 digit
                function ($attribute, $value, $fail) use ($mahasiswa) {
                    // Lewati jika NIK kosong
                    if (empty($value)) {
                        return;
                    }

                    // Cari ortu yang terkait dengan mahasiswa
                    $currentOrtu = $mahasiswa->id_ortu
                        ? Ortu::find($mahasiswa->id_ortu)
                        : null;

                    // Jika ortu sudah ada dan NIK tidak berubah, lewati validasi
                    if ($currentOrtu && $value === $currentOrtu->nik_ayah) {
                        return;
                    }

                    // Cek apakah NIK ayah sudah digunakan di tabel ortu
                    $exists = Ortu::where('nik_ayah', $value)
                        ->when($currentOrtu, function ($query) use ($currentOrtu) {
                            // Kecualikan ortu saat ini jika sedang update
                            return $query->where('id', '!=', $currentOrtu->id);
                        })
                        ->exists();

                    if ($exists) {
                        $fail('NIK Ayah sudah digunakan');
                    }
                }
            ],

            // Validasi NIK Ibu
            'nik_ibu' => [
                'digits:16', // Harus 16 digit
                function ($attribute, $value, $fail) use ($mahasiswa) {
                    // Lewati jika NIK kosong
                    if (empty($value)) {
                        return;
                    }

                    // Cari ortu yang terkait dengan mahasiswa
                    $currentOrtu = $mahasiswa->id_ortu
                        ? Ortu::find($mahasiswa->id_ortu)
                        : null;

                    // Jika ortu sudah ada dan NIK tidak berubah, lewati validasi
                    if ($currentOrtu && $value === $currentOrtu->nik_ibu) {
                        return;
                    }

                    // Cek apakah NIK ibu sudah digunakan di tabel ortu
                    $exists = Ortu::where('nik_ibu', $value)
                        ->when($currentOrtu, function ($query) use ($currentOrtu) {
                            // Kecualikan ortu saat ini jika sedang update
                            return $query->where('id', '!=', $currentOrtu->id);
                        })
                        ->exists();

                    if ($exists) {
                        $fail('NIK Ibu sudah digunakan');
                    }
                }
            ],
            'nama_ayah' => 'required',
            'tempat_lahir_ayah' => 'required',
            'tanggal_lahir_ayah' => 'required',
            'pendidikan_ayah' => 'required',
            'pekerjaan_ayah' => 'required',
            'penghasilan_ayah' => 'required',
            'no_hp_ayah' => 'required',

            //ibu
            'nama_ibu' => 'required',
            'tempat_lahir_ibu' => 'required',
            'tanggal_lahir_ibu' => 'required',
            'pendidikan_ibu' => 'required',
            'pekerjaan_ibu' => 'required',
            'penghasilan_ibu' => 'required',
            'no_hp_ibu' => 'required',

            'alamat_ortu' => 'required',
            'provinsi_ortu' => 'required',
            'kabupaten_ortu' => 'required',
            'kecamatan_ortu' => 'required',
            'desa_ortu' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $dataOrtu = [
                // Data Ayah
                'nama_ayah' => ucwords($request->nama_ayah),
                'tempat_lahir_ayah' => ucwords($request->tempat_lahir_ayah),
                'tanggal_lahir_ayah' => $request->tanggal_lahir_ayah,
                'nik_ayah' => $request->nik_ayah,
                'pendidikan_ayah' => $request->pendidikan_ayah,
                'pekerjaan_ayah' => $request->pekerjaan_ayah,
                'penghasilan_ayah' => $request->penghasilan_ayah,
                'no_hp_ayah' => $request->no_hp_ayah,

                // Data Ibu
                'nama_ibu' => ucwords($request->nama_ibu),
                'tempat_lahir_ibu' => ucwords($request->tempat_lahir_ibu),
                'tanggal_lahir_ibu' => $request->tanggal_lahir_ibu,
                'nik_ibu' => $request->nik_ibu,
                'pendidikan_ibu' => $request->pendidikan_ibu,
                'pekerjaan_ibu' => $request->pekerjaan_ibu,
                'penghasilan_ibu' => $request->penghasilan_ibu,
                'no_hp_ibu' => $request->no_hp_ibu,

                // Alamat Orang Tua
                'alamat_ortu' => $request->alamat_ortu,
                'id_provinsi' => $request->provinsi_ortu,
                'id_kabupaten' => $request->kabupaten_ortu,
                'id_kecamatan' => $request->kecamatan_ortu,
                'id_desa' => $request->desa_ortu,
            ];

            // Jika belum ada data ortu, buat baru
            if (!$currentOrtu) {
                $ortu = Ortu::create($dataOrtu);

                // Update mahasiswa dengan id_ortu yang baru dibuat
                $mahasiswa->update([
                    'id_ortu' => $ortu->id,
                    'status_step' => 3
                ]);
            } else {
                // Jika sudah ada, update data ortu yang sudah ada
                $currentOrtu->update($dataOrtu);
                $ortu = $currentOrtu;

                // Update status step mahasiswa
                $mahasiswa->update([
                    'status_step' => 3
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => $currentOrtu
                    ? 'Data Orang Tua berhasil diperbarui'
                    : 'Data Orang Tua berhasil disimpan',
                'nextStep' => 3,
                'id_ortu' => $ortu->id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }
    public function simpanStep3(Request $request)
    {
        $mahasiswa = Mahasiswa::where('id_user', Auth::user()->id)->first();

        if (!$mahasiswa) {
            return response()->json([
                'success' => false,
                'message' => 'Mahasiswa tidak ditemukan'
            ], 404);
        }

        // Cari data program studi yang sudah ada (jika ada)
        $currentProdi = $mahasiswa->id_program_studi
            ? Prodi::find($mahasiswa->id_program_studi)
            : null;


        $validator = Validator::make($request->all(), [
            'jenis_pendaftaran' => 'required',
            'jenis_kelas' => 'required',
            'program_studi' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $dataProdi = [
                'jenis_pendaftaran' => $request->jenis_pendaftaran,
                'jenis_kelas' => $request->jenis_kelas,
                'program_studi' => $request->program_studi,
            ];

            if (!$currentProdi) {
                // Create baru
                $prodi = Prodi::create($dataProdi);

                // Update mahasiswa dengan id_program_studi yang baru dibuat
                $mahasiswa->update([
                    'id_program_studi' => $prodi->id,
                    'status_step' => 4
                ]);
            } else {
                // Update data program studi yang sudah ada
                $currentProdi->update($dataProdi);
                $prodi = $currentProdi;

                // Update status step mahasiswa
                $mahasiswa->update([
                    'status_step' => 4
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => $currentProdi ? 'Data Program Studi berhasil diperbarui' : 'Data  Program Studi berhasil disimpan',
                'nextStep' => 4,
                'id_prodi' => $prodi->id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function simpanStep4(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pas_foto' => [
                'required',
                'file',
                'mimes:jpeg,png,jpg',
                'max:1024'
            ],
            'ijazah' => [
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:1024'
            ],
            'kk' => [
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:1024'
            ],
            'ktp' => [
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:1024'
            ],
            'daftar_nilai' => [
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:1024'
            ],
            'kip' => [
                'nullable', // KIP tidak wajib
                'file',
                'mimes:jpg,jpeg,png',
                'max:1024'
            ],
        ], [
            '*.required' => 'Dokumen :attribute wajib diunggah.',
            '*.mimes' => 'Format file :attribute tidak valid. Gunakan format JPG, PNG, atau PDF.',
            '*.max' => 'Ukuran file :attribute maksimal 1MB.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Validasi dokumen gagal'
            ], 422);
        }

        try {
            // Ambil mahasiswa yang sedang login
            $mahasiswa = Mahasiswa::where('id_user', Auth::user()->id)->first();

            // Buat dokumen baru
            $dokumen = new Dokumen();
            $uploadedFiles = [];
            $fields = ['pas_foto', 'ijazah', 'kk', 'ktp', 'daftar_nilai', 'kip'];

            foreach ($fields as $field) {
                if ($request->hasFile($field)) {
                    $file = $request->file($field);
                    $fileName = time() . '_' . $field . '.' . $file->getClientOriginalExtension();

                    // Simpan file ke direktori yang diinginkan
                    $path = $file->storeAs('dokumen/' . $mahasiswa->id, $fileName, 'public');

                    $dokumen->$field = $path;
                    $uploadedFiles[$field] = $path;
                }
            }

            // Simpan dokumen
            $dokumen->save();

            // Update mahasiswa dengan id dokumen
            $mahasiswa->update([
                'id_dokumen' => $dokumen->id,
                'status_step' => 5 // Misalnya step selanjutnya
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil diunggah',
                'nextStep' => 5
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function simpanBuktiPembayaran(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bukti_pembayaran' => [
                'required',
                'file',
                'mimes:jpeg,png,jpg,pdf',
                'max:1024'
            ],
        ], [
            '*.required' => 'Bukti pembayaran wajib diunggah.',
            '*.mimes' => 'Format file :attribute tidak valid. Gunakan format JPG, PNG, atau PDF.',
            '*.max' => 'Ukuran file :attribute maksimal 1MB.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Validasi pembayaran gagal'
            ], 422);
        }

        try {
            // Ambil mahasiswa yang sedang login
            $mahasiswa = Mahasiswa::where('id_user', Auth::user()->id)->first();

            // Buat dokumen baru
            $bukti_pembayaran = new Pembayaran();
            $uploadedFiles = [];
            $fields = ['bukti_pembayaran'];

            foreach ($fields as $field) {
                if ($request->hasFile($field)) {
                    $file = $request->file($field);
                    $fileName = time() . '_' . $field . '.' . $file->getClientOriginalExtension();

                    // Simpan file ke direktori yang diinginkan
                    $path = $file->storeAs('bukti_pembayaran/' . $mahasiswa->id, $fileName, 'public');

                    $bukti_pembayaran->$field = $path;
                    $uploadedFiles[$field] = $path;
                }
            }

            // Simpan dokumen
            $bukti_pembayaran->save();

            // Update mahasiswa dengan id dokumen
            $mahasiswa->update([
                'id_pembayaran' => $bukti_pembayaran->id,
                'status_pembayaran' => 1
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Bukti pembayaran berhasil diunggah',
                'nextStep' => 5
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function downloadBuktiPembayaran($id)
    {
        $data['mahasiswa'] = DB::table('mahasiswa')
            ->join('program_studi', 'program_studi.id', 'mahasiswa.id_program_studi')
            ->join('dokumen', 'dokumen.id', 'mahasiswa.id_dokumen')
            ->join('provinsis', 'provinsis.id', 'mahasiswa.id_provinsi')
            ->join('kabupatens', 'kabupatens.id', 'mahasiswa.id_kabupaten')
            ->join('kecamatans', 'kecamatans.id', 'mahasiswa.id_kecamatan')
            ->join('kelurahans', 'kelurahans.id', 'mahasiswa.id_desa')
            ->select('mahasiswa.nama as nama_lengkap', 'mahasiswa.nik', 'mahasiswa.no_pendaftaran', 'mahasiswa.jenis_kelamin', 'mahasiswa.tempat_lahir', 'mahasiswa.tanggal_lahir', 'program_studi.program_studi as nama_prodi', 'program_studi.jenis_pendaftaran as jalur_masuk', 'mahasiswa.alamat', 'kelurahans.name as nama_desa', 'kecamatans.name as nama_kec', 'kabupatens.name as nama_kab', 'provinsis.name as nama_prov', 'mahasiswa.no_hp', 'dokumen.pas_foto')
            ->where('id_user', Auth::user()->id)
            ->first();

        $data['jk'] = ['L' => 'Laki-laki', 'P' => 'Perempuan'];
        $data['jenis_daftar'] = ['reguler' => 'Reguler', 'kip' => 'KIP'];
        $data['kelas'] = ['pagi' => 'Kelas Pagi', 'sore' => 'Kelas Sore'];
        $data['prodi_studi'] = ['mnj' => 'Manajemen', 'akt' => 'Akutansi'];

        $data['logo'] = storage_path('img/logo-kop.png');


        $pdf = PDF::loadView('backend.mhs.download-pembayaran', $data)->setPaper('A4')
            ->setOption('margin-top', 10)
            ->setOption('margin-bottom', 10)
            ->setOption('margin-left', 10)
            ->setOption('margin-right', 10);
        return $pdf->download('Formulir_Pendaftaran_' . $data['mahasiswa']->nik . '.pdf');
    }
}
