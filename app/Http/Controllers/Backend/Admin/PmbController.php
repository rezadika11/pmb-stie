<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PmbController extends Controller
{
    public function datatable()
    {
        $query = Mahasiswa::query();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('aksi', function ($row) {
                $btnView = '<a href="' . route('pmb.detail', $row->id) . '" class="btn btn-sm btn-warning" title="Detail"><i class="bi bi-eye"></i></a>';

                // Mengembalikan tombol tanpa mengelompokkan
                // return $btnEdit . ' ' . $btnDelete; // Menggunakan spasi untuk memisahkan tombol
                return $btnView;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    public function index()
    {
        return view('backend.admin.pmb.index');
    }

    public function detail($id)
    {
        $data['mahasiswa'] = DB::table('mahasiswa')
            ->join('ortu', 'ortu.id', 'mahasiswa.id_ortu')
            ->join('program_studi', 'program_studi.id', 'mahasiswa.id_program_studi')
            ->join('pembayaran', 'pembayaran.id', 'mahasiswa.id_pembayaran')
            ->join('dokumen', 'dokumen.id', 'mahasiswa.id_dokumen')
            ->join('provinsis', 'provinsis.id', 'mahasiswa.id_provinsi')
            ->join('kabupatens', 'kabupatens.id', 'mahasiswa.id_kabupaten')
            ->join('kecamatans', 'kecamatans.id', 'mahasiswa.id_kecamatan')
            ->join('kelurahans', 'kelurahans.id', 'mahasiswa.id_desa')
            ->select('mahasiswa.*', 'ortu.*', 'program_studi.jenis_pendaftaran', 'program_studi.jenis_kelas', 'program_studi.program_studi', 'pembayaran.bukti_pembayaran', 'dokumen.*', 'provinsis.name as nama_prov', 'kabupatens.name as nama_kab', 'kecamatans.name as nama_kec', 'kelurahans.name as nama_desa')
            ->where('mahasiswa.id', $id)
            ->first();

        // $data['ortu'] = 

        return view('backend.admin.pmb.detail', $data);
    }
}
