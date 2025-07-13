<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dokumen;
use App\Models\Mahasiswa;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class PmbController extends Controller
{
    public function datatable()
    {
        $query = Mahasiswa::query();

        return DataTables::of($query)
            ->addIndexColumn()
              ->addColumn('no_pendaftaran', function($row){
                return $row->no_pendaftaran ?? '-';
            })
            ->addColumn('aksi', function ($row) {
                $btnView = '<a href="' . route('pmb.detail', $row->id) . '" class="btn btn-sm btn-secondary" title="Detail"><i class="bi bi-eye"></i></a>';

                // Mengembalikan tombol tanpa mengelompokkan
                // return $btnEdit . ' ' . $btnDelete; // Menggunakan spasi untuk memisahkan tombol
                return $btnView;
            })->addColumn('status', function ($row) {
                       if ($row->status_daftar == 1) {
                    return '<span class="badge badge-success">Validasi</span>';
                }else if($row->status_daftar == 2) {
                    return '<span class="badge badge-danger">Ditolak</span>';  
                }else {
                    return '<span class="badge badge-warning">Belum Validasi</span>';
                }
            })
            ->rawColumns(['aksi', 'status'])
            ->make(true);
    }
    public function index()
    {
        return view('backend.admin.pmb.index');
    }

    public function detail($id)
    {
       $data['mahasiswa'] = DB::table('mahasiswa')
            ->leftJoin('ortu', 'ortu.id', '=', 'mahasiswa.id_ortu')
            ->leftJoin('program_studi', 'program_studi.id', '=', 'mahasiswa.id_program_studi')
            ->leftJoin('pembayaran', 'pembayaran.id', '=', 'mahasiswa.id_pembayaran')
            ->leftJoin('dokumen', 'dokumen.id', '=', 'mahasiswa.id_dokumen')
            ->leftJoin('provinsis', 'provinsis.id', '=', 'mahasiswa.id_provinsi')
            ->leftJoin('kabupatens', 'kabupatens.id', '=', 'mahasiswa.id_kabupaten')
            ->leftJoin('kecamatans', 'kecamatans.id', '=', 'mahasiswa.id_kecamatan')
            ->leftJoin('kelurahans', 'kelurahans.id', '=', 'mahasiswa.id_desa')
            ->select(
                'mahasiswa.*', 'mahasiswa.id as id_mhs',
                'ortu.*', 
                'program_studi.jenis_pendaftaran', 
                'program_studi.jenis_kelas', 
                'program_studi.program_studi', 
                'pembayaran.id as bayar_id', 
                'pembayaran.bukti_pembayaran', 
                'dokumen.*', 
                'dokumen.id as doc_id', 
                'provinsis.name as nama_prov', 
                'kabupatens.name as nama_kab', 
                'kecamatans.name as nama_kec', 
                'kelurahans.name as nama_desa'
            )
        ->where('mahasiswa.id', $id)
        ->first();

        $data['ortu'] = DB::table('ortu')
            ->leftJoin('mahasiswa', 'mahasiswa.id_ortu', 'ortu.id')
            ->leftJoin('provinsis', 'provinsis.id', 'ortu.id_provinsi')
            ->leftJoin('kabupatens', 'kabupatens.id', 'ortu.id_kabupaten')
            ->leftJoin('kecamatans', 'kecamatans.id', 'ortu.id_kecamatan')
            ->leftJoin('kelurahans', 'kelurahans.id', 'ortu.id_desa')
            ->select('ortu.*', 'provinsis.name as prov_ortu', 'kabupatens.name as kab_ortu', 'kecamatans.name as kec_ortu', 'kelurahans.name as desa_ortu')
            ->where('mahasiswa.id', $id)
            ->first();

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

        // $data['ortu'] = 

        return view('backend.admin.pmb.detail', $data);
    }

    public function showFoto($id)
    {
        $mahasiswa = Dokumen::findOrFail($id);

        // Periksa apakah pas_foto tidak kosong
        if (empty($mahasiswa->pas_foto)) {
            abort(404, 'Foto tidak ditemukan');
        }

        // Konstruksi path lengkap dengan storage_path()
        $fullPath = storage_path('app/public/' . $mahasiswa->pas_foto);

        // Periksa file secara fisik
        if (!file_exists($fullPath)) {
            abort(404, 'File foto tidak ada');
        }

        // Dapatkan nama file asli
        $fileName = pathinfo($mahasiswa->pas_foto, PATHINFO_BASENAME);

        // Dapatkan mime type file
        $mimeType = mime_content_type($fullPath) ?: 'application/octet-stream';

        // Tampilkan file dengan response dan nama file asli
        return response()->file($fullPath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $fileName . '"'
        ]);
    }
    public function showKTP($id)
    {
        $mahasiswa = Dokumen::findOrFail($id);

        // Periksa apakah pas_foto tidak kosong
        if (empty($mahasiswa->ktp)) {
            abort(404, 'KTP tidak ditemukan');
        }

        // Konstruksi path lengkap dengan storage_path()
        $fullPath = storage_path('app/public/' . $mahasiswa->ktp);

        // Periksa file secara fisik
        if (!file_exists($fullPath)) {
            abort(404, 'File KTP tidak ada');
        }

        // Dapatkan nama file asli
        $fileName = pathinfo($mahasiswa->pas_foto, PATHINFO_BASENAME);

        // Dapatkan mime type file
        $mimeType = mime_content_type($fullPath) ?: 'application/octet-stream';

        // Tampilkan file dengan response dan nama file asli
        return response()->file($fullPath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $fileName . '"'
        ]);
    }

    public function showKK($id)
    {
        $mahasiswa = Dokumen::findOrFail($id);

        // Periksa apakah pas_foto tidak kosong
        if (empty($mahasiswa->kk)) {
            abort(404, 'KK tidak ditemukan');
        }

        // Konstruksi path lengkap dengan storage_path()
        $fullPath = storage_path('app/public/' . $mahasiswa->kk);

        // Periksa file secara fisik
        if (!file_exists($fullPath)) {
            abort(404, 'File kk tidak ada');
        }

        // Dapatkan mime type file
        $mimeType = mime_content_type($fullPath);

        // Tampilkan file dengan response
        return response()->file($fullPath, [
            'Content-Type' => $mimeType
        ]);
    }

    public function showDaftarNilai($id)
    {
        $mahasiswa = Dokumen::findOrFail($id);

        // Periksa apakah pas_foto tidak kosong
        if (empty($mahasiswa->daftar_nilai)) {
            abort(404, 'Daftar nilai tidak ditemukan');
        }

        // Konstruksi path lengkap dengan storage_path()
        $fullPath = storage_path('app/public/' . $mahasiswa->daftar_nilai);

        // Periksa file secara fisik
        if (!file_exists($fullPath)) {
            abort(404, 'File daftar nilai tidak ada');
        }

        // Dapatkan nama file asli
        $fileName = pathinfo($mahasiswa->pas_foto, PATHINFO_BASENAME);

        // Dapatkan mime type file
        $mimeType = mime_content_type($fullPath) ?: 'application/octet-stream';

        // Tampilkan file dengan response dan nama file asli
        return response()->file($fullPath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $fileName . '"'
        ]);
    }

    public function showIjazah($id)
    {
        $mahasiswa = Dokumen::findOrFail($id);

        // Periksa apakah pas_foto tidak kosong
        if (empty($mahasiswa->ijazah)) {
            abort(404, 'Ijazah tidak ditemukan');
        }

        // Konstruksi path lengkap dengan storage_path()
        $fullPath = storage_path('app/public/' . $mahasiswa->ijazah);

        // Periksa file secara fisik
        if (!file_exists($fullPath)) {
            abort(404, 'File ijazah tidak ada');
        }

        // Dapatkan mime type file
        $mimeType = mime_content_type($fullPath);

        // Tampilkan file dengan response
        return response()->file($fullPath, [
            'Content-Type' => $mimeType
        ]);
    }

    public function showKIP($id)
    {
        $mahasiswa = Dokumen::findOrFail($id);

        // Periksa apakah pas_foto tidak kosong
        if (empty($mahasiswa->daftar_nilai)) {
            abort(404, 'Kartu bantuan tidak ditemukan');
        }

        // Konstruksi path lengkap dengan storage_path()
        $fullPath = storage_path('app/public/' . $mahasiswa->kip);

        // Periksa file secara fisik
        if (!file_exists($fullPath)) {
            abort(404, 'File kartu bantuan nilai tidak ada');
        }

        // Dapatkan mime type file
        $mimeType = mime_content_type($fullPath);

        // Tampilkan file dengan response
        return response()->file($fullPath, [
            'Content-Type' => $mimeType
        ]);
    }

    public function showPembayaran($id)
    {
        $mahasiswa = Pembayaran::findOrFail($id);

        // Periksa apakah pas_foto tidak kosong
        if (empty($mahasiswa->bukti_pembayaran)) {
            abort(404, 'Bukti pembayaran tidak ditemukan');
        }

        // Konstruksi path lengkap dengan storage_path()
        $fullPath = storage_path('app/public/' . $mahasiswa->bukti_pembayaran);

        // Periksa file secara fisik
        if (!file_exists($fullPath)) {
            abort(404, 'Bukti pembayaran bantuan nilai tidak ada');
        }

        // Dapatkan mime type file
        $mimeType = mime_content_type($fullPath);

        // Tampilkan file dengan response
        return response()->file($fullPath, [
            'Content-Type' => $mimeType
        ]);
    }

    public function konfirmasiDaftar(Request $request)
    {
        
        try {
            // Ambil mahasiswa yang sedang login
            $mahasiswa = Mahasiswa::find($request->mahasiswa_id);

            // Update mahasiswa dengan id dokumen
            $mahasiswa->update([
                'status_daftar' => 1
            ]);

            session()->flash('success', 'Pendafataran berhasil divalidasi');
            return redirect(route('pmb.index'))->with('success', 'Pendafataran berhasil divalidasi');
        } catch (\Exception $e) {
            return redirect(route('pmb.detail', ['id' => $request->mahasiswa_id]))->with('error', 'Gagal disimpan kesalahan data:'  . $e->getMessage());
        }
    }
    
      public function tolakPendaftaran(Request $request){
        try {
            // Ambil mahasiswa yang sedang login
            $mahasiswa = Mahasiswa::find($request->mahasiswa_id);

            // Update mahasiswa dengan id dokumen
            $mahasiswa->update([
                'status_daftar' => 2
            ]);

            session()->flash('success', 'Pendafataran berhasil ditolak');
            return redirect(route('pmb.index'))->with('success', 'Pendafataran berhasil ditolak');
        } catch (\Exception $e) {
            return redirect(route('pmb.detail', ['id' => $request->mahasiswa_id]))->with('error', 'Gagal disimpan kesalahan data:'  . $e->getMessage());
        }
    }
}
