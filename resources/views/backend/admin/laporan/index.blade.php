@extends('layouts.backend.main')
@section('title','Laporan Penerimaan Mahasiswa Baru')
@push('css')
<link href="{{ asset('backend/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<style>
    .table-responsive {
    overflow-x: auto !important; /* Pastikan ada scroll jika konten lebih lebar */
}

#dataTable {
    table-layout: fixed !important; /* Menghindari kolom lebar otomatis */
    width: 100% !important; /* Pastikan tabel memenuhi lebar kontainer */
}
</style>
@endpush
@section('content')
<div class="content-body text-dark">
    <div class="container-fluid">
        <div class="col-sm-6 p-md-0 mb-4">
            <div class="welcome-text">
                <h3>
                    Laporan Penerimaan Mahasiswa Baru Tahun Ajaran&nbsp;&nbsp;
                    {{ substr($tahunAktif->kode ?? '', 0, 4) . '/' . substr($tahunAktif->kode ?? '', 4, 4) }}
                </h3>
                
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="col-md-2">
                            <select name="tahunAkademik" id="tahunAkademik" class="form-control">
                                <option value="" disabled selected>Pilih Tahun Akademik</option>
                                  @foreach($tahun as $option)
                                    <option value="{{ $option->kode }}" 
                                        {{ $tahunAktif && $tahunAktif->kode == $option->kode ? 'selected' : '' }}>
                                        {{ substr($option->kode, 0, 4) . '/' . substr($option->kode, 4, 4) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-10">
                            <button type="button" 
                            onclick="window.location.href='{{ route('laporan.export') }}?tahun_akademik=' + $('#tahunAkademik').val()" 
                            class="btn btn-sm btn-secondary">
                            <i class="bi bi-file-earmark-excel"></i> Cetak Laporan
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="dataTable" class="display" style="min-width: 100px">
                                <thead>
                                    <tr>
                                        <th width="5%">No.</th>
                                        <th>No Pendaftaran</th>
                                        <th>Nama</th>
                                        <th>Jenis Kelamin</th>
                                        <th>TTL</th>
                                        {{-- <th>Aksi</th> --}}
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script src="{{ asset('backend/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>

<script>
   $(document).ready(function() {
        // Simpan instance DataTable ke dalam variabel
        let dataTable = $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            scrollX: true,
            drawCallback: function() {
                $('.dataTables_scrollBody').css({
                    'border': 'none',
                    'border-bottom': 'none'
                });
                $('.dataTables_scrollHead').css({
                    'border': 'none',
                    'border-bottom': 'none'
                });
                $('.dataTables_scroll').css('border', 'none');
            },
            ajax: {
                url: '{{ route('laporan.datatable') }}',
                type: 'GET',
                data: function(d) {
                    // Tambahkan parameter filter tahun akademik
                    d.tahun_akademik = $('#tahunAkademik').val();
                }
            },
            columns: [
                { 
                    data: 'DT_RowIndex', 
                    name: 'DT_RowIndex', 
                    orderable: false, 
                    searchable: false,
                },
                { 
                    data: 'no_pendaftaran', 
                    name: 'no_pendaftaran',
                },
                { 
                    data: 'nama', 
                    name: 'nama',
                },
                {
                    data:'jenis_kelamin',
                    name:'jenis_kelamin',
                },
                {
                    data:'ttl',
                    name:'ttl',
                },
            ],
        });

        // Gunakan variabel dataTable yang sudah didefinisikan
        $('#tahunAkademik').on('change', function() {
            dataTable.ajax.reload(); // Gunakan ajax.reload() untuk me-reload tabel
        });
    });
</script>
@endpush