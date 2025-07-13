@extends('layouts.backend.main')
@section('title','Tambah Jumlah')
@push('css')
<link rel="stylesheet" href="{{ asset('backend/vendor/toastr/css/toastr.min.css') }}">
@endpush
@section('content')
<div class="content-body">
    <div class="container-fluid">
        {{-- <div class="row mb-5"> --}}
            <div class="col-sm-6 p-md-0 mb-4">
                <div class="welcome-text">
                    <h3>@yield('title')</h3>
                </div>
            </div>
        {{-- </div> --}}

        <div class="row">
            <div class="col-12">
                <div class="card">
                    {{-- <div class="card-header">
                       <a href="" class="btn btn-sm btn-primary"><i class="bi bi-plus-square"></i> Tambah</a>
                    </div> --}}
                    <div class="card-body">
                        <div class="basic-form">
                            <form id="formSimpan">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label text-dark">Judul<span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" id="judul" name="judul" class="form-control" placeholder="Masukan Judul" autofocus>
                                        <div class="judul-error text-danger"></div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label text-dark">Jumlah<span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="number" id="jumlah" name="jumlah" class="form-control" placeholder="Masukan Jumlah" autofocus>
                                        <div class="jumlah-error text-danger"></div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary"><i class="bi bi-floppy"></i> Simpan</button>
                                        <a href="{{ route('count.index') }}" class="btn btn-light">Kembali <i class="bi bi-arrow-right"></i></a>
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
<script>
    $(document).ready(()=>{
        $('#formSimpan').submit(function (e) {
            e.preventDefault();

            // let formData = {
            //     judul: $('#judul').val(),
            //     jumlah: $('#jumlah').val(),
            // }
            let formData = new FormData(this);

            $.ajax({
                type: "POST",
                url: "{{ route('count.store') }}",
                data: formData,
                contentType: false,
                processData: false,
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                success: function (res) {
                    // Redirect ke halaman pendaftaran index
                    window.location.href = res.redirect;
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        let errors = xhr.responseJSON.errors;

                        // Bersihkan error sebelumnya
                        $('.judul-error, .jumlah-error').text('');

                        $.each(errors, function(key, value) {
                            $('.' + key + '-error').text(value[0]);
                        });
                    } else {
                        console.error("Unexpected error response:", xhr);
                        alert("An unexpected error occurred. Please try again.");
                    }
                }
            });
        });
    })
</script>
@endpush
