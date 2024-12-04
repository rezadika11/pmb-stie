<div class="step" data-step="3">
    <div class="card-header">
        Program Studi
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    @if(isset($mhs->id_program_studi))
                    <input type="hidden" name="id_prodi" value="{{ $mhs->id_program_studi }}">
                    @endif
                    <input type="hidden" name="id_mahasiswa" value="{{ $mhs->id ?? '' }}">
                    <label>Jenis Pendaftaran</label>
                    <select class="form-control" name="jenis_pendaftaran" id="jenis_pendaftaran">
                        <option disabled selected>Pilih Jenis Pendaftaran</option>
                        @foreach ($jenis_daftar as $key => $val )
                        <option value="{{ $key }}" {{ old('jenis_pendaftaran', $prodi->jenis_pendaftaran ?? '')==$key ? 'selected' : '' }}>
                            {{ $val }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Program Studi</label>
                    <select class="form-control" name="program_studi" id="program_studi">
                        <option disabled selected>Pilih Prodi Studi</option>
                        @foreach ($prodi_studi as $key => $val )
                        <option value="{{ $key }}" {{ old('program_studi', $prodi->program_studi ?? '')==$key ? 'selected' : '' }}>
                            {{ $val }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Jenis Kelas</label>
                    <select class="form-control" name="jenis_kelas" id="jenis_kelas">
                        <option disabled selected>Pilih Jenis Kelas</option>
                        @foreach ($kelas as $key => $val )
                        <option value="{{ $key }}" {{ old('jenis_kelas', $prodi->jenis_kelas ?? '')==$key ? 'selected' : '' }}>
                            {{ $val }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>