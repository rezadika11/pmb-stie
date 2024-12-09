@extends('layouts.backend.main')
@section('title','Pendaftaran Mahasiswa Baru')
@push('css')
<link href="{{ asset('backend/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('backend/vendor/toastr/css/toastr.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/css/toastr-custom.css') }}">
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
                <h3>@yield('title')</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="dataTable" class="display" style="min-width: 100px">
                                <thead>
                                    <tr>
                                        <th width="5%">No.</th>
                                        <th>No Pendaftaran</th>
                                        <th>Nama</th>
                                        <th>Aksi</th>
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
<script src="{{ asset('backend/vendor/toastr/js/toastr.min.js') }}"></script>

<script>
   $(document).ready(function() {
        $('#dataTable').DataTable({
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
                url: '{{ route('pmb.datatable') }}',
                type: 'GET'
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
                    data: 'aksi', 
                    name: 'aksi', 
                    orderable: false, 
                    searchable: false,
                }
            ],
        });

        
        @if(session('success'))
            toastr.success("{{ session('success') }}");
        @endif
    });
</script>
@endpush