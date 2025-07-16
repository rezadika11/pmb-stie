@extends('layouts.backend.main')
@section('title','Edit Biaya Pendaftaran')
@push('css')
<link rel="stylesheet" href="{{ asset('backend/vendor/toastr/css/toastr.min.css') }}">
<style>
    .form-group label {
        font-weight: 600;
    }

    .currency-input {
        position: relative;
    }

    .currency-input::before {
        content: 'Rp';
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
        z-index: 1;
    }

    .currency-input input {
        padding-left: 35px;
    }
</style>
@endpush

@section('content')
<div class="content-body text-dark">
    <div class="container-fluid">
        <div class="col-sm-6 p-md-0 mb-4">
            <div class="welcome-text">
                <h3>@yield('title')</h3>
            </div>

        </div>
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('biaya_prodi.update', $biayaProdi->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="id_gelombang">Gelombang <span class="text-danger">*</span></label>
                                        <select name="id_gelombang" id="id_gelombang"
                                            class="form-control @error('id_gelombang') is-invalid @enderror" required>
                                            <option value="">Pilih Gelombang</option>
                                            @foreach($gelombangs as $gelombang)
                                            <option value="{{ $gelombang->id }}" {{ (old('id_gelombang', $biayaProdi->
                                                id_gelombang) == $gelombang->id) ? 'selected' : '' }}>
                                                {{ $gelombang->nama_gelombang }} - {{ $gelombang->tahunAkademik ?
                                                substr($gelombang->tahunAkademik->kode, 0, 4) . '/' .
                                                substr($gelombang->tahunAkademik->kode, 4, 4) : 'N/A' }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('id_gelombang')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="program_studi">Program Studi <span
                                                class="text-danger">*</span></label>
                                        <select name="program_studi" id="program_studi"
                                            class="form-control @error('program_studi') is-invalid @enderror" required>
                                            <option value="">Pilih Program Studi</option>
                                            @foreach($prodi_studi as $kode => $nama)
                                            <option value="{{ $kode }}" {{ (old('program_studi', $biayaProdi->
                                                program_studi) == $kode) ? 'selected' : '' }}>
                                                {{ $nama }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('program_studi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="biaya_pendaftaran">Biaya Pendaftaran <span
                                                class="text-danger">*</span></label>
                                        <div class="currency-input">
                                            <input type="number" name="biaya_pendaftaran" id="biaya_pendaftaran"
                                                class="form-control @error('biaya_pendaftaran') is-invalid @enderror"
                                                value="{{ old('biaya_pendaftaran', $biayaProdi->biaya_pendaftaran) }}"
                                                min="0" required>
                                        </div>
                                        @error('biaya_pendaftaran')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="biaya_tri_dharma">Biaya Tri Dharma <span
                                                class="text-danger">*</span></label>
                                        <div class="currency-input">
                                            <input type="number" name="biaya_tri_dharma" id="biaya_tri_dharma"
                                                class="form-control @error('biaya_tri_dharma') is-invalid @enderror"
                                                value="{{ old('biaya_tri_dharma', $biayaProdi->biaya_tri_dharma) }}"
                                                min="0" required>
                                        </div>
                                        @error('biaya_tri_dharma')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="biaya_ospek">Biaya Ospek <span class="text-danger">*</span></label>
                                        <div class="currency-input">
                                            <input type="number" name="biaya_ospek" id="biaya_ospek"
                                                class="form-control @error('biaya_ospek') is-invalid @enderror"
                                                value="{{ old('biaya_ospek', $biayaProdi->biaya_ospek) }}" min="0"
                                                required>
                                        </div>
                                        @error('biaya_ospek')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="biaya_spp">Biaya SPP <span class="text-danger">*</span></label>
                                        <div class="currency-input">
                                            <input type="number" name="biaya_spp" id="biaya_spp"
                                                class="form-control @error('biaya_spp') is-invalid @enderror"
                                                value="{{ old('biaya_spp', $biayaProdi->biaya_spp) }}" min="0" required>
                                        </div>
                                        @error('biaya_spp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="biaya_sks">Biaya SKS <span class="text-danger">*</span></label>
                                        <div class="currency-input">
                                            <input type="number" name="biaya_sks" id="biaya_sks"
                                                class="form-control @error('biaya_sks') is-invalid @enderror"
                                                value="{{ old('biaya_sks', $biayaProdi->biaya_sks) }}" min="0" required>
                                        </div>
                                        @error('biaya_sks')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <div class="custom-control custom-checkbox">
                                            <input type="hidden" name="gratis_untuk_kip" value="0">
                                            <input type="checkbox" class="custom-control-input" id="gratis_untuk_kip"
                                                name="gratis_untuk_kip" value="1" {{ old('gratis_untuk_kip',
                                                $biayaProdi->gratis_untuk_kip) ? 'checked' :
                                            '' }}>
                                            <label class="custom-control-label" for="gratis_untuk_kip">
                                                Gratis untuk mahasiswa KIP
                                            </label>
                                        </div>
                                        <small class="text-muted">Centang jika mahasiswa KIP tidak dikenakan
                                            biaya</small>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-floppy"></i> Update
                                </button>
                                <a href="{{ route('biaya_prodi.index') }}" class="btn btn-light ml-2">
                                    Kembali <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card ">
                    <div class="card-header">
                        <h5 class="card-title">Informasi Saat Ini</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm" style="color: #313030">
                            <tr>
                                <td><strong>Gelombang:</strong></td>
                                <td>{{ $biayaProdi->gelombang->nama_gelombang }}</td>
                            </tr>
                            <tr>
                                <td><strong>Program Studi:</strong></td>
                                <td>{{ $biayaProdi->nama_program_studi }}</td>
                            </tr>
                            <tr>
                                <td><strong>Total Biaya:</strong></td>
                                <td>Rp {{ number_format($biayaProdi->total_biaya, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td><strong>KIP Gratis:</strong></td>
                                <td>
                                    @if($biayaProdi->gratis_untuk_kip)
                                    <span class="badge badge-success">Ya</span>
                                    @else
                                    <span class="badge badge-secondary">Tidak</span>
                                    @endif
                                </td>
                            </tr>
                        </table>

                        <div class="alert alert-warning mt-3">
                            <h6><i class="bi bi-exclamation-triangle"></i> Perhatian:</h6>
                            <p class="mb-0 text-dark">Perubahan biaya akan mempengaruhi semua mahasiswa yang mendaftar
                                di
                                gelombang dan program studi ini.</p>
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
    $(document).ready(function() {
        // Format number input
        $('input[type="number"]').on('input', function() {
            let value = $(this).val();
            if (value < 0) {
                $(this).val(0);
            }
        });

        // Toastr notifications
        @if(session('error'))
            toastr.error('{{ session('error') }}');
        @endif
    });
</script>
@endpush