@extends('layouts.backend.main')
@section('title','Edit Registrasi')
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
                                <input type="hidden" name="id" value="{{ $registrasi->id }}">
                                
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label text-dark">Judul<span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" id="name" name="name" class="form-control" placeholder="Masukan Judul" value="{{ old('name', $registrasi->name) }}" autofocus>
                                        <div class="name-error text-danger"></div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-sm-2 col-form-label text-dark">Isi<span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                       <textarea id="editor" name="content">{{ old('content', $registrasi->content) }}</textarea>
                                       <div class="content-error text-danger"></div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary"><i class="bi bi-floppy"></i> Simpan</button>
                                        <a href="{{ route('registrasi.index') }}" class="btn btn-light">Kembali <i class="bi bi-arrow-right"></i></a>
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
            "help", "image", "insertdatetime", "link", "lists", "media", 
            "preview", "searchreplace", "table", "visualblocks", "accordion", "table",
        ],
        height: 550,
        toolbar: "undo redo | fontfamily fontsize | styles | bold italic underline strikethrough | align | bullist numlist | table | link image | accordion | fullscreen",
        menubar: false,
        image_advtab: true,
        table_toolbar: 'tableprops tabledelete | tableinsertrowbefore tableinsertrowafter tabledeleterow | tableinsertcolbefore tableinsertcolafter tabledeletecol',
        font_size_formats: '8pt 10pt 12pt 14pt 16pt 18pt 24pt 36pt 48pt',
        file_picker_callback: function (callback, value, meta) {
            if (meta.filetype === 'image') {
                var input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');
                
                input.onchange = function () {
                    var file = this.files[0];
                    
                    // Cek ukuran file
                    if (file.size > 2 * 1024 * 1024) { 
                        alert('Ukuran file terlalu besar. Maksimal 2MB.');
                        return;
                    }

                    // Buat FormData untuk upload
                    var formData = new FormData();
                    formData.append('file', file);

                    // Ajax upload
                    $.ajax({
                        url: "{{ route('registrasi.image') }}", 
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        success: function (response) {
                            // Panggil callback dengan URL gambar yang dikembalikan dari server
                            callback(response.location, {
                                alt: file.name
                            });
                        },
                        error: function (xhr) {
                            // Tangani error
                            alert('Upload gagal: ' + (xhr.responseJSON?.message || 'Terjadi kesalahan'));
                        }
                    });
                };
                
                input.click();
            }
        },
    });

    $(document).ready(()=>{
        $('#formSimpan').submit(function (e) { 
            e.preventDefault(); 
            let formData = $(this).serialize(); 
            $.ajax({ 
                type: "POST", 
                url: "{{ route('registrasi.update', $registrasi->id) }}", 
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