<div class="step" data-step="2">
    <div class="card-header">
        Data Orang Tua
    </div>
    <div class="card-body">
        {{-- @if(isset($mhs->id_ortu))
        <input type="hidden" name="id_ortu" value="{{ $mhs->id_ortu ?? '' }}">
        @endif --}}
        <input type="hidden" name="id_ortu" value="{{ $ortu->id ?? '' }}">
        <input type="hidden" name="id_mahasiswa" value="{{ $mhs->id ?? '' }}">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Nama Ayah</label>
                    <input type="text" name="nama_ayah" id="nama_ayah" class="form-control" value="{{ old('nama_ayah',$ortu->nama_ayah ?? '') }}" placeholder="Masukan Nama Ayah">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Nama Ibu</label>
                    <input type="text" name="nama_ibu" id="nama_ibu" class="form-control" value="{{ old('nama_ibu',$ortu->nama_ibu ?? '') }}" placeholder="Masukan Nama Ibu">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Tempat Lahir Ayah</label>
                    <input type="text" class="form-control" name="tempat_lahir_ayah" id="tempat_lahir_ayah" value="{{ old('tempat_lahir_ayah',$ortu->tempat_lahir_ayah ?? '') }}" placeholder="Masukan Tempat Lahir Ayah">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Tempat Lahir Ibu</label>
                    <input type="text" class="form-control" name="tempat_lahir_ibu" id="tempat_lahir_ibu" value="{{ old('tempat_lahir_ibu',$ortu->tempat_lahir_ibu ?? '') }}" placeholder="Masukan Tempat Lahir Ibu">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Tanggal Lahir Ayah</label>
                    <input type="text" id="date_ayah" class="form-control" name="tanggal_lahir_ayah" value="{{ old('tanggal_lahir_ayah',$ortu->tanggal_lahir_ayah ?? '') }}" placeholder="Masukan Tanggal Lahir Ayah" autocomplete="off">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Tanggal Lahir Ibu</label>
                    <input type="text" id="date_ibu" class="form-control" name="tanggal_lahir_ibu"  value="{{ old('tanggal_lahir_ibu',$ortu->tanggal_lahir_ibu ?? '') }}" placeholder="Masukan Tanggal Lahir Ibu" autocomplete="off">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>NIK Ayah</label>
                    <input type="number" 
                    class="form-control" 
                    name="nik_ayah" 
                    data-original-nik="{{ $ortu->nik_ayah ?? '' }}" 
                    value="{{ old('nik_ayah', $ortu->nik_ayah ?? '') }}" 
                    placeholder="Masukan NIK Ayah">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>NIK Ibu</label>
                   <input type="number" 
                    class="form-control" 
                    name="nik_ibu" 
                    data-original-nik="{{ $ortu->nik_ibu ?? '' }}" 
                    value="{{ old('nik_ibu', $ortu->nik_ibu ?? '') }}" 
                    placeholder="Masukan NIK Ibu">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Pendidikan Ayah</label>
                    <select name="pendidikan_ayah" id="pendidikan_ayah" class="form-control">
                        <option disabled selected>Pilih Pendidikan Ayah</option>
                        @foreach ($didik_ayah as $key => $val )
                        <option value="{{ $key }}" {{ old('pendidikan_ayah', $ortu->pendidikan_ayah ?? '')==$key ? 'selected' : '' }}>
                            {{ $val }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Pendidikan Ibu</label>
                    <select name="pendidikan_ibu" id="pendidikan_ibu" class="form-control">
                        <option disabled selected>Pilih Pendidikan Ibu</option>
                        @foreach ($didik_ibu as $key => $val )
                        <option value="{{ $key }}" {{ old('pendidikan_ibu', $ortu->pendidikan_ibu ?? '')==$key ? 'selected' : '' }}>
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
                    <label>Pekerjaan Ayah</label>
                    <select name="pekerjaan_ayah" id="pekerjaan_ayah" class="form-control">
                        <option disabled selected>Pilih Pekerjaan Ayah</option>
                        @foreach ($kerja_ayah as $key => $val )
                        <option value="{{ $key }}" {{ old('pekerjaan_ayah',$ortu->pekerjaan_ayah ?? '')==$key ? 'selected' : '' }}>
                            {{ $val }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Pekerjaan Ibu</label>
                    <select name="pekerjaan_ibu" id="pekerjaan_ibu" class="form-control">
                        <option disabled selected>Pilih Pekerjaan Ibu</option>
                        @foreach ($kerja_ibu as $key => $val )
                        <option value="{{ $key }}" {{ old('pekerjaan_ibu',$ortu->pekerjaan_ibu ?? '')==$key ? 'selected' : '' }}>
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
                    <label>Penghasilan Ayah</label>
                    <select name="penghasilan_ayah" id="penghasilan_ayah" class="form-control">
                        <option disabled selected>Pilih Penghasilan Ayah</option>
                        @foreach ($hasil_ayah as $key => $val )
                        <option value="{{ $key }}" {{ old('penghasilan_ayah',$ortu->penghasilan_ayah ?? '')==$key ? 'selected' : '' }}>
                            {{ $val }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Penghasilan Ibu</label>
                    <select name="penghasilan_ibu" id="penghasilan_ibu" class="form-control">
                        <option disabled selected>Pilih Penghasilan Ibu</option>
                        @foreach ($hasil_ibu as $key => $val )
                        <option value="{{ $key }}" {{ old('penghasilan_ibu',$ortu->penghasilan_ibu ?? '')==$key ? 'selected' : '' }}>
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
                    <label>No HP Ayah</label>
                    <input type="number" class="form-control" name="no_hp_ayah" id="no_hp_ayah" value="{{ old('no_hp_ayah',$ortu->no_hp_ayah ?? '') }}" placeholder="Masukan No HP Ayah">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>No HP Ibu</label>
                    <input type="number" class="form-control" name="no_hp_ibu" id="no_hp_ibu" value="{{ old('no_hp_ibu',$ortu->no_hp_ibu ?? '') }}" placeholder="Masukan No HP Ibu">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Alamat Orang Tua</label>
                    <input type="text" class="form-control" name="alamat_ortu" id="alamat_ortu" value="{{ old('no_hp_ibu',$ortu->alamat_ortu ?? '') }}"  placeholder="Masukan Alamat Jl, RT, RW">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Provinsi</label>
                    <select name="provinsi_ortu" id="provinsi_ortu" class="select2 form-control" >
                        <option selected disabled>Pilih Provinsi</option>
                        @foreach ($provinsiOrtuList as $val)
                        <option value="{{ $val->id }}" 
                            {{ old('provinsi_ortu', $ortu->id_prov ?? '') == $val->id ? 'selected' : '' }}>
                            {{ $val->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Kabupaten</label>
                    <select name="kabupaten_ortu" id="kabupaten_ortu" class="select2 form-control">
                        <option selected disabled>Pilih Kabupaten</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="">Kecamatan</label>
                    <select name="kecamatan_ortu" id="kecamatan_ortu" class="select2 form-control" >
                        <option selected disabled>Pilih Kecamatan</option>
                    
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="">Desa</label>
                    <select name="desa_ortu" id="desa_ortu" class="select2 form-control" >
                        <option selected disabled>Pilih Desa</option>
                    
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>