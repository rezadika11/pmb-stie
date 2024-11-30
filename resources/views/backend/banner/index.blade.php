@extends('layouts.backend.main')
@section('title','Banner')
@push('css')
<link rel="stylesheet" href="{{ asset('backend/vendor/toastr/css/toastr.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/css/toastr-custom.css') }}">
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
                            <form id="formSimpan" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="id" value="{{ $data->id }}">
                                
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label text-dark">Judul Pertama<span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" id="title1" name="title1" class="form-control" 
                                               placeholder="Masukan Judul Pertama" value="{{ $data->title1 }}" autofocus>
                                        <div class="title1-error text-danger"></div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label text-dark">Judul Kedua<span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" id="title2" name="title2" class="form-control" 
                                               placeholder="Masukan Judul Kedua" value="{{ $data->title2 }}">
                                        <div class="title2-error text-danger"></div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label text-dark">Deskripsi</label>
                                    <div class="col-sm-10">
                                       <textarea name="description" class="form-control" id="description" cols="30" rows="5">{{ $data->description }}</textarea>
                                        <div class="description-error text-danger"></div>
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
                                             src="{{ asset('storage/' . $data->path) }}" 
                                             alt="Preview Gambar" 
                                             style="max-width: 400px;">
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-floppy"></i> Simpan
                                        </button>
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
            
            // Siapkan FormData untuk mengirim file
            let formData = new FormData(this);
            
            $.ajax({ 
                type: "POST", 
                url: "{{ route('banner.update', $data->id) }}", 
                data: formData, 
                contentType: false,
                processData: false, 
                dataType: "json", 
                success: function (res) { 
                    window.location.href = res.redirect;
                }, 
                error: function(xhr) { 
                    if (xhr.responseJSON && xhr.responseJSON.errors) { 
                        let errors = xhr.responseJSON.errors; 
                        
                        // Bersihkan error sebelumnya
                        $('.title1-error, .title2-error, .image-error').text('');
                        
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

        @if(session('success'))
            toastr.success("{{ session('success') }}");
        @endif

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
    })
</script>
@endpush
