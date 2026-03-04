<div class="modal fade text-left" id="inlineForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <label class="modal-title text-text-bold-600" id="modalTitle">Tambah Pelanggan</label>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formPelanggan">
                @csrf
                <input type="hidden" id="pelanggan_id" name="id">
                <div class="modal-body">
                    <label>Nama Pelanggan</label>
                    <div class="form-group">
                        <input type="text" placeholder="Nama Pelanggan" class="form-control" id="nama" name="nama" required>
                    </div>

                    <label>Alamat</label>
                    <div class="form-group">
                        <textarea placeholder="Alamat" class="form-control" id="alamat" name="alamat"></textarea>
                    </div>


                    <label>No. HP</label>
                    <div class="form-group">
                        <input type="text" placeholder="No. HP" class="form-control" id="no_hp" name="no_hp">
                    </div>

                    <label>Kota</label>
                    <div class="form-group">
                        <input type="text" placeholder="Kota" class="form-control" id="kota" name="kota">
                    </div>

                    <label>Status Bayar</label>
                    <div class="form-group">
                        <select class="form-control" id="status_bayar" name="status_bayar">
                            <option value="Tunai">Tunai</option>
                            <option value="Kredit">Kredit</option>
                        </select>
                    </div>

                    <label>Limit Kredit</label>
                    <div class="form-group">
                        <input type="number" placeholder="Limit Kredit" class="form-control" id="batas_kredit" name="batas_kredit" value="0">
                    </div>


                    <label>Keterangan</label>
                    <div class="form-group">
                        <textarea placeholder="Keterangan" class="form-control" id="keterangan" name="keterangan"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
