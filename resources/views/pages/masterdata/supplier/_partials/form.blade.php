<div class="modal fade text-left" id="inlineForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33"
    aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <label class="modal-title text-text-bold-600" id="modalTitle">Tambah Supplier</label>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formSupplier">
                @csrf
                <input type="hidden" id="supplier_id" name="id">
                <div class="modal-body">
                    <label>Nama Supplier</label>
                    <div class="form-group">
                        <input type="text" placeholder="Nama Supplier" class="form-control" id="nama"
                            name="nama" required>
                    </div>

                    <label>Alamat</label>
                    <div class="form-group">
                        <input type="text" placeholder="Alamat" class="form-control" id="alamat" name="alamat"
                            required>
                    </div>

                    <label>Kota</label>
                    <div class="form-group">
                        <input type="text" placeholder="Kota" class="form-control" id="kota" name="kota"
                            required>
                    </div>

                    <label>No. Telepon</label>
                    <div class="form-group">
                        <input type="text" placeholder="No. Telepon" class="form-control" id="no_hp"
                            name="no_hp" required>
                    </div>

                    <label>Email</label>
                    <div class="form-group">
                        <input type="email" placeholder="Email" class="form-control" id="email" name="email"
                            required>
                    </div>

                    <label>Contact Person</label>
                    <div class="form-group">
                        <input type="text" placeholder="Contact Person" class="form-control" id="contact_person"
                            name="contact_person" required>
                    </div>

                    <label>No. CP</label>
                    <div class="form-group">
                        <input type="text" placeholder="No. CP" class="form-control" id="telepon_contact_person"
                            name="telepon_contact_person" required>
                    </div>

                    <label>Keterangan</label>
                    <div class="form-group">
                        <textarea placeholder="Keterangan" class="form-control" id="keterangan" name="keterangan" required></textarea>
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
