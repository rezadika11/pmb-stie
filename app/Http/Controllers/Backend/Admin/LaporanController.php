<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\TahunAkademik;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Coordinate;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class LaporanController extends Controller
{
    public function datatable(Request $request)
    {
        $query = Mahasiswa::query()
            ->leftJoin('tahun_akademik', 'mahasiswa.id_tahun_akademik', 'tahun_akademik.id');

        // Jika ada filter tahun akademik
        if ($request->has('tahun_akademik') && $request->tahun_akademik) {
            $query->where('tahun_akademik.kode', $request->tahun_akademik)
                ->where('mahasiswa.status_daftar', 1);
        } else {
            // Jika tidak ada filter, tetap tampilkan hanya tahun akademik aktif
            $query->where('tahun_akademik.status', 1)
                ->where('mahasiswa.status_daftar', 1);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('ttl', function ($row) {
                $tempat = $row->tempat_lahir;
                $tanggal = Carbon::parse($row->tanggal_lahir)->translatedFormat('d F Y');
                return $tempat . ', ' . $tanggal;
            })
            ->addColumn('jenis_kelamin', function ($row) {
                return $row->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
            })
            ->rawColumns(['ttl'])
            ->make(true);
    }
    public function index()
    {
        $tahun = TahunAkademik::all();

        $tahunAktif = TahunAkademik::where('status', 1)->first();

        // $currentYear = date('Y');
        // $tahunAkademikOptions = [];

        // // Generate dari tahun sekarang sampai 5 tahun ke depan
        // for ($i = 0; $i < 3; $i++) {
        //     $startYear = $currentYear + $i;
        //     $endYear = $startYear + 1;

        //     $tahunAkademikOptions[] = [
        //         'key' => $startYear . $endYear,
        //         'value' => $startYear . '/' . $endYear
        //     ];
        // }
        return view('backend.admin.laporan.index', compact('tahun', 'tahunAktif'));
    }
    // ->where('mahasiswa.status_daftar', 1);
    public function exportExcel(Request $request)
    {

        $agamaMapping = [
            'islam' => 'Islam',
            'kristen' => 'Kristen',
            'katolik' => 'Katolik',
            'hindu' => 'Hindu',
            'buddha' => 'Buddha',
            'konghucu' => 'Konghucu',
            'dll' => 'Lainnya'
        ];

        $warga = ['wni' => 'WNI', 'wna' => 'WNA'];
        $kawin = ['blm' => 'Belum Menikah', 'nikah' => 'Menikah'];
        //ayah
        $didik_ayah = ['tdk_sekolah' => 'Tidak Sekolah', 'sd' => 'SD', 'smp' => 'SMP', 'sma' => 'SMA', 's1' => 'S1', 's2' => 'S2', 's3' => 'S3'];
        $kerja_ayah = ['pns' => 'PNS', 'abri' => 'Abri', 'polri' => 'Polri', 'pensiunan' => 'Pensiunan', 'tani' => 'Petani/Nelayan', 'pegawai' => 'Pegawai Swasta', 'pedagang' => 'Pedagang / Pengusaha', 'tdk_keja' => 'Tidak Bekerja', 'dll' => 'Lainnya'];
        $hasil_ayah = ['kurang_lima' => ' < 500.000', 'lima_sajuta' => '500.000 - 1.000.000', 'sajuta_tigajuta' => '1.000.000 - 3.000.000', 'tigajuta_limajuta' => '3.000.000 - 5.000.000', 'lebih_limajuta' => '> 5.000.000'];

        //ibu
        $didik_ibu = ['tdk_sekolah' => 'Tidak Sekolah', 'sd' => 'SD', 'smp' => 'SMP', 'sma' => 'SMA', 's1' => 'S1', 's2' => 'S2', 's3' => 'S3'];
        $kerja_ibu = ['pns' => 'PNS', 'abri' => 'Abri', 'polri' => 'Polri', 'pensiunan' => 'Pensiunan', 'tani' => 'Petani/Nelayan', 'pegawai' => 'Pegawai Swasta', 'pedagang' => 'Pedagang / Pengusaha', 'tdk_keja' => 'Tidak Bekerja', 'dll' => 'Lainnya'];
        $hasil_ibu = ['kurang_lima' => ' < 500.000', 'lima_sajuta' => '500.000 - 1.000.000', 'sajuta_tigajuta' => '1.000.000 - 3.000.000', 'tigajuta_limajuta' => '3.000.000 - 5.000.000', 'lebih_limajuta' => '> 5.000.000'];
        //  $provinsiOrtuList= DB::table('provinsis')->get();

        //Prodi Pilih
        $jenis_daftar = ['reguler' => 'Reguler', 'kip' => 'KIP'];
        $kelas = ['pagi' => 'Kelas Pagi', 'sore' => 'Kelas Sore'];
        $prodi = ['mnj' => 'Manajemen', 'akt' => 'Akutansi'];

        // Query builder dengan join multiple tabel
        // Query builder dengan join multiple tabel
        $query = Mahasiswa::query()
            ->select(
                'mahasiswa.*',
                'ortu.nama_ayah',
                'ortu.nama_ibu',
                'ortu.pendidikan_ayah',
                'ortu.pendidikan_ibu',
                'ortu.pekerjaan_ayah',
                'ortu.pekerjaan_ibu',
                'ortu.penghasilan_ayah',
                'ortu.penghasilan_ibu',
                'ortu.alamat_ortu',
                'provinsis.name as nama_provinsi',
                'kabupatens.name as nama_kabupaten',
                'kecamatans.name as nama_kecamatan',
                'kelurahans.name as nama_desa'
            )
            ->leftJoin('ortu', 'mahasiswa.id_ortu', '=', 'ortu.id')
            ->leftJoin('provinsis', 'mahasiswa.id_provinsi', '=', 'provinsis.id')
            ->leftJoin('kabupatens', 'mahasiswa.id_kabupaten', '=', 'kabupatens.id')
            ->leftJoin('kecamatans', 'mahasiswa.id_kecamatan', '=', 'kecamatans.id')
            ->leftJoin('kelurahans', 'mahasiswa.id_desa', '=', 'kelurahans.id')
            ->leftJoin('tahun_akademik', 'mahasiswa.id_tahun_akademik', '=', 'tahun_akademik.id')
            ->where('tahun_akademik.status', 1)
            ->where('mahasiswa.status_daftar', 1);

        // Filter tahun akademik jika ada
        if ($request->has('tahun_akademik') && $request->tahun_akademik) {
            $query->where('tahun_akademik.kode', $request->tahun_akademik)
                ->where('mahasiswa.status_daftar', 1);
        }

        $data = $query->get();

        // Format tahun akademik
        $tahunAkademik = '';
        if ($request->tahun_akademik) {
            $tahunAkademik = substr($request->tahun_akademik, 0, 4) . '/' . substr($request->tahun_akademik, 4, 4);
        }

        // Buat spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Style untuk judul
        $styleJudul = [
            'font' => [
                'bold' => true,
                'size' => 14
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ];

        // Tulis judul
        $sheet->setCellValue('A1', 'LAPORAN PENERIMAAN MAHASISWA BARU');
        $sheet->setCellValue('A2', 'SEKOLAH TINGGI ILMU EKONOMI TAMANSISWA BANJARNEGARA');
        $sheet->setCellValue('A3', 'TAHUN AJARAN ' . $tahunAkademik);

        // Merge cells untuk judul
        $lastColumn = 'V'; // Karena kita punya 22 kolom (termasuk No)
        $sheet->mergeCells('A1:' . $lastColumn . '1');
        $sheet->mergeCells('A2:' . $lastColumn . '2');
        $sheet->mergeCells('A3:' . $lastColumn . '3');

        // Apply style untuk judul
        $sheet->getStyle('A1:' . $lastColumn . '3')->applyFromArray($styleJudul);

        // Header mulai dari baris ke-5
        $headers = [
            'No',
            'No Pendaftaran',
            'Nama',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Jenis Kelamin',
            'NIK',
            'Agama',
            'Status Kawin',
            'Kewarganegaraan',
            'Nama Ayah',
            'Nama Ibu',
            'Pendidikan Ayah',
            'Pendidikan Ibu',
            'Pekerjaan Ayah',
            'Pekerjaan Ibu',
            'Penghasilan Ayah',
            'Penghasilan Ibu',
            'Alamat Orang Tua',
            'Provinsi',
            'Kabupaten',
            'Kecamatan',
            'Desa',
            'Kode Pos',
            'No HP'
        ];

        // Tulis header
        foreach ($headers as $key => $header) {
            $column = chr(65 + $key);
            $sheet->setCellValue($column . '5', $header);
        }

        // Tulis data mulai dari baris ke-6
        foreach ($data as $row => $item) {
            $rowNumber = $row + 6;

            // Nomor urut otomatis
            $sheet->setCellValue('A' . $rowNumber, $row + 1);

            // Data mahasiswa
            $sheet->setCellValue('B' . $rowNumber, $item->no_pendaftaran);
            $sheet->setCellValue('C' . $rowNumber, $item->nama);
            $sheet->setCellValue('D' . $rowNumber, $item->tempat_lahir);
            $sheet->setCellValue('E' . $rowNumber, Carbon::parse($item->tanggal_lahir)->format('d-m-Y'));
            $sheet->setCellValue('F' . $rowNumber, $item->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan');
            $sheet->setCellValue('G' . $rowNumber, $item->nik);
            $sheet->setCellValue('H' . $rowNumber, $agamaMapping[strtolower($item->agama)] ?? $item->agama);
            $sheet->setCellValue('I' . $rowNumber, $kawin[strtolower($item->status_kawin)] ?? $item->status_kawin);
            $sheet->setCellValue('J' . $rowNumber, $warga[strtolower($item->kewarganegaraan)] ?? $item->kewarganegaraan);

            // Data orang tua
            $sheet->setCellValue('K' . $rowNumber, $item->nama_ayah);
            $sheet->setCellValue('L' . $rowNumber, $item->nama_ibu);
            $sheet->setCellValue('M' . $rowNumber, $item->pendidikan_ayah);
            $sheet->setCellValue('N' . $rowNumber, $item->pendidikan_ibu);
            $sheet->setCellValue('O' . $rowNumber, $item->pekerjaan_ayah);
            $sheet->setCellValue('P' . $rowNumber, $item->pekerjaan_ibu);
            $sheet->setCellValue('Q' . $rowNumber, $item->penghasilan_ayah);
            $sheet->setCellValue('R' . $rowNumber, $item->penghasilan_ibu);
            $sheet->setCellValue('S' . $rowNumber, $item->alamat_ortu);
            $sheet->setCellValue('T' . $rowNumber, $item->nama_provinsi);
            $sheet->setCellValue('U' . $rowNumber, $item->nama_kabupaten);
            $sheet->setCellValue('V' . $rowNumber, $item->nama_kecamatan);
            $sheet->setCellValue('W' . $rowNumber, $item->nama_desa);
            $sheet->setCellValue('X' . $rowNumber, $item->kode_pos);
            $sheet->setCellValue('Y' . $rowNumber, $item->no_hp);
        }

        // Style header
        $styleHeader = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'FFDDDDDD',
                ],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        // Apply style ke header
        $sheet->getStyle('A5:Y5')->applyFromArray($styleHeader);

        // Style untuk data
        $styleData = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        // Apply style ke data
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('A6:Y' . $lastRow)->applyFromArray($styleData);

        // Auto width kolom
        foreach (range('A', 'Y') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Set tinggi baris untuk judul
        $sheet->getRowDimension(1)->setRowHeight(30);
        $sheet->getRowDimension(2)->setRowHeight(30);
        $sheet->getRowDimension(3)->setRowHeight(30);

        // Nama file
        $filename = 'Laporan_Mahasiswa_' . $tahunAkademik . '.xlsx';

        // Simpan file
        $writer = new Xlsx($spreadsheet);

        // Download langsung
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
