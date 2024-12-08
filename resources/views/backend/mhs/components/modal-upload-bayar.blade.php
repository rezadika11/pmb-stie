    <!-- Modal Konfirmasi -->
    <div class="modal fade" id="modalBayar" tabindex="-1" aria-labelledby="modalBayar" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalBayar">Upload Bukti Pembayaran</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </div>
                <div class="modal-body text-dark">
                <form enctype="multipart/form-data">
                    <label for="">Upload</label>
                    <input type="file" class="form-control" name="bukti_pembayaran" id="bukti_pembayaran" accept=".jpg,.jpeg,.png,.pdf">
                </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-sm btn-primary" id="btnSimpanBuktiBayar">Simpan</button>
                </div>
            </div>
        </div>
    </div>