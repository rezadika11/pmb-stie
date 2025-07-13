<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use App\Models\Gelombang;
use App\Models\TahunAkademik;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class GelombangController extends Controller
{
    public function index()
    {
        $gelombang = TahunAkademik::select('tanggal_mulai', 'tanggal_selesai')->where('status', 1)->first();
        $tahunAjaran = TahunAkademik::where('status', 1)->first();
        return view('backend.gelombang.index', compact('tahunAjaran', 'gelombang'));
    }

    public function datatable()
    {
        try {

            $query = Gelombang::with('tahunAkademik')
                ->orderBy('id_tahun_akademik', 'asc')
                ->orderBy('tanggal_mulai', 'asc');

            if (request()->ajax()) {
                return DataTables::of($query)
                    ->addIndexColumn()
                    ->editColumn('tahun_akademik', function ($row) {
                        $kode = $row->tahunAkademik->kode;

                        if (strlen($kode) === 8) {
                            return substr($kode, 0, 4) . '/' . substr($kode, 4, 4);
                        }

                        return $kode; // Jika format tidak sesuai, tampilkan apa adanya
                    })
                    ->editColumn('tanggal_mulai', function ($row) {
                        // Format tanggal_mulai menjadi dd-mm-yy
                        return Carbon::parse($row->tanggal_mulai)->format('d-m-Y');
                    })
                    ->editColumn('tanggal_selesai', function ($row) {
                        // Format tanggal_selesai menjadi dd-mm-yy
                        return Carbon::parse($row->tanggal_selesai)->format('d-m-Y');
                    })
                    ->addColumn('action', function ($row) {
                        $btn = '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn-edit btn btn-success btn-sm"><i class="bi bi-pencil"></i> Edit</a>';
                        // if (!$row->status) {
                        //     $btn .= ' <a href="javascript:void(0)" data-id="' . $row->id . '" class="delete-btn btn btn-danger btn-sm">Delete</a>';
                        // }
                        // $btn .= ' <a href="javascript:void(0)" data-id="' . $row->id . '" class="set-aktif-btn btn btn-info btn-sm">Set Aktif</a>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
        } catch (\Exception $e) {
            // Log::error($e->getMessage());
            return response()->json(['error' => 'Tabel gelombang tidak ditemukan atau terjadi masalah database.'], 500);
        }
    }

    public function setAktif(Request $request, Gelombang $gelombang)
    {
        // Nonaktifkan semua gelombang lain di tahun akademik yang sama
        Gelombang::where('id_tahun_akademik', $gelombang->id_tahun_akademik)
            ->where('id', '!=', $gelombang->id)
            ->update(['status' => false]);

        // Aktifkan gelombang yang dipilih
        $gelombang->update(['status' => true]);

        return response()->json(['success' => 'Gelombang berhasil diaktifkan.']);
    }

    // public function create()
    // {
    //     return view('backend.gelombang.form');
    // }

    public function store(Request $request)
    {
        $rules = [
            'nama_gelombang' => [
                'required',
                'string',
                'max:255',
                Rule::unique('gelombangs')->where(function ($query) use ($request) {
                    return $query->where('nama_gelombang', $request->nama_gelombang)
                        ->where('id_tahun_akademik', $request->id_tahun_akademik);
                })->ignore((int)$request->id),
            ],
            'tanggal_mulai' => [
                'required',
                'date',
                'before_or_equal:tanggal_selesai',
                function ($attribute, $value, $fail) use ($request) {
                    $tanggalBuka = Carbon::parse($value);
                    $tanggalTutup = Carbon::parse($request->tanggal_selesai);

                    $overlapExists = Gelombang::where(function ($query) use ($tanggalBuka, $tanggalTutup) {
                        $query->where(function ($q) use ($tanggalBuka, $tanggalTutup) {
                            $q->where('tanggal_mulai', '<=', $tanggalTutup)
                                ->where('tanggal_selesai', '>=', $tanggalBuka);
                        });
                    })
                        ->when($request->id, function ($query) use ($request) {
                            $query->where('id', '!=', (int)$request->id);
                        })
                        ->exists();

                    if ($overlapExists) {
                        $fail('Rentang tanggal gelombang ini tumpang tindih dengan gelombang lain yang sudah ada.');
                    }
                },
            ],
            'tanggal_selesai' => [
                'required',
                'date',
                'after_or_equal:tanggal_mulai',
            ],
        ];

        $messages = [
            'nama_gelombang.unique' => 'Kombinasi Nama Gelombang dan Tahun Akademik sudah ada.',
            'nama_gelombang.required' => 'Nama gelombang harus diisi.',
            'nama_gelombang.string' => 'Nama gelombang harus berupa teks.',
            'nama_gelombang.max' => 'Nama gelombang tidak boleh lebih dari 255 karakter.',
            'tanggal_mulai.required' => 'Tanggal mulai harus diisi.',
            'tanggal_mulai.date' => 'Tanggal mulai harus berupa tanggal.',
            'tanggal_mulai.before_or_equal' => 'Tanggal mulai harus sebelum atau sama dengan tanggal selesai.',
            'tanggal_selesai.required' => 'Tanggal selesai harus diisi.',
            'tanggal_selesai.date' => 'Tanggal selesai harus berupa tanggal.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Custom validation for date range
        $tahunAjaran = TahunAkademik::find($request->id_tahun_akademik);
        $tglBuka = Carbon::parse($request->tanggal_mulai);
        $tglTutup = Carbon::parse($request->tanggal_selesai);

        if ($tglBuka->lt($tahunAjaran->tanggal_mulai) || $tglTutup->gt($tahunAjaran->tanggal_selesai)) {
            return response()->json(['error' => 'Tanggal gelombang harus berada dalam rentang tanggal Tahun Akademik yang dipilih.'], 422);
        }

        try {
            if ($request->id) {
                // Update existing record
                $gelombang = Gelombang::find($request->id);
                if (!$gelombang) {
                    return response()->json(['error' => 'Gelombang tidak ditemukan.'], 404);
                }
                $gelombang->update([
                    'nama_gelombang' => $request->nama_gelombang,
                    'id_tahun_akademik' => $request->id_tahun_akademik, // Fixed field name
                    'tanggal_mulai' => $request->tanggal_mulai,
                    'tanggal_selesai' => $request->tanggal_selesai,
                ]);
                session()->flash('success', 'Gelombang berhasil diperbarui.');
            } else {
                // Create new record
                Gelombang::create([
                    'nama_gelombang' => ucwords($request->nama_gelombang),
                    'id_tahun_akademik' => $request->id_tahun_akademik, // Fixed field name
                    'tanggal_mulai' => $request->tanggal_mulai,
                    'tanggal_selesai' => $request->tanggal_selesai,
                ]);

                session()->flash('success', 'Gelombang berhasil disimpan.');
            }

            return response()->json([
                'success' => 'Data gelombang berhasil disimpan.',
                'redirect' => route('gelombang.index'),
            ], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Gagal menyimpan data gelombang.'], 500);
        }
    }

    public function edit($id)
    {
        $gelombang = Gelombang::find($id);
        if ($gelombang) {
            return response()->json($gelombang);
        }
        return response()->json(['error' => 'Data tidak ditemukan.'], 404);
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, Gelombang $gelombang)
    {
        // $request->validate([
        //     'nama_gelombang' => 'required',
        //     'tanggal_mulai' => 'required|date',
        //     'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        // ]);

        // $gelombang->update([
        //     'nama_gelombang' => $request->nama_gelombang,
        //     'tanggal_mulai' => $request->tanggal_mulai,
        //     'tanggal_selesai' => $request->tanggal_selesai,
        // ]);

        // return response()->json(['success' => 'Data berhasil diubah']);
    }

    public function destroy(Gelombang $gelombang)
    {
        // $gelombang->delete();

        // return response()->json(['success' => 'Data berhasil dihapus']);
    }
}
