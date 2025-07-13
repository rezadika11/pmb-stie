@extends('layouts.backend.main')
@section('title','Profil')
@push('css')
<link rel="stylesheet" href="{{ asset('backend/vendor/toastr/css/toastr.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/css/toastr-custom.css') }}">
@endpush
@section('content')
<div class="content-body text-dark">
    <div class="container-fluid">
        <div class="col-sm-6 p-md-0 mb-4">
            <div class="welcome-text">
               <h3>Edit Profil</h3> 
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('superadmin.updateProfile') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Nama</label>
                                       <input type="text" class="form-control @error('name')
                                           is-invalid
                                       @enderror" name="name" value="{{ old('name', $profil->name) }}">
                                       @error('name')
                                           <div class="invalid-feedback">
                                            {{ $message }}
                                           </div>
                                       @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Email</label>
                                       <input type="text" class="form-control @error('email')
                                           is-invalid
                                       @enderror" name="email" value="{{ old('email', $profil->email) }}">
                                       @error('email')
                                           <div class="invalid-feedback">
                                            {{ $message }}
                                           </div>
                                       @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Password</label>
                                       <input type="password" class="form-control @error('password1')
                                           is-invalid
                                       @enderror" name="password1">
                                       @error('password1')
                                           <div class="invalid-feedback">
                                            {{ $message }}
                                           </div>
                                       @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Ulangi Password</label>
                                       <input type="password" class="form-control @error('password2')
                                           is-invalid
                                       @enderror" name="password2">
                                       @error('password2')
                                           <div class="invalid-feedback">
                                            {{ $message }}
                                           </div>
                                       @enderror
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-sm btn-primary"><i class="bi-floppy"></i> Update</button>
                        </form>
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
    @if(session('success'))
        toastr.success('{{ session('success') }}', 'Berhasil');
    @endif

    @if(session('error'))
        toastr.error('{{ session('error') }}', 'Gagal');
    @endif
</script>
@endpush