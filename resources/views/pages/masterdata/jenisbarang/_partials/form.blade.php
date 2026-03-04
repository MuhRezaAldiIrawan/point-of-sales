<div class="modal fade text-left" id="inlineForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <label class="modal-title text-text-bold-600" id="modalTitle">Tambah Jenis Barang</label>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formJenisBarang">
                @csrf
                <input type="hidden" id="jenis_barang_id" name="id">
                <div class="modal-body">
                    <label>Jenis Barang</label>
                    <div class="form-group">
                        <input type="text" placeholder="Jenis Barang" class="form-control" id="nama" name="nama" required>
                    </div>

                    <label>Kode</label>
                    <div class="form-group">
                        <input type="text" placeholder="Kode" class="form-control" id="kode" name="kode">
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
