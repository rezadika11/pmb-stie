@extends('layouts.backend.main')
@section('title','Dashboard')
@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    @if (Auth::user()->roles == 'superadmin')
                        <h4>Selamat Datang {{ Auth::user()->name }}</h4>
                    @elseif (Auth::user()->roles == 'admin')
                        <h4>Selamat Datang {{ Auth::user()->name }}</h4>
                    @elseif (Auth::user()->roles == 'mhs')
                    <h4>Selamat Datang {{ Auth::user()->name }},</h4>
                    @php
                        $tahunAkademik = date('Y') . '/' . (date('Y') + 1);
                    @endphp
                    <span class="text-dark">Penerimaan Mahasiswa Baru STIE Tamansiswa Banjarnegara {{ $tahunAkademik }}</span>
                    @endif
                </div>
            </div>
        </div>
        @if(Auth::user()->roles == 'superadmin')
            @include('backend.components.dashboard-superadmin')
        @elseif (Auth::user()->roles == 'admin')
            @include('backend.components.dashboard-admin')
        @elseif (Auth::user()->roles == 'mhs')
            @include('backend.components.dashboard-mhs')
        @endif
    </div>
</div>
@endsection