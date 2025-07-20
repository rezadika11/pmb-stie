<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\BiayaProdi;
use App\Models\Gelombang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class BiayaProdiController extends Controller
{
    public function index()
    {
        return view('backend.biaya_prodi.index');
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $data = BiayaProdi::with(['gelombang.tahunAkademik'])
                ->select('biaya_prodi.*')
                ->join('gelombangs', 'biaya_prodi.id_gelombang', '=', 'gelombangs.id')
                ->orderBy('gelombangs.tanggal_mulai', 'asc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('gelombang_info', function ($row) {
                    return '<strong>' . $row->gelombang->nama_gelombang . '</strong><br>' .
                        '<small class="text-muted">' . ($row->gelombang->tahunAkademik ?
                            substr($row->gelombang->tahunAkademik->kode, 0, 4) . '/' .
                            substr($row->gelombang->tahunAkademik->kode, 4, 4) : 'N/A') . '</small>';
                })
                ->addColumn('program_studi', function ($row) {
                    return $row->nama_program_studi;
                })
                ->addColumn('jenis_kelas', function ($row) {
                    return ucfirst($row->jenis_kelas);
                })
                ->addColumn('biaya_pendaftaran_formatted', function ($row) {
                    return 'Rp ' . number_format($row->biaya_pendaftaran, 0, ',', '.');
                })
                ->addColumn('biaya_tri_dharma_formatted', function ($row) {
                    return 'Rp ' . number_format($row->biaya_tri_dharma, 0, ',', '.');
                })
                ->addColumn('biaya_ospek_formatted', function ($row) {
                    return 'Rp ' . number_format($row->biaya_ospek, 0, ',', '.');
                })
                ->addColumn('biaya_spp_formatted', function ($row) {
                    return 'Rp ' . number_format($row->biaya_spp, 0, ',', '.');
                })
                ->addColumn('biaya_sks_formatted', function ($row) {
                    return 'Rp ' . number_format($row->biaya_sks, 0, ',', '.');
                })
                ->addColumn('total_biaya_formatted', function ($row) {
                    return '<strong>Rp ' . number_format($row->total_biaya, 0, ',', '.') . '</strong>';
                })
                ->addColumn('kip_status', function ($row) {
                    if ($row->gratis_untuk_kip) {
                        return '<span class="badge badge-success">Ya</span>';
                    } else {
                        return '<span class="badge badge-primary">Tidak</span>';
                    }
                })
                ->addColumn('action', function ($row) {
                    // $editUrl = route('biaya_prodi.edit', $row->id);
                    // $deleteUrl = route('biaya_prodi.destroy', $row->id);

                    $btnEdit = '<a href="' . route('biaya_prodi.edit', $row->id) . '" class="btn btn-sm btn-success" title="Edit"><i class="bi bi-pencil"></i></a>';
                    $btnDelete = '<button class="btn btn-sm btn-danger btn-hapus" data-id="' . $row->id . '" title="Hapus"><i class="bi bi-trash"></i></button>';

                    // Mengembalikan tombol tanpa mengelompokkan
                    return $btnEdit . ' ' . $btnDelete; // Menggunakan spasi untuk memisahkan tombol
                })
                ->rawColumns(['gelombang_info', 'total_biaya_formatted', 'kip_status', 'action'])
                ->make(true);
        }
    }

    public function create()
    {
        $gelombangs = Gelombang::with('tahunAkademik')->orderBy('id', 'desc')->get();

        // Prodi Pilih
        $prodi_studi = ['mnj' => 'Manajemen', 'akt' => 'Akuntansi'];

        // Jenis Kelas
        $jenis_kelas = ['pagi' => 'Pagi', 'sore' => 'Sore'];

        return view('backend.biaya_prodi.create', compact('gelombangs', 'prodi_studi', 'jenis_kelas'));
    }

    public function store(Request $request)
    {
        // Set biaya_sks to 0 if jenis_kelas is 'sore' and biaya_sks is null or empty
        if ($request->jenis_kelas === 'sore' && (is_null($request->biaya_sks) || $request->biaya_sks === '')) {
            $request->merge(['biaya_sks' => 0]);
        }

        $rules = [
            'id_gelombang' => 'required|exists:gelombangs,id',
            'program_studi' => 'required|in:mnj,akt',
            'jenis_kelas' => 'required|in:pagi,sore',
            'biaya_pendaftaran' => 'required|min:0',
            'biaya_tri_dharma' => 'required|min:0',
            'biaya_ospek' => 'required|min:0',
            'biaya_spp' => 'required|min:0',
            'gratis_untuk_kip' => 'nullable'
        ];

        // Add biaya_sks validation only for 'pagi' class
        if ($request->jenis_kelas === 'pagi') {
            $rules['biaya_sks'] = 'required|min:0';
        } else {
            $rules['biaya_sks'] = 'nullable|min:0';
        }

        $messages = [
            'id_gelombang.required' => 'Gelombang harus dipilih',
            'program_studi.required' => 'Program Studi harus dipilih',
            'program_studi.in' => 'Program Studi harus Manajemen atau Akuntansi',
            'jenis_kelas.required' => 'Jenis kelas harus dipilih',
            'jenis_kelas.in' => 'Jenis kelas harus Pagi atau Sore',
            'biaya_pendaftaran.required' => 'Biaya pendaftaran harus diisi',
            'biaya_tri_dharma.required' => 'Biaya Tri Dharma harus diisi',
            'biaya_ospek.required' => 'Biaya Ospek harus diisi',
            'biaya_spp.required' => 'Biaya SPP harus diisi',
            'biaya_sks.required' => 'Biaya SKS harus diisi untuk kelas pagi',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Cek apakah kombinasi gelombang, prodi, dan jenis kelas sudah ada
            $exists = BiayaProdi::where('id_gelombang', $request->id_gelombang)
                ->where('program_studi', $request->program_studi)
                ->where('jenis_kelas', $request->jenis_kelas)
                ->exists();

            if ($exists) {
                return redirect()->back()
                    ->with('error', 'Biaya untuk gelombang, program studi, dan jenis kelas ini sudah ada!')
                    ->withInput();
            }

            BiayaProdi::create([
                'id_gelombang' => $request->id_gelombang,
                'program_studi' => $request->program_studi,
                'jenis_kelas' => $request->jenis_kelas,
                'biaya_pendaftaran' => $request->biaya_pendaftaran,
                'biaya_tri_dharma' => $request->biaya_tri_dharma,
                'biaya_ospek' => $request->biaya_ospek,
                'biaya_spp' => $request->biaya_spp,
                'biaya_sks' => $request->biaya_sks,
                'gratis_untuk_kip' => $request->gratis_untuk_kip
            ]);

            return redirect()->route('biaya_prodi.index')
                ->with('success', 'Biaya pendaftaran berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error('Error saving BiayaProdi: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    public function edit($id)
    {
        $biayaProdi = BiayaProdi::with(['gelombang'])->findOrFail($id);
        $gelombangs = Gelombang::with('tahunAkademik')->orderBy('id', 'desc')->get();

        // Prodi Pilih
        $prodi_studi = ['mnj' => 'Manajemen', 'akt' => 'Akuntansi'];

        // Jenis Kelas
        $jenis_kelas = ['pagi' => 'Pagi', 'sore' => 'Sore'];

        return view('backend.biaya_prodi.edit', compact('biayaProdi', 'gelombangs', 'prodi_studi', 'jenis_kelas'));
    }

    public function update(Request $request, $id)
    {
        $biayaProdi = BiayaProdi::findOrFail($id);

        // Set biaya_sks to 0 if jenis_kelas is 'sore' and biaya_sks is null or empty
        if ($request->jenis_kelas === 'sore' && (is_null($request->biaya_sks) || $request->biaya_sks === '')) {
            $request->merge(['biaya_sks' => 0]);
        }

        $rules = [
            'id_gelombang' => 'required|exists:gelombangs,id',
            'program_studi' => 'required|in:mnj,akt',
            'jenis_kelas' => 'required|in:pagi,sore',
            'biaya_pendaftaran' => 'required|min:0',
            'biaya_tri_dharma' => 'required|min:0',
            'biaya_ospek' => 'required|min:0',
            'biaya_spp' => 'required|min:0',
            'gratis_untuk_kip' => 'nullable'
        ];

        // Add biaya_sks validation only for 'pagi' class
        if ($request->jenis_kelas === 'pagi') {
            $rules['biaya_sks'] = 'required|min:0';
        } else {
            $rules['biaya_sks'] = 'nullable|min:0';
        }

        $messages = [
            'id_gelombang.required' => 'Gelombang harus dipilih',
            'program_studi.required' => 'Program Studi harus dipilih',
            'program_studi.in' => 'Program Studi harus Manajemen atau Akuntansi',
            'jenis_kelas.required' => 'Jenis kelas harus dipilih',
            'jenis_kelas.in' => 'Jenis kelas harus Pagi atau Sore',
            'biaya_pendaftaran.required' => 'Biaya pendaftaran harus diisi',
            'biaya_tri_dharma.required' => 'Biaya Tri Dharma harus diisi',
            'biaya_ospek.required' => 'Biaya Ospek harus diisi',
            'biaya_spp.required' => 'Biaya SPP harus diisi',
            'biaya_sks.required' => 'Biaya SKS harus diisi untuk kelas pagi',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            dd($validator->errors());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Cek apakah kombinasi gelombang, prodi, dan jenis kelas sudah ada (kecuali data yang sedang diedit)
            $exists = BiayaProdi::where('id_gelombang', $request->id_gelombang)
                ->where('program_studi', $request->program_studi)
                ->where('jenis_kelas', $request->jenis_kelas)
                ->where('id', '!=', $id)
                ->exists();

            if ($exists) {
                return redirect()->back()
                    ->with('error', 'Biaya untuk gelombang, program studi, dan jenis kelas ini sudah ada!')
                    ->withInput();
            }

            $biayaProdi->update([
                'id_gelombang' => $request->id_gelombang,
                'program_studi' => $request->program_studi,
                'jenis_kelas' => $request->jenis_kelas,
                'biaya_pendaftaran' => $request->biaya_pendaftaran,
                'biaya_tri_dharma' => $request->biaya_tri_dharma,
                'biaya_ospek' => $request->biaya_ospek,
                'biaya_spp' => $request->biaya_spp,
                'biaya_sks' => $request->biaya_sks,
                'gratis_untuk_kip' => $request->gratis_untuk_kip
            ]);

            return redirect()->route('biaya_prodi.index')
                ->with('success', 'Biaya pendaftaran berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $BiayaProdi = BiayaProdi::findOrFail($id);
            $BiayaProdi->delete();

            session()->flash('success', 'Biaya Prodi berhasil dihapus.');
            return response()->json([
                'status' => 'success',
                'message' => 'Biaya Prodi berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus biaya Prodi: ' . $e->getMessage()
            ], 500);
        }
    }
}
