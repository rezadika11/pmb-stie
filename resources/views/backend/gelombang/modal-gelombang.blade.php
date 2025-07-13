<div class="modal fade" id="gelombangModal" tabindex="-1" role="dialog" aria-labelledby="gelombangModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="gelombangModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="gelombangForm">
                @csrf
                <div class="modal-body text-dark">
                    {{-- <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <div>
                            <i class="bi bi-exclamation-circle mr-2"></i>
                           <div class="text-white">Tanggal gelombang harus di antara <strong>{{ $gelombang->tanggal_mulai }}</strong> dan <strong>{{ $gelombang->tanggal_selesai }}</strong></div> 
                        </div>
                      </div> --}}
                      @if ($gelombang)
                            <div class="alert alert-danger text-dark" role="alert">
                                Tanggal gelombang harus di antara 
                                <strong>{{ $gelombang->tanggal_mulai }}</strong> 
                                dan 
                                <strong>{{ $gelombang->tanggal_selesai }}</strong>
                            </div>
                        @endif
                  
                      
                      <input 
                      type="hidden" 
                      name="id_tahun_akademik" 
                      id="id_tahun_akademik" 
                      value="{{ $tahunAjaran->id ?? '' }}">
                    <input type="hidden" name="id" id="gelombang_id">
                    <div class="form-group">
                        <label for="nama_gelombang">Nama Gelombang</label>
                        <input type="text" name="nama_gelombang" id="nama_gelombang" class="form-control" placeholder="Contoh: Gelombang 1">
                        <span class="text-danger text-sm error-text nama_gelombang_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="tanggal_mulai">Tanggal Mulai</label>
                        <input type="text" name="tanggal_mulai" id="tglMulai" class="form-control">
                        <span class="text-danger text-sm error-text tanggal_mulai_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="tanggal_selesai">Tanggal Selesai</label>
                        <input type="text" name="tanggal_selesai" id="tglSelesai" class="form-control">
                        <span class="text-danger text-sm error-text tanggal_selesai_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="biaya">Biaya</label>
                        <input type="number" name="biaya" id="biaya" class="form-control">
                        <span class="text-danger text-sm error-text biaya_error"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">Batal</button>
                    <button type="submit" id="btnSimpan" class="btn btn-sm btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>