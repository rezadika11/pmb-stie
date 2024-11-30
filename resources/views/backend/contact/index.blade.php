@extends('layouts.backend.main')
@section('title','Kontak')
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
                                    <label for="" class="col-sm-2 col-form-label text-dark">Isi<span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                       <textarea id="editor" name="content">{{ old('content', $data->content) }}</textarea>
                                       <div class="content-error text-danger"></div>
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
<script src="{{ asset('backend/vendor/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('backend/vendor/toastr/js/toastr.min.js') }}"></script>
<script>
    tinymce.init({
        selector: '#editor',
        license_key: 'gpl',
        plugins: [
            "advlist", "anchor", "autolink", "charmap", "fullscreen", 
            "help", "insertdatetime", "link", "lists", 
            "preview", "searchreplace", "table", "visualblocks", "accordion", "table",
        ],
        height: 550,
        toolbar: "undo redo | fontfamily fontsize | styles | bold italic underline strikethrough | align | bullist numlist | table | link  | accordion | fullscreen",
        menubar: false,
        image_advtab: true,
        table_toolbar: 'tableprops tabledelete | tableinsertrowbefore tableinsertrowafter tabledeleterow | tableinsertcolbefore tableinsertcolafter tabledeletecol',
        font_size_formats: '8pt 10pt 12pt 14pt 16pt 18pt 24pt 36pt 48pt',
    });

    $(document).ready(()=>{
        $('#formSimpan').submit(function (e) { 
            e.preventDefault(); 
            let formData = $(this).serialize(); 
            $.ajax({ 
                type: "POST", 
                url: "{{ route('kontak.update', $data->id) }}", 
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

        @if(session('success'))
            toastr.success("{{ session('success') }}");
        @endif
    });
</script>
@endpush
