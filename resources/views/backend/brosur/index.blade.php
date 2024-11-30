@extends('layouts.backend.main')
@section('title','Brosur')
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
                                    <label class="col-sm-2 col-form-label text-dark">Judul<span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" id="name" name="name" class="form-control" 
                                               placeholder="Masukan Judul" value="{{ $data->name }}" autofocus>
                                        <div class="name-error text-danger"></div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label text-dark">Isi</label>
                                    <div class="col-sm-10">
                                       <textarea name="content" class="form-control" id="content" cols="30" rows="5">{{ $data->content }}</textarea>
                                        <div class="content-error text-danger"></div>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="" class="col-sm-2 col-form-label text-dark">File Brosur<span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="file" name="image" id="image" class="form-control">
                                        <div class="image-error text-danger"></div>
                                    </div>
                                    <div class="col-sm-10 offset-sm-2 mt-2">
                                        <a href="{{ asset('storage/' . $data->path) }}" target="_blank" class="text-danger">{{ $data->image }}</a>
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
                url: "{{ route('brosur.update', $data->id) }}", 
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
                        $('.name-error, .content-error, .image-error').text('');
                        
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
