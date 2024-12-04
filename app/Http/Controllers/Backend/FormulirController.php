<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Ortu;
use App\Models\Prodi;
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

        return view('backend.mhs.formulir', $data);
    }

    public function checkNikUniquenessStep1(Request $request)
    {
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
        // Ambil mahasiswa yang sedang login/aktif
        $currentMahasiswa = Mahasiswa::find($request->id_mahasiswa);

        // Aturan validasi dengan pengecualian khusus
        $nikValidationRule = [
            'required',
            'digits:16',
            // Jika NIK berbeda dengan NIK saat ini, pastikan unique
            Rule::unique('mahasiswa')->where(function ($query) use ($currentMahasiswa) {
                // Jika NIK berbeda, pastikan unique
                return $query->where('nik', '!=', $currentMahasiswa->nik);
            })
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
            'nik.unique' => 'NIK sudah digunakan, tolong ganti yg sesuai',
            'nik.digits' => 'NIK harus 16 digit'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $dataToSave = [
                'id_user' => Auth::user()->id,
                'nik' => $request->nik,
                'nama' => $request->nama,
                'pts_transfer' => $request->pts_transfer,
                'tempat_lahir' => $request->tempat_lahir,
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
                'status_step' => 2
            ];

            $mahasiswa = Mahasiswa::updateOrCreate(
                ['id' => $request->id_mahasiswa],
                $dataToSave
            );

            return response()->json([
                'success' => true,
                'message' => $request->id_mahasiswa ? 'Data berhasil diperbarui' : 'Data berhasil disimpan',
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

    public function checkNikOrtuUniqueness(Request $request)
    {
        $nik = $request->nik;
        $idOrtu = $request->id_ortu;
        $idMahasiswa = $request->id_mahasiswa;
        $jenisNik = $request->jenis_nik; // 'ayah' atau 'ibu'

        // Validasi input
        if (!$nik) {
            return response()->json([
                'unique' => false,
                'message' => 'NIK tidak valid'
            ], 400);
        }

        // Cek apakah NIK sudah ada di database
        $query = Ortu::where($jenisNik == 'ayah' ? 'nik_ayah' : 'nik_ibu', $nik);

        // Jika id_ortu ada, exclude NIK dari data yang sedang diedit
        if ($idOrtu) {
            $query->where('id', '!=', $idOrtu);
        }

        // Tambahan: Cek apakah NIK sudah digunakan oleh mahasiswa lain
        if ($idMahasiswa) {
            $query->whereHas('mahasiswa', function ($q) use ($idMahasiswa) {
                $q->where('id', '!=', $idMahasiswa);
            });
        }

        $exists = $query->exists();

        return response()->json([
            'unique' => !$exists,
            'message' => $exists ? 'NIK sudah digunakan' : 'NIK tersedia'
        ]);
    }

    public function simpanStep2(Request $request)
    {
        // Ambil data ortu yang sedang login/aktif
        $currentOrtu = Ortu::find($request->id_ortu);

        // Aturan validasi khusus untuk update
        $nikAyahValidationRule = [
            'required',
            'digits:16',
            // Jika ada ID ortu (update), abaikan NIK milik ortu yang sedang diedit
            Rule::unique('ortu', 'nik_ayah')->ignore($currentOrtu->id ?? null),
        ];

        $nikIbuValidationRule = [
            'required',
            'digits:16',
            // Jika ada ID ortu (update), abaikan NIK milik ortu yang sedang diedit
            Rule::unique('ortu', 'nik_ibu')->ignore($currentOrtu->id ?? null),
        ];


        $validator = Validator::make($request->all(), [
            'nama_ayah' => 'required|string',
            'tempat_lahir_ayah' => 'required|string',
            'tanggal_lahir_ayah' => 'required|string',
            'nik_ayah' => $nikAyahValidationRule,
            'pendidikan_ayah' => 'required',
            'pekerjaan_ayah' => 'required|string',
            'penghasilan_ayah' => 'required',
            'no_hp_ayah' => 'required',
            'nama_ibu' => 'required|string',
            'tempat_lahir_ibu' => 'required|string',
            'tanggal_lahir_ibu' => 'required|string',
            'nik_ibu' => $nikIbuValidationRule,
            'pendidikan_ibu' => 'required',
            'pekerjaan_ibu' => 'required|string',
            'penghasilan_ibu' => 'required',
            'no_hp_ibu' => 'required',
            'alamat_ortu' => 'required|string',
            'provinsi' => 'required|exists:provinsis,id',
            'kabupaten' => 'required|exists:kabupatens,id',
            'kecamatan' => 'required|exists:kecamatans,id',
            'desa' => 'required|exists:kelurahans,id',
        ], [
            'nik_ayah.unique' => 'NIK Ayah sudah digunakan',
            'nik_ibu.unique' => 'NIK Ibu sudah digunakan',
            'nik_ayah.digits' => 'NIK Ayah harus 16 digit',
            'nik_ibu.digits' => 'NIK Ibu harus 16 digit'
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
                'nama_ayah' => $request->nama_ayah,
                'tempat_lahir_ayah' => $request->tempat_lahir_ayah,
                'tanggal_lahir_ayah' => $request->tanggal_lahir_ayah,
                'nik_ayah' => $request->nik_ayah,
                'pendidikan_ayah' => $request->pendidikan_ayah,
                'pekerjaan_ayah' => $request->pekerjaan_ayah,
                'penghasilan_ayah' => $request->penghasilan_ayah,
                'no_hp_ayah' => $request->no_hp_ayah,

                // Data Ibu
                'nama_ibu' => $request->nama_ibu,
                'tempat_lahir_ibu' => $request->tempat_lahir_ibu,
                'tanggal_lahir_ibu' => $request->tanggal_lahir_ibu,
                'nik_ibu' => $request->nik_ibu,
                'pendidikan_ibu' => $request->pendidikan_ibu,
                'pekerjaan_ibu' => $request->pekerjaan_ibu,
                'penghasilan_ibu' => $request->penghasilan_ibu,
                'no_hp_ibu' => $request->no_hp_ibu,

                // Alamat Orang Tua
                'alamat_ortu' => $request->alamat_ortu,
                'id_provinsi' => $request->provinsi,
                'id_kabupaten' => $request->kabupaten,
                'id_kecamatan' => $request->kecamatan,
                'id_desa' => $request->desa
            ];

            $ortu = Ortu::updateOrCreate(
                ['id' => $request->id_ortu],
                $dataOrtu
            );

            // Update mahasiswa dengan id_ortu yang baru
            $mahasiswa = Mahasiswa::where('id', $request->id_mahasiswa)->first();
            $mahasiswa->update([
                'id_ortu' => $ortu->id,
                'status_step' => 3
            ]);

            return response()->json([
                'success' => true,
                'message' => $request->id_ortu ? 'Data Orang Tua berhasil diperbarui' : 'Data Orang Tua berhasil disimpan',
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

            $prodi = Prodi::updateOrCreate(
                ['id' => $request->id_prodi],
                $dataProdi
            );

            // Update mahasiswa dengan id_ortu yang baru
            $mahasiswa = Mahasiswa::where('id', $request->id_mahasiswa)->first();
            $mahasiswa->update([
                'id_program_studi' => $prodi->id,
                'status_step' => 4
            ]);

            return response()->json([
                'success' => true,
                'message' => $request->id_prodi ? 'Data Program Studi berhasil diperbarui' : 'Data  Program Studi berhasil disimpan',
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

    public function submitForm(Request $request)
    {
        //
    }
}
