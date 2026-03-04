<div class="modal fade text-left" id="inlineForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <label class="modal-title text-text-bold-600" id="modalTitle">Tambah Merek</label>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formMerek">
                @csrf
                <input type="hidden" id="merek_id" name="id">
                <div class="modal-body">
                    <label>Nama Merek</label>
                    <div class="form-group">
                        <input type="text" placeholder="Nama Merek" class="form-control" id="nama" name="nama" required>
                    </div>

                    <label>Deskripsi </label>
                    <div class="form-group">
                        <textarea placeholder="Deskripsi" class="form-control" id="deskripsi" name="deskripsi"></textarea>
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
