<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Formulir Pendaftaran</title>
    <style>
             @page {
            size: A4;
            margin: 10mm;
        }

        html, body {
            width: 210mm;
            height: 297mm;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            font-size: 10pt;
        }

        .container {
            width: 190mm;
            margin: 0 auto;
            padding: 5mm;
            box-sizing: border-box;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 5mm;
        }

        .foto-container {
            text-align: left;
        }

        .foto-container img {
            width: 35mm;
            height: 50mm;
            object-fit: cover;
            border: 0.5px solid #000;
        }

        .formulir-table {
            width: 100%;
            border-collapse: collapse;
        }

        .formulir-table td {
            border: 0.5px solid #000;
            padding: 2mm;
            font-size: 9pt;
            vertical-align: top;
        }

        .label {
            width: 40mm;
            font-weight: bold;
            background-color: #f0f0f0;
        }

        .data-cell {
            width: auto;
        }

        .section-header {
            text-align: center;
            background-color: #e0e0e0;
            font-weight: bold;
            padding: 2mm;
        }

        .ttd-container {
            text-align: right;
            margin-top: 10mm;
            font-size: 9pt;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="{{$logo}}" width="80mm" height="80mm" style="float:left;"/>
        <div style="text-align:center; font-size:12pt; font-weight: bold;">
          BUKTI PENDAFTARAN MAHASISWA BARU
        </div>
        <div style="text-align:center; font-size:13pt; font-weight: bold;">
            SEKOLAH TINGGI ILMU EKONOMI TAMANSISWA BANJARNEGARA
        </div>
        <div style="text-align:center; font-size:9pt;">
            Jl. Mayjend Panjaitan No.29, FAX (0286) 595043, Banjarnegara 53414
          <br />Website: www.stietambara.ac.id&nbsp;&nbsp;Email: info@stietambara.ac.id
        </div>
        <div style="margin-top:15px">
            <hr style="border:0.3mm solid;"/>
        </div>
        <br />

        <div class="header-container">
            <div class="foto-container">
                @if($mahasiswa->pas_foto)
                    <img src="{{ storage_path('app/public/' . $mahasiswa->pas_foto) }}" alt="Pas Foto">
                @else
                    <div style="width:35mm;height:45mm;border:0.5px solid #000;display:flex;justify-content:center;align-items:center;">
                        Foto
                    </div>
                @endif
            </div>
        </div>

        <table class="formulir-table">
            {{-- <tr>
                <td colspan="2" class="section-header">
                    DATA PRIBADI CALON MAHASISWA
                </td>
            </tr> --}}
            <tr>
                <td class="label">Nomor Pendaftaran</td>
                <td class="data-cell">{{ $mahasiswa->no_pendaftaran }}</td>
            <tr>
                <td class="label">Nama Lengkap</td>
                <td class="data-cell">{{ $mahasiswa->nama_lengkap }}</td>
            </tr>
            <tr>
                <td class="label">Jenis Kelamin</td>
                <td class="data-cell">{{ $jk[$mahasiswa->jenis_kelamin] }}</td>
            </tr>
            <tr>
                <td class="label">Tempat, Tanggal Lahir</td>
                <td class="data-cell">
                    {{ $mahasiswa->tempat_lahir }}, 
                    {{ \Carbon\Carbon::parse($mahasiswa->tanggal_lahir)->format('d F Y') }}
                </td>
            </tr>
            <tr>
                <td class="label">Program Studi</td>
                <td class="data-cell">{{ $prodi_studi[$mahasiswa->nama_prodi] }}</td>
            </tr>
            <tr>
                <td class="label">Jalur Masuk</td>
                <td class="data-cell">{{ $jenis_daftar[$mahasiswa->jalur_masuk] }}</td>
            </tr>
            <tr>
                <td class="label">Alamat</td>
                <td class="data-cell">
                    {{ $mahasiswa->alamat }}, 
                    {{ $mahasiswa->nama_desa }}, 
                    {{ $mahasiswa->nama_kec }}, 
                    {{ $mahasiswa->nama_kab }}, 
                    {{ $mahasiswa->nama_prov }}
                </td>
            </tr>
            <tr>
                <td class="label">Nomor HP</td>
                <td class="data-cell">{{ $mahasiswa->no_hp }}</td>
            </tr>
        </table>

        <div class="ttd-container">
            <p>{{ $mahasiswa->nama_kab }}, {{ \Carbon\Carbon::now()->format('d F Y') }}</p>
            <p>Calon Mahasiswa,</p>
            <br><br>
            <p><strong>{{ $mahasiswa->nama_lengkap }}</strong></p>
        </div>
    </div>
</body>
</html>