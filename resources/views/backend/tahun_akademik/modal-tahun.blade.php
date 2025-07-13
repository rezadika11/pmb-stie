<div class="modal fade" id="modalTahunAkademik" tabindex="-1" aria-labelledby="modalTahunAkademik" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTahunAkademik">Ubah Tahun Akademik</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
          </button>
        </div>
        <div class="modal-body text-dark">
           <form id="formSimpan">
            @csrf
            <div class="form-group row">
                <label class="col-sm-4 col-form-label text-dark">Tahun Akademik<span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <select id="kode" name="kode" class="kode form-control" autofocus>
                        <option value="" selected disabled>Pilih Tahun Akademik</option>
                        @foreach($tahunAkademikOptions as $option)
                            <option value="{{ $option['key'] }}">{{ $option['value'] }}</option>
                        @endforeach
                    </select>
                    <div class="kode-error text-danger"></div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label text-dark">Tanggal Mulai<span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <input type="text" id="tglMulai" name="tanggal_mulai" class="tanggal_mulai form-control" placeholder="Masukan Tanggal Mulai">
                    <div class="tanggal_mulai-error text-danger"></div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label text-dark">Tanggal Selesai<span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <input type="text" id="tglSelesai" name="tanggal_selesai" class="tanggal_selesai form-control" placeholder="Masukan Tanggal Selesai">
                    <div class="tanggal_selesai-error text-danger"></div>
                </div>
            </div>
           </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-sm btn-primary" id="btnUbahTahun">Simpan</button>
        </div>
      </div>
    </div>
</div>