@extends('layouts.backend.main')
@section('title','Edit Testimoni')
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
                            <form id="formSimpan" enctype="multipart/form-data">
                                @csrf
                                {{-- @method('PUT') --}}
                                <input type="hidden" name="id" value="{{ $testimoni->id }}">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label text-dark">Nama<span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" id="name" name="name" class="form-control" value="{{ $testimoni->name }}" placeholder="Masukan Nama" autofocus>
                                        <div class="name-error text-danger"></div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label text-dark">Alumni<span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" id="alumni" name="alumni" class="form-control" value="{{ $testimoni->alumni }}" placeholder="Masukan Alumni" autofocus>
                                        <div class="alumni-error text-danger"></div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label text-dark">Posisi Sekarang<span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" id="kerja" name="kerja" class="form-control" value="{{ $testimoni->kerja }}" placeholder="Masukan Posisi Sekarang" autofocus>
                                        <div class="kerja-error text-danger"></div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label text-dark">Isi Testimoni<span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                       <textarea name="isi" id="isi" class="form-control" cols="20" rows="5" placeholder="Masukan isi testimoni">{{ $testimoni->isi }}</textarea>
                                        <div class="isi-error text-danger"></div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-sm-2 col-form-label text-dark">Gambar<span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="file" name="image" id="image" class="form-control">
                                        <div class="image-error text-danger"></div>
                                    </div>
                                    <div class="col-sm-10 offset-sm-2 mt-2">
                                        <img id="previewImage"
                                             alt="Preview Gambar"
                                            src="{{ asset('storage/' . $testimoni->path) }}"
                                             style="max-width: 400px;">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary"><i class="bi bi-floppy"></i> Simpan</button>
                                        <a href="{{ route('testimoni.index') }}" class="btn btn-light">Kembali <i class="bi bi-arrow-right"></i></a>
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
            let formData = new FormData(this);
            $.ajax({
                type: "POST",
                url: "{{ route('testimoni.update', $testimoni->id) }}",
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

        // Preview gambar
        $('#image').change(function() {
            let reader = new FileReader();
            reader.onload = function(e) {
                $('#previewImage')
                    .attr('src', e.target.result)
                    .show();
            }
            reader.readAsDataURL(this.files[0]);
        });
    });
</script>
@endpush
