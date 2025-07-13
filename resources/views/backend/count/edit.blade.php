@extends('layouts.backend.main')
@section('title','Edit Pendaftaran')
@push('css')
<link rel="stylesheet" href="{{ asset('backend/vendor/toastr/css/toastr.min.css') }}">
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
                                {{-- @method('PUT') --}}
                                <input type="hidden" name="id" value="{{ $count->id }}">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label text-dark">Judul<span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" id="judul" name="judul" class="form-control" value="{{ old('judul',$count->judul) }}" placeholder="Masukan Judul" autofocus>
                                        <div class="judul-error text-danger"></div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label text-dark">Jumlah<span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="number" id="jumlah" name="jumlah" class="form-control" value="{{ old('jumlah',$count->jumlah) }}" placeholder="Masukan Jumlah" autofocus>
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
            let formData = $(this).serialize();
            $.ajax({
                type: "POST",
                url: "{{ route('count.update', $count->id) }}",
                data: formData,
                dataType: "json",
                success: function (res) {
                    // Redirect ke halaman pendaftaran index
                    window.location.href = res.redirect;
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        let errors = xhr.responseJSON.errors;
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
    });
</script>
@endpush
