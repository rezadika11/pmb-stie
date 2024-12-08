@extends('layouts.backend.main')
@section('title','Dashboard')
@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    @if (Auth::user()->roles == 'mhs')
                    <h4>Selamat Datang {{ Auth::user()->name }},</h4>
                    @php
                        $tahunAkademik = date('Y') . '/' . (date('Y') + 1);
                    @endphp
                    <span class="text-dark">Penerimaan Mahasiswa Baru STIE Tamansiswa Banjarnegara {{ $tahunAkademik }}</span>
                    @endif
                </div>
            </div>
        </div>
        @if (Auth::user()->roles == 'admin')
        <div class="row">
            <div class="col-lg-3 col-sm-6">
                <div class="card">
                    <div class="stat-widget-one card-body">
                        <div class="stat-icon d-inline-block">
                            <i class="ti-money text-success border-success"></i>
                        </div>
                        <div class="stat-content d-inline-block">
                            <div class="stat-text">Profit</div>
                            <div class="stat-digit">1,012</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="card">
                    <div class="stat-widget-one card-body">
                        <div class="stat-icon d-inline-block">
                            <i class="ti-user text-primary border-primary"></i>
                        </div>
                        <div class="stat-content d-inline-block">
                            <div class="stat-text">Customer</div>
                            <div class="stat-digit">961</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="card">
                    <div class="stat-widget-one card-body">
                        <div class="stat-icon d-inline-block">
                            <i class="ti-layout-grid2 text-pink border-pink"></i>
                        </div>
                        <div class="stat-content d-inline-block">
                            <div class="stat-text">Projects</div>
                            <div class="stat-digit">770</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="card">
                    <div class="stat-widget-one card-body">
                        <div class="stat-icon d-inline-block">
                            <i class="ti-link text-danger border-danger"></i>
                        </div>
                        <div class="stat-content d-inline-block">
                            <div class="stat-text">Referral</div>
                            <div class="stat-digit">2,781</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @elseif (Auth::user()->roles == 'mhs')
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4><b>Silahkan klik dibawah ini untuk mengisi formulir pendafatran:</b></h4>
                        <a href="{{ route('formulir.index') }}" class="text-primary">Formulir Pendaftaran</a>
                        <br>
                        <br>
                        <h4><b>Customer Service Penerimaan Mahasiswa Baru</b></h4>
    
                        <h6>STIE Tamansiswa Banjarnegara</h6>
    
                        <h6>Hotline WA :</h6>
                        <ul>
                        <li>
                            <h6>082138157660 (Fera, Chat)</h6>
                        </li>
                        <li>
                            <h6>08122533700 (Dina, Chat Only)</h6>
                        </li>
                        </ul>
                        <h6>Website: <a href="www.pmb.stietambara.ac.id" class="text-secondary">pmb.stietambara.ac.id</a></h6>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection