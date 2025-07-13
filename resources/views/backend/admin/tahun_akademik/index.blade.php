@extends('layouts.backend.main')
@section('title','Tahun Akademik')
@push('css')
<link href="{{ asset('backend/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('backend/vendor/toastr/css/toastr.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/css/toastr-custom.css') }}">
<link rel="stylesheet" href="{{ asset('backend/vendor/datepicker/jquery-ui.css') }}">
<style>
    .table-responsive {
        overflow-x: auto !important;
        /* Pastikan ada scroll jika konten lebih lebar */
    }

    #dataTable {
        table-layout: fixed !important;
        /* Menghindari kolom lebar otomatis */
        width: 100% !important;
        /* Pastikan tabel memenuhi lebar kontainer */
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
                        <button type="button" id="modalTahun" class="btn btn-sm btn-primary"><i
                                class="bi bi-plus-square"></i> Tambah</button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="dataTable" class="display" style="min-width: 100px">
                                <thead>
                                    <tr>
                                        <th width="5%">No.</th>
                                        <th>Tahun Akademik</th>
                                        <th>Tanggal Mulai</th>
                                        <th>Tanggal Selesai</th>
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
@include('backend.tahun_akademik.modal-tahun')
@endsection
@push('js')
<script src="{{ asset('backend/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend/vendor/toastr/js/toastr.min.js') }}"></script>
<script src="{{ asset('backend/vendor/datepicker/jquery-ui.js') }}"></script>

<script>
    $(document).ready(function() {
        $("#tglMulai").datepicker({
            changeMonth: true,
            changeYear: true,
            yearRange: "1950:" + (new Date().getFullYear() + 2),
            dateFormat: 'yy-mm-dd', 
        });

        $("#tglSelesai").datepicker({
            changeMonth: true,
            changeYear: true,
            yearRange: "1950:" + (new Date().getFullYear() + 2),
            dateFormat: 'yy-mm-dd', 
        });           

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
                url: '{{ route('admin.tahun_akademik.datatable') }}',
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
                    data: 'kode', 
                    name: 'kode',
                },
                { 
                    data: 'tanggal_mulai', 
                    name: 'tanggal_mulai',
                },
                { 
                    data: 'tanggal_selesai', 
                    name: 'tanggal_selesai',
                },
                {
                    data: 'aksi', 
                    name: 'aksi', 
                    orderable: false, 
                    searchable: false,
                }
            ],
        });

        $('#modalTahun').click((e) => {
            e.preventDefault();
            $('#modalTahunAkademik').modal('show');
        })

        $('#btnUbahTahun').click((e) => {
            e.preventDefault();

            let formData = {
                kode : $('.kode').val(),
                tanggal_mulai : $('.tanggal_mulai').val(),
                tanggal_selesai : $('.tanggal_selesai').val(),
            }

            $.ajax({
                type: "POST",
                url: "{{ route('tahun_akademik.store') }}",
                data: formData,
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                beforeSend: function () {
                    // Bersihkan error sebelumnya
                    $.each($('.text-danger'), function () {
                        $(this).text('');
                    });
                    $('button[type="submit"]').html('<i class="spinner-border spinner-border-sm"></i> Menyimpan...').attr('disabled', true);
                },
                success: function (res) {
                    // toastr.success('Users baru berhasil disimpan.');
                    window.location.href = res.redirect;
                },
                error: function (xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function (key, value) {
                            $('.' + key + '-error').text(value[0]);
                        });
                    } else {
                        toast.error("Unexpected error response:", xhr);
                        toast.error("An unexpected error occurred. Please try again.");
                    }
                },
                complete: function () {
                    $('button[type="submit"]').html('Simpan').attr('disabled', false);
                }
            });
            
        })

        $(document).on('click', '.btn-set-aktif', function () {
            const id = $(this).data('id');
            $.ajax({
                url: `/admin/tahun-akademik/set-aktif/${id}`,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                success: function (response) {
                    toastr.success(response.message);
                    $('#dataTable').DataTable().ajax.reload(); // Reload DataTable setelah update
                },
                error: function (xhr) {
                    alert(xhr.responseJSON.message || 'Terjadi kesalahan.');
                }
            });
        });



        
        @if(session('success'))
            toastr.success("{{ session('success') }}");
        @endif
    });
</script>
@endpush