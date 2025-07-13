@extends('layouts.backend.main')
@section('title','Gelombang')
@push('css')
<link href="{{ asset('backend/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('backend/vendor/toastr/css/toastr.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/css/toastr-custom.css') }}">
<link rel="stylesheet" href="{{ asset('backend/vendor/datepicker/jquery-ui.css') }}">
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
                       <button type="button" id="modalTahun" class="btn btn-sm btn-primary"><i class="bi bi-plus-square"></i> Tambah</button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="dataTable" class="display" style="min-width: 100px">
                                <thead>
                                    <tr>
                                        <th width="5%">No.</th>
                                        <th>Nama Gelombang</th>
                                        <th>Tahun Akademik</th>
                                        <th>Tanggal Mulai</th>
                                        <th>Tanggal Selesai</th>
                                        <th>Biaya</th>
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
@include('backend.gelombang.modal-gelombang')
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
            ajax: '{{ route("gelombang.datatable") }}',
            columns: [
                { 
                    data: 'DT_RowIndex', 
                    name: 'DT_RowIndex', 
                    orderable: false, 
                    searchable: false,
                },
                { 
                    data: 'nama_gelombang', 
                    name: 'nama_gelombang', 
                },
                { 
                    data: 'tahun_akademik', 
                    name: 'tahun_akademik', 
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
                    data: 'biaya', 
                    name: 'biaya',
                },
                {
                    data: 'action', 
                    name: 'action', 
                    orderable: false, 
                    searchable: false,
                }
            ],
        });

          $('#modalTahun').click((e) => {
            e.preventDefault();
            $('#gelombangModalLabel').html("Tambah Gelombang");
            $('#gelombangModal').modal('show');
        })

          // Reset form and errors on modal close
          $('#gelombangModal').on('hidden.bs.modal', function () {
                $('#gelombangForm')[0].reset();
                $('#gelombang_id').val('');
                $('.text-danger').text('');
            });

        // Open modal for edit
        $('#dataTable').on('click', '.btn-edit', function () {
            var id = $(this).data('id');
            $.get(`/superadmin/gelombang/edit/${id}`, function (data) {
                $('#gelombangModalLabel').html("Edit Gelombang");
                $('#gelombangForm').trigger("reset");
                $('.error-text').html('');
                $('#gelombangModal').modal('show');
                $('#gelombang_id').val(data.id);
                $('#nama_gelombang').val(data.nama_gelombang);
                $('#tglMulai').val(data.tanggal_mulai);
                $('#tglSelesai').val(data.tanggal_selesai);
                $('#biaya').val(data.biaya);
            })
        });

        // Submit form via AJAX
        $('#gelombangForm').submit(function (e) {
            e.preventDefault();
            let formData = $(this).serialize();
            $.ajax({
                url: '{{ route("gelombang.store") }}',
                type: 'POST',
                data: formData,
                beforeSend: () => {
                    $('#btnSimpan')
                        .html(
                            '<i class="spinner-border spinner-border-sm"></i> Menyimpan...',
                        )
                        .attr('disabled', true);
                    $('.text-danger').text('');
                },
                success: (response) => {
                    if (response.success) {
                        // toastr.success(response.success);
                        // $('#modalGelombang').modal('hide');
                        // table.ajax.reload();
                        window.location.href = response.redirect;
                    }
                },
                error: (xhr) => {
                    if (xhr.status === 422) {
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function (key, value) {
                                $('.' + key + '_error').text(value[0]);
                            });
                        } else if (
                            xhr.responseJSON &&
                            xhr.responseJSON.error
                        ) {
                            toastr.error(xhr.responseJSON.error);
                        } else {
                            toastr.error(
                                'Terjadi kesalahan validasi yang tidak terduga.',
                            );
                        }
                    } else {
                        toastr.error(
                            xhr.responseJSON?.error ||
                                'Terjadi kesalahan server.',
                        );
                    }
                },
                complete: () => {
                    $('#btnSimpan').html('Simpan').attr('disabled', false);
                },
            });
        });

        // Delete record
        // $('body').on('click', '.delete-btn', function () {
        //     var id = $(this).data('id');
        //     if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
        //         $.ajax({
        //             type: "DELETE",
        //             url: "{{ route('gelombang.index') }}" + '/' + id,
        //             headers: {
        //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //             },
        //             success: function (response) {
        //                 toastr.success(response.success);
        //                 $('#dataTable').DataTable().ajax.reload();
        //             },
        //             error: function (response) {
        //                 toastr.error("Terjadi kesalahan saat menghapus data.");
        //             }
        //         });
        //     }
        // });

        // Set Aktif
        // $(document).on('click', '.btn-set-aktif', function () {
        //     var id = $(this).data('id');
        //     if (confirm("Apakah Anda yakin ingin mengaktifkan gelombang ini?")) {
        //         $.ajax({
        //             type: "POST",
        //             url: "{{ route('gelombang.setAktif', '') }}/" + id,
        //             headers: {
        //                 'X-CSRF-TOKEN': "{{ csrf_token() }}"
        //             },
        //             success: function (response) {
        //                 toastr.success(response.success);
        //                 $('#dataTable').DataTable().ajax.reload();
        //             },
        //             error: function (response) {
        //                 alert(xhr.responseJSON.message || 'Terjadi kesalahan.');
        //             }
        //         });
        //     }
        // });

        @if(session('success'))
            toastr.success("{{ session('success') }}");
        @endif

      });
    </script>
@endpush
