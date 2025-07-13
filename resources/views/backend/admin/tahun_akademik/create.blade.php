@extends('layouts.backend.main')
@section('title','Tambah Tahun Akademik')
@push('css')
<link rel="stylesheet" href="{{ asset('backend/vendor/toastr/css/toastr.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/vendor/datepicker/jquery-ui.css') }}">
@endpush
@section('content')
<div class="content-body">
    <div class="container-fluid">
            <div class="col-sm-6 p-md-0 mb-4">
                <div class="welcome-text">
                    <h3>@yield('title')</h3>
                </div>
            </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="basic-form">
                            <form id="formSimpan">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label text-dark">Tahun Akademik<span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" id="kode" name="kode" class="kode form-control" placeholder="Masukan Tahun Akademik" autofocus>
                                        <div class="kode-error text-danger"></div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label text-dark">Tanggal Mulai<span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" id="tglMulai" name="tanggal_mulai" class="tanggal_mulai form-control" placeholder="Masukan Tanggal Mulai">
                                        <div class="tanggal_mulai-error text-danger"></div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label text-dark">Tanggal Selesai<span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" id="tglSelesai" name="tanggal_selesai" class="tanggal_selesai form-control" placeholder="Masukan Tanggal Selesai">
                                        <div class="tanggal_selesai-error text-danger"></div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary"><i class="bi bi-floppy"></i> Simpan</button>
                                        <a href="{{ route('tahun_akademik.index') }}" class="btn btn-light">Kembali <i class="bi bi-arrow-right"></i></a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script src="{{ asset('backend/vendor/toastr/js/toastr.min.js') }}"></script>
<script src="{{ asset('backend/vendor/datepicker/jquery-ui.js') }}"></script>
<script>
$(document).ready(() => {
    $("#tglMulai").datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: "1950:" + (new Date().getFullYear() + 1),
        dateFormat: 'yy-mm-dd', 
    });

    $("#tglSelesai").datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: "1950:" + (new Date().getFullYear() + 1),
        dateFormat: 'yy-mm-dd', 
    });

    $('#formSimpan').submit(function (e) {
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
                $('button[type="submit"]').html('<i class="bi bi-floppy"></i> Simpan').attr('disabled', false);
            }
        });
    });
});
</script>
@endpush