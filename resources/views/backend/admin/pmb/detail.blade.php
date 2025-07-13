@extends('layouts.backend.main')
@section('title','Detail Mahasiswa Baru')
@push('css')
<link rel="stylesheet" href="{{ asset('backend/vendor/toastr/css/toastr.min.css') }}">
<style>
    .table td {
        padding: 8px 10px;
        /* Kurangi padding horizontal */
        padding-left: 0;
        /* Hilangkan padding kiri di kolom pertama */
    }

    .table tr td:first-child {
        width: 30%;
        /* Batasi lebar kolom pertama */
        font-weight: bold;
    }

    .table tr td:last-child {
        width: 80%;
        /* Sisakan ruang untuk kolom kedua */
    }

    /* Atau dengan metode fixed */
    .table {
        table-layout: fixed;
        width: 100%;
    }

    .table td {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .hover-effect {
        position: relative;
        transition: all 0.3s ease;
    }

    .hover-effect:hover {
        color: #007bff !important;
        padding-left: 10px;
        text-decoration: underline;
    }
</style>
@endpush
@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="col-sm-6 p-md-0 mb-4">
            <div class="welcome-text">
                <h3>Detail Mahasiswa Baru</h3>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="basic-form">
                            {{-- <form id="formSimpan"> --}}
                                {{-- @csrf --}}
                                {{-- @method('PUT') --}}
                                <!--<input type="text" name="id" value="{{ $mahasiswa->id_mhs }}">-->
                                <h4>Data Pribadi</h4>
                                <table class="table table-striped text-dark" style="min-width: 100px">
                                    <tr>
                                        <td>No Pendaftaran</td>
                                        <td>: {{ $mahasiswa->no_pendaftaran ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Program Studi Pilihan</td>
                                        <td>: {{ $prodi_studi[$mahasiswa->program_studi] ?? '-' }} - {{
                                            $jenis_daftar[$mahasiswa->jenis_pendaftaran] ?? '-' }} ({{
                                            $kelas[$mahasiswa->jenis_kelas] ?? '-' }})</td>
                                    </tr>
                                    <tr>
                                        <td>Nama</td>
                                        <td>: {{ $mahasiswa->nama }}</td>
                                    </tr>
                                    <tr>
                                        <td>Tempat dan tanggal lahir</td>
                                        <td>: {{ $mahasiswa->tempat_lahir ?? '-' }}, {{
                                            \Carbon\Carbon::parse($mahasiswa->tanggal_lahir)->translatedFormat('d F Y')
                                            ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Jenis Kelamin</td>
                                        <td>: {{ $jk[$mahasiswa->jenis_kelamin] ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Nomor Induk Kependudukan</td>
                                        <td>: {{ $mahasiswa->nik ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Agama</td>
                                        <td>: {{ $agm[$mahasiswa->agama] ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Status Perkawinan</td>
                                        <td>:{{ $kawin[$mahasiswa->status_kawin] ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Kewarganegaraan</td>
                                        <td>: {{ $warga[$mahasiswa->kewarganegaraan] ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Asal Sekolah (Lulusan SMA/MA, SMK)</td>
                                        <td>: {{ $mahasiswa->asal_sekolah ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Asal PTS (Lulusan D2, D3, S1/Transfer)</td>
                                        <td>: {{ $mahasiswa->asal_pts ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Alamat</td>
                                        <td>: {{ $mahasiswa->alamat }}, {{ $mahasiswa->nama_desa ?? '-' }}, {{
                                            $mahasiswa->nama_kec ?? '-' }}, {{ $mahasiswa->nama_kab }}, {{
                                            $mahasiswa->nama_prov ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Kode Pos</td>
                                        <td>: {{ $mahasiswa->kode_pos ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>No. Telepon/HP</td>
                                        <td>: {{ $mahasiswa->no_hp ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Pekerjaan</td>
                                        <td>: {{ $mahasiswa->pekerjaan ?? '-' }}</td>
                                    </tr>
                                </table>

                                <h4 class="mt-4">Data Orang Tua</h4>
                                <table class="table table-striped text-dark" style="min-width: 100px">
                                    <tr>
                                        <td>Nama Ayah</td>
                                        <td>: {{ $mahasiswa->nama_ayah ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Tempat dan tanggal lahir</td>
                                        <td>:
                                            {{ $mahasiswa->tempat_lahir_ayah ?? '-' }},
                                            @if (!empty($mahasiswa->tanggal_lahir_ayah) &&
                                            strtotime($mahasiswa->tanggal_lahir_ayah))
                                            {{
                                            \Carbon\Carbon::parse($mahasiswa->tanggal_lahir_ayah)->translatedFormat('d F
                                            Y') }}
                                            @else
                                            -
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Nomor Induk Kependudukan</td>
                                        <td>: {{ $mahasiswa->nik_ayah ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Pendidikan Ayah</td>
                                        <td>: {{ $didik_ayah[$mahasiswa->pendidikan_ayah] ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Pekerjaan Ayah</td>
                                        <td>: {{ $kerja_ayah[$mahasiswa->pekerjaan_ayah] ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Penghasilan Ayah</td>
                                        <td>: {{ $hasil_ayah[$mahasiswa->penghasilan_ayah] ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Nama Ibu</td>
                                        <td>: {{ $mahasiswa->nama_ibu ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Tempat dan tanggal lahir</td>
                                        <td>:
                                            {{ $mahasiswa->tempat_lahir_ibu ?? '-' }},
                                            @if (!empty($mahasiswa->tanggal_lahir_ibu) &&
                                            strtotime($mahasiswa->tanggal_lahir_ibu))
                                            {{
                                            \Carbon\Carbon::parse($mahasiswa->tanggal_lahir_ibu)->translatedFormat('d F
                                            Y') }}
                                            @else
                                            -
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Nomor Induk Kependudukan</td>
                                        <td>: {{ $mahasiswa->nik_ibu ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Pendidikan Ibu</td>
                                        <td>: {{ $didik_ibu[$mahasiswa->pendidikan_ibu] ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Pekerjaan Ibu</td>
                                        <td>: {{ $kerja_ibu[$mahasiswa->pekerjaan_ibu] ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Penghasilan Ibu</td>
                                        <td>: {{ $hasil_ibu[$mahasiswa->penghasilan_ibu] ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Alamat</td>
                                        <td>: {{ $ortu->alamat_ortu ?? '-' }}, {{ $ortu->desa_ortu ?? '-' }}, {{
                                            $ortu->kec_ortu ?? '-' }}, {{ $ortu->kab_ortu ?? '-' }}, {{ $ortu->prov_ortu
                                            ?? '-' }}, </td>
                                    </tr>
                                </table>
                                <h5>Lampiran Dokumen :</h5>
                                @if ($mahasiswa->id_dokumen != null)
                                <ul class="text-dark" style="font-size: 15px">
                                    <li>
                                        <a href="{{ route('pmb.showFoto',$mahasiswa->doc_id) }}" target="_blank"
                                            class="text-decoration-none text-dark hover-effect">
                                            - Pas Foto
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('pmb.showKTP',$mahasiswa->doc_id) }}" target="_blank"
                                            class="text-decoration-none text-dark hover-effect">
                                            - KTP
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('pmb.showIjazah',$mahasiswa->doc_id) }}" target="_blank"
                                            class="text-decoration-none text-dark hover-effect">
                                            - Ijazah
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('pmb.showDaftarNilai',$mahasiswa->doc_id) }}" target="_blank"
                                            class="text-decoration-none text-dark hover-effect">
                                            - Daftar Nilai
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('pmb.showKK',$mahasiswa->doc_id) }}" target="_blank"
                                            class="text-decoration-none text-dark hover-effect">
                                            - Kartu Keluarga
                                        </a>
                                    </li>
                                    @if ($mahasiswa->kip != null)
                                    <li>
                                        <a href="{{ route('pmb.showKIP',$mahasiswa->doc_id) }}" target="_blank"
                                            class="text-decoration-none text-dark hover-effect">
                                            - Kartu KIP, PKH atau KKS
                                        </a>
                                    </li>
                                    @endif
                                    @if ($mahasiswa->status_pembayaran == 1)
                                    <li>
                                        <a href="{{ route('pmb.showPembayaran',$mahasiswa->bayar_id) }}" target="_blank"
                                            class="text-decoration-none text-dark hover-effect">
                                            - Bukti Pembayaran
                                        </a>
                                    </li>
                                    @endif
                                </ul>
                                @endif
                                <div class="form-group row mt-3">
                                    <div class="col-sm-10">
                                        @if ($mahasiswa->status_pembayaran == 1 && $mahasiswa->status_daftar == 0)
                                        <button type="submit" class="btn btn-sm btn-primary" data-toggle="modal"
                                            data-target="#btnKonfirmasi"><i class="bi bi-floppy"></i>
                                            Konfirmasi</button>
                                        @elseif($mahasiswa->status_pembayaran == 0 && $mahasiswa->status_daftar == 0)
                                        <button type="submit" class="btn btn-sm btn-danger" data-toggle="modal"
                                            data-target="#btnDitolak"><i class="bi bi-x-circle"></i> Ditolak</button>
                                        @elseif($mahasiswa->status_pembayaran == 1 && $mahasiswa->status_daftar == 1)

                                        @endif
                                        <a href="{{ route('pmb.index') }}" class="btn btn-light">Kembali <i
                                                class="bi bi-arrow-right"></i></a>
                                    </div>
                                </div>

                                {{--
                            </form> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Konfirmasi -->
<div class="modal fade" id="btnKonfirmasi" tabindex="-1" aria-labelledby="btnKonfirmasi" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="btnKonfirmasi">Konfirmasi Pendaftaran</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
            </div>
            <div class="modal-body text-dark">
                Apakah Anda yakin ingin validasi pendaftaran ini?
                <form id="konfirmasiSimpanBtn" action="{{ route('pmb.konfirmasiDaftar') }}" method="POST">
                    @csrf
                    <input type="hidden" name="mahasiswa_id" value="{{ $mahasiswa->id_mhs }}">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-sm btn-primary"
                    onclick="event.preventDefault(); document.getElementById('konfirmasiSimpanBtn').submit();">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Ditolak-->
<div class="modal fade" id="btnDitolak" tabindex="-1" aria-labelledby="btnDitolak" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="btnDitolak">Konfirmasi Pendaftaran</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
            </div>
            <div class="modal-body text-dark">
                Apakah Anda yakin ingin tolak pendaftaran ini?
                <form id="btnTolakPendaftaran" action="{{ route('pmb.tolakPendaftaran') }}" method="POST">
                    @csrf
                    <input type="hidden" name="mahasiswa_id" value="{{ $mahasiswa->id }}">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-sm btn-primary"
                    onclick="event.preventDefault(); document.getElementById('btnTolakPendaftaran').submit();">Ya,
                    Tolak</button>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script src="{{ asset('backend/vendor/toastr/js/toastr.min.js') }}"></script>

<script>
    @if(session('error'))
            toastr.success("{{ session('error') }}");
        @endif
</script>
@endpush