<div class="modal fade text-left" id="inlineForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <label class="modal-title text-text-bold-600" id="modalTitle">Tambah Bank</label>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formBank">
                @csrf
                <input type="hidden" id="bank_id" name="id">
                <div class="modal-body">
                    <label>Nama Bank</label>
                    <div class="form-group">
                        <input type="text" placeholder="Nama Bank" class="form-control" id="nama" name="nama" required>
                    </div>

                    <label>No. Rekening</label>
                    <div class="form-group">
                        <input type="text" placeholder="No. Rekening" class="form-control" id="no_rekening" name="no_rekening" required>
                    </div>

                    <label>Atas Nama</label>
                    <div class="form-group">
                        <input type="text" placeholder="Atas Nama" class="form-control" id="atas_nama" name="atas_nama" required>
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
