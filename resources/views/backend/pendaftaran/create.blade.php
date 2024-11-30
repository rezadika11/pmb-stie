@extends('layouts.backend.main')
@section('title','Tambah Pendaftaran')
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
                            <form id="formSimpan" >
                                @csrf
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label text-dark">Judul<span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" id="name" name="name" class="form-control" placeholder="Masukan Judul" autofocus>
                                        <div class="name-error text-danger"></div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-sm-2 col-form-label text-dark">Isi<span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                       <textarea id="editor" name="content">
                                       </textarea>
                                       <div class="content-error text-danger"></div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary"><i class="bi bi-floppy"></i> Simpan</button>
                                        <a href="{{ route('pendaftaran.index') }}" class="btn btn-light">Kembali <i class="bi bi-arrow-right"></i></a>
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
            selector: '#editor',  // Ganti ini sesuai dengan ID textarea Anda
            license_key: 'gpl',
            plugins: [
                "advlist", "anchor", "autolink", "charmap", "fullscreen", 
                "help", "image", "insertdatetime", "link", "lists", "media", 
                "preview", "searchreplace", "table", "visualblocks", "accordion", "table",
            ],
            height: 550,
            toolbar: "undo redo | fontfamily fontsize | styles | bold italic underline strikethrough | align | bullist numlist | table | link image | accordion | fullscreen",
            menubar: false,  // Menyembunyikan menu bar jika tidak diperlukan
            image_advtab: true,  // Menambahkan tab tambahan untuk pengaturan gambar
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
                            url: "{{ route('pendaftaran.image') }}", 
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
</script>
<script>
    // let editor1 = new RichTextEditor("#isiEditor");
$(document).ready(()=>{
    $('#formSimpan').submit(function (e) { 
        e.preventDefault(); 
        
        // Ambil konten dari TinyMCE
        let content = tinymce.get('editor').getContent();
        
        // Hapus "../storage/" dari path gambar
        content = content.replace(/(src=["'])\.\.\/storage\//g, '$1/storage/');

        // Siapkan data untuk dikirim
        let formData = $(this).serializeArray();
        
        // Tambahkan konten TinyMCE ke formData
        formData.push({
            name: 'content',
            value: content
        });

        $.ajax({ 
            type: "POST", 
            url: "{{ route('pendaftaran.store') }}", 
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
})
</script>
@endpush