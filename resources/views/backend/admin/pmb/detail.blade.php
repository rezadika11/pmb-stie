@extends('layouts.backend.main')
@section('title','Detail Mahasiswa Baru')
@push('css')
<link rel="stylesheet" href="{{ asset('backend/vendor/toastr/css/toastr.min.css') }}">
@endpush
@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="col-sm-6 p-md-0 mb-4">
            <div class="welcome-text">
                <h3>Detail Mahasiswa Baru</h3>
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
                                {{-- <input type="text" name="id" value="{{ $id }}"> --}}
                                <table class="display" style="min-width: 100px">
                                    <tr>
                                        <td>Nama</td>
                                        <td>{{ $mahasiswa->nama }}</td>
                                    </tr>
                                </table>
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
@endpush