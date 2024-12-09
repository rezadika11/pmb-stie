@extends('layouts.backend.main')
@section('title','Edit Profil')
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
                                <input type="hidden" name="id" value="{{ $user->id }}">
                                
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label text-dark">Nama Lengkap<span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" id="name" name="name" class="form-control" placeholder="Masukan Nama Lengkap" value="{{ old('name',$user->name) }}" autofocus>
                                        <div class="name-error text-danger"></div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label text-dark">Email<span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="email" id="email" name="email" class="form-control" placeholder="Masukan Email" value="{{ old('email',$user->email) }}" autofocus>
                                        <div class="email-error text-danger"></div>
                                    </div>
                                </div>
                                @if ($user->roles != 'mhs')
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label text-dark">Hak Akses<span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <select name="roles" id="roles" class="form-control">
                                            <option value="" selected disabled>Pilih Hak Akses</option>
                                            @foreach ($roles as $key => $val)
                                            <option value="{{ $key }}" 
                                                {{ old('roles', $user->roles ?? '') == $key ? 'selected' : '' }}>
                                                {{ $val }}
                                            </option>
                                            @endforeach
                                        </select>
                                        <div class="roles-error text-danger"></div>
                                    </div>
                                </div>
                                @endif
                               
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label text-dark">Password<span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="password" id="password" name="password" class="form-control" placeholder="Masukan Password" autofocus>
                                        <div class="password-error text-danger"></div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label text-dark">Ulangi Password<span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="password" id="password1" name="password1" class="form-control" placeholder="Masukan Ulangi Password" autofocus>
                                        <div class="password1-error text-danger"></div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary"><i class="bi bi-floppy"></i> Simpan</button>
                                        <a href="{{ route('users.index') }}" class="btn btn-light">Kembali <i class="bi bi-arrow-right"></i></a>
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
            // let formData = new FormData(this);
            let formData = {
                name : $('#name').val(),
                email : $('#email').val(),
                roles : $('#roles').val(),
                password : $('#password').val(),
                password1 : $('#password1').val(),
            }

            $.ajax({ 
                type: "POST", 
                url: "{{ route('users.update', $user->id) }}", 
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
                    // $('button[type="submit"]').html('<i class="spinner-border spinner-border-sm"></i> Menyimpan...').attr('disabled', true);
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
                        console.error("Unexpected error response:", xhr);
                        alert("An unexpected error occurred. Please try again.");
                    }
                },
            }); 
        });
    });
</script>
@endpush