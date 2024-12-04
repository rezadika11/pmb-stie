<div class="step active" data-step="1">
    <div class="card-header">
        Identitas Pribadi
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" class="form-control" name="nama" value="{{ $user }}"  readonly>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>NIK</label>
                    <input type="number" class="form-control" name="nik" value="{{ old('nik',$mhs->nik ?? '') }}"  placeholder="Masukan NIK">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Tempat Lahir</label>
                    <input type="text" class="form-control" name="tempat_lahir" value="{{ old('tempat_lahir',$mhs->tempat_lahir ?? '') }}"  placeholder="Masukan Tempat Lahir">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Tanggal Lahir</label>
                    <input type="date" class="form-control" name="tanggal_lahir" value="{{ old('tanggal_lahir',$mhs->tanggal_lahir ?? '') }}" >
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Jenis Kelamin</label>
                    <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" >
                        <option value="" disabled selected>Pilih Jenis Kelamin</option>
                        @foreach ($jk as $key => $val )
                        <option value="{{ $key }}" {{ old('jenis_kelamin',$mhs->jenis_kelamin ?? '')==$key ? 'selected' : '' }}>
                            {{ $val }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Agama</label>
                    <select class="form-control" id="agama" name="agama" >
                        <option value="" disabled selected>Pilih Agama</option>
                        @foreach ($agm as $key => $val )
                        <option value="{{ $key }}" {{ old('agama',$mhs->agama ?? '')==$key ? 'selected' : '' }}>
                            {{ $val }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Status Perkawinan</label>
                    <select name="kawin" id="kawin" class="form-control" >
                        <option value="" disabled selected>Pilih Status Perkawinan</option>
                        @foreach ($kawin as $key => $val )
                        <option value="{{ $key }}" {{ old('kawin',$mhs->status_kawin ?? '')==$key ? 'selected' : '' }}>
                            {{ $val }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Kewarganegaraan</label>
                    <select name="kewarganegaraan" id="kewarganegaraan" class="form-control" >
                        <option value="" disabled selected>Pilih Kewarganegaraan</option>
                        @foreach ($warga as $key => $val )
                        <option value="{{ $key }}" {{ old('kewarganegaraan',$mhs->kewarganegaraan ?? '')==$key ? 'selected' : '' }}>
                            {{ $val }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Asal Sekolah (SMA/MA, SMK)</label>
                    <input type="text" class="form-control" name="asal_sekolah" value="{{ old('asal_sekolah',$mhs->asal_sekolah ?? '') }}" placeholder="Masukan Asal Sekolah" >
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Asal PTS (untuk mahasiswa transfer)</label>
                    <input type="text" class="form-control" name="pts_transfer" value="{{ old('pts_transfer',$mhs->pts_transfer ?? '') }}" placeholder="Masukan Asal PTS (untuk mahasiswa transfer)">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="">Alamat (Jl, RT, RW)</label>
                    <input type="text" class="form-control" name="alamat" value="{{ old('alamat',$mhs->alamat ?? '') }}"  placeholder="Masukan Alamat Jl, RT, RW">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="">Provinsi</label>
                    <select name="provinsi" id="provinsi" class="select2 form-control" >
                        <option selected disabled>Pilih Provinsi</option>
                        @foreach ($provinsi as $val)
                        <option value="{{ $val->id }}" 
                            {{ old('provinsi', $mhs->id_prov ?? '') == $val->id ? 'selected' : '' }}>
                            {{ $val->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="">Kabupaten</label>
                    <select name="kabupaten" id="kabupaten" class="select2 form-control" >
                        <option selected disabled>Pilih Kabupaten</option>
                    
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="">Kecamatan</label>
                    <select name="kecamatan" id="kecamatan" class="select2 form-control" >
                        <option selected disabled>Pilih Kecamatan</option>
                    
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="">Desa</label>
                    <select name="desa" id="desa" class="select2 form-control" >
                        <option selected disabled>Pilih Desa</option>
                    
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="">Kode Pos</label>
                    <input type="number" class="form-control" name="kode_pos" value="{{ old('kode_pos',$mhs->kode_pos ?? '') }}"  placeholder="Masukan Kode Pos">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="">No. Telepon/HP</label>
                    <input type="number" class="form-control" name="no_hp" value="{{ old('no_hp',$mhs->no_hp ?? '') }}" placeholder="Masukan No HP">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="">Pekerjaan (bila sudah bekerja)</label>
                    <input type="text" class="form-control" name="pekerjaan" value="{{ old('pekerjaan',$mhs->pekerjaan ?? '') }}"  placeholder="Masukan Pekerjaan">
                </div>
            </div>
        </div>
    </div>
</div>