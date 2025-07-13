<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\TahunAkademik;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class TahunAkademikController extends Controller
{
    public function datatable()
    {
        $query = TahunAkademik::query();

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('kode', function ($row) {
                // Format kode menjadi 2025/2026
                if (strlen($row->kode) === 8) { // Pastikan format sesuai (4 tahun awal + 4 tahun akhir)
                    return substr($row->kode, 0, 4) . '/' . substr($row->kode, 4, 4);
                }
                return $row->kode; // Jika format tidak sesuai, tampilkan apa adanya
            })
            ->editColumn('tanggal_mulai', function ($row) {
                // Format tanggal_mulai menjadi dd-mm-yy
                return Carbon::parse($row->tanggal_mulai)->format('d-m-Y');
            })
            ->editColumn('tanggal_selesai', function ($row) {
                // Format tanggal_selesai menjadi dd-mm-yy
                return Carbon::parse($row->tanggal_selesai)->format('d-m-Y');
            })
            ->addColumn('aksi', function ($row) {
                // $btnEdit = '<a href="' . route('tahun_akademik.edit', $row->id) . '" class="btn btn-sm btn-success" title="Edit"><i class="bi bi-pencil"></i></a>';
                // $btnDelete = '<button class="btn btn-sm btn-danger btn-hapus" data-id="' . $row->id . '" data-nama="' . $row->name . '" title="Hapus"><i class="bi bi-trash"></i></button>';

                // return $btnEdit . ' ' . $btnDelete;
                if ($row->status == 1) {
                    return '<button class="btn btn-sm btn-success">Aktif</button>'; // Return the badge for 'aktif'
                } else {
                    return '<button class="btn btn-sm btn-warning btn-set-aktif" data-id="' . $row->id . '">Tidak Aktif</button>'; // Tampilkan tombol "Tidak Aktif"
                }
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function index()
    {
        $currentYear = date('Y');
        $tahunAkademikOptions = [];

        // Generate dari tahun sekarang sampai 5 tahun ke depan
        for ($i = 0; $i < 3; $i++) {
            $startYear = $currentYear + $i;
            $endYear = $startYear + 1;

            $tahunAkademikOptions[] = [
                'key' => $startYear . $endYear,
                'value' => $startYear . '/' . $endYear
            ];
        }

        return view('backend.tahun_akademik.index', compact('tahunAkademikOptions'));
    }

    public function create()
    {
        return view('backend.tahun_akademik.create');
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'kode' => 'required',
                'tanggal_mulai' => 'required',
                'tanggal_selesai' => 'required',
            ], [
                'kode.required' => 'Tahun akademik harus diisi',
                'tanggal_mulai.required' => 'Tanggal mulai harus diisi',
                'tanggal_selesai.required' => 'Tanggal selesai harus diisi',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Pastikan hanya ada satu status aktif (status = 1)
            TahunAkademik::where('status', 1)->update(['status' => 0]);

            // Cek apakah kode sudah ada di database
            $tahunAkademik = TahunAkademik::where('kode', $request->kode)->first();

            if ($tahunAkademik) {
                // Jika ditemukan, lakukan update
                $tahunAkademik->update([
                    'tanggal_mulai' => $request->tanggal_mulai,
                    'tanggal_selesai' => $request->tanggal_selesai,
                    'status' => 1 // Set status aktif
                ]);

                session()->flash('success', 'Tahun akademik berhasil diperbarui.');
            } else {
                // Jika tidak ditemukan, buat data baru
                TahunAkademik::create([
                    'kode' => $request->kode,
                    'tanggal_mulai' => $request->tanggal_mulai,
                    'tanggal_selesai' => $request->tanggal_selesai,
                    'status' => 1 // Set status aktif
                ]);

                session()->flash('success', 'Tahun akademik berhasil disimpan.');
            }

            return response()->json(['redirect' => route('tahun_akademik.index')], 200);
        } catch (\Exception $e) {
            return response()->json(['errors' => ['general' => 'Terjadi kesalahan: ' . $e->getMessage()]], 500);
        }
    }

    public function setAktif($id)
    {
        try {
            // Nonaktifkan semua tahun akademik lainnya
            TahunAkademik::where('status', 1)->update(['status' => 0]);

            // Aktifkan tahun akademik yang dipilih
            $tahunAkademik = TahunAkademik::findOrFail($id);
            $tahunAkademik->update(['status' => 1]);

            return response()->json([
                'success' => true,
                'message' => 'Tahun akademik berhasil diubah.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
}
