@extends('layouts.main')

@section('css')
    <style>
        .card {
            border: 1px solid #dee2e6;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .form-group label {
            font-weight: 500;
            margin-bottom: 0.3rem;
            font-size: 14px;
        }

        .form-control {
            border: 1px solid #ced4da;
            border-radius: 3px;
            padding: 8px 12px;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .text-danger {
            color: #dc3545 !important;
        }

        .section-header {
            background-color: #6c757d;
            color: white;
            padding: 8px 15px;
            margin: 20px 0 15px 0;
            border-radius: 3px;
            font-weight: 500;
        }

        .detail-section {
            background-color: #6c757d;
            color: white;
            padding: 8px 15px;
            margin: 20px 0 15px 0;
            border-radius: 3px;
            font-weight: 500;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            text-align: center;
            vertical-align: middle;
            padding: 12px 8px;
            font-size: 13px;
        }

        .table tbody td {
            vertical-align: middle;
            padding: 10px 8px;
            font-size: 13px;
        }

        .table-responsive {
            border: 1px solid #dee2e6;
            border-radius: 3px;
        }

        .btn {
            padding: 8px 16px;
            border-radius: 3px;
            font-size: 14px;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0069d9;
            border-color: #0062cc;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .empty-state {
            padding: 30px;
            text-align: center;
            color: #6c757d;
            font-style: italic;
        }

        /* Form disabled state */
        .form-disabled {
            pointer-events: none;
            opacity: 0.6;
        }

        .form-disabled input,
        .form-disabled select,
        .form-disabled button {
            cursor: not-allowed;
        }

        .search-section {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 3px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .input-group-sm .form-control {
            font-size: 13px;
            padding: 6px 10px;
        }

        .select-group {
            position: relative;
            display: flex;
            align-items: stretch;
        }

        .select-group .select2-container {
            flex: 1;
        }

        .select-group .btn-add {
            border: 1px solid #28a745;
            border-left: none;
            background: #28a745;
            color: white;
            padding: 0 12px;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0 3px 3px 0;
            min-width: 45px;
        }

        .select-group .btn-add:hover {
            background: #218838;
            border-color: #1e7e34;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
        }

        .select-group .btn-add:focus {
            box-shadow: none;
            outline: none;
        }

        .select-group .select2-container .select2-selection--single {
            border-radius: 3px 0 0 3px;
            border-right: none;
        }

        .select-group .select2-container .select2-selection--single:focus-within {
            border-right: none;
        }

        @media (max-width: 768px) {
            .form-control {
                padding: 10px 12px;
            }

            .table thead th,
            .table tbody td {
                padding: 8px 4px;
                font-size: 12px;
            }
        }
    </style>

    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/forms/selects/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Berhasil!</strong> {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Error!</strong> {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <form id="barangMasukForm" method="POST" action="{{ route('barangmasuk.store') }}">
                            @csrf <!-- Header Information -->
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>No. Reff <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="no_reff" name="no_reff"
                                            placeholder="" value="{{ old('no_reff') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Tanggal <span class="text-danger">*</span></label>
                                        <div class='input-group'>
                                            <input type='text' class="form-control" id="tanggal" name="tanggal"
                                                value="{{ old('tanggal', date('Y-m-d')) }}" required>
                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <span class="ft-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Jenis Stok <span class="text-danger">*</span></label>
                                        <div class="select-group">
                                            <select class="select2 form-control" id="jenis_stok_id" name="jenis_stok_id"
                                                required>
                                                <option value="">-- Pilih Jenis Stok --</option>
                                                @foreach ($jenisStoks as $jenisStok)
                                                    <option value="{{ $jenisStok->id }}"
                                                        {{ old('jenis_stok_id') == $jenisStok->id ? 'selected' : '' }}>
                                                        {{ $jenisStok->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="button" class="btn btn-add" data-toggle="modal"
                                                data-target="#modalJenisStok" title="Tambah Jenis Stok">
                                                <i class="ft-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Input Catatan...</label>
                                        <textarea class="form-control" id="catatan" name="catatan" rows="2" placeholder="Input Catatan...">{{ old('catatan') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Detail Barang Section -->
                            <div class="detail-section">
                                Detail Barang
                            </div>

                            <!-- Search and Input Section -->
                            <div class="search-section">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Stok PPN</label>
                                            <select class="form-control form-control-sm" id="stok_ppn" name="stok_ppn">
                                                <option value="0" {{ old('stok_ppn') == '0' ? 'selected' : '' }}>NON
                                                    PPN</option>
                                                <option value="1" {{ old('stok_ppn') == '1' ? 'selected' : '' }}>PPN
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Cari Nama / Kode Barang</label>
                                            <select class="form-control form-control-sm" id="barang_id">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <button type="button" class="btn btn-success btn-block" id="addItemBtn">
                                                <i class="feather icon-plus"></i> Simpan
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Kode / Nama Barang / Jenis</label>
                                            <input type="text" class="form-control form-control-sm"
                                                id="nama_barang_display" readonly placeholder="">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Satuan</label>
                                            <select class="select2 form-control form-control-sm" id="satuan_select">
                                                <option value="">-- Pilih --</option>
                                                @foreach ($satuans as $item)
                                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Isi</label>
                                            <input type="number" class="form-control form-control-sm" id="isi"
                                                value="0">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Harga Beli Rp.</label>
                                            <input type="text" class="form-control form-control-sm" id="harga_beli"
                                                placeholder="0">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Jumlah</label>
                                            <input type="number" class="form-control form-control-sm" id="jumlah"
                                                placeholder="0" value="0">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Total</label>
                                            <input type="text" class="form-control form-control-sm" id="total"
                                                readonly placeholder="0">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Keterangan</label>
                                            <input type="text" class="form-control form-control-sm"
                                                id="keterangan_detail" placeholder="">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Detail Table -->
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="detailBarangTable">
                                    <thead>
                                        <tr>
                                            <th width="5%">NO</th>
                                            <th width="10%">KODE</th>
                                            <th width="25%">NAMA BARANG</th>
                                            <th width="8%">PPN</th>
                                            <th width="8%">SATUAN</th>
                                            <th width="8%">ISI</th>
                                            <th width="8%">JUMLAH</th>
                                            <th width="10%">HARGA RP.</th>
                                            <th width="10%">TOTAL RP.</th>
                                            <th width="8%">KETERANGAN</th>
                                        </tr>
                                    </thead>
                                    <tbody id="detailBarangBody">
                                        <tr>
                                            <td colspan="10" class="empty-state">
                                                Data Kosong
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <input type="hidden" name="detail_items" id="detailItemsData">

                            <div class="form-group mt-3 text-right">
                                <a href="javascript:void(0)" onclick="window.history.back()"
                                    class="btn btn-secondary mr-2">
                                    <i class="feather icon-x"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="feather icon-check"></i>
                                    Simpan Data
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Jenis Stok -->
    <div class="modal fade" id="modalJenisStok" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #28a745; color: white;">
                    <h5 class="modal-title">
                        <i class="feather icon-package"></i> Tambah Jenis Stok Baru
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="formJenisStok">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Jenis Stok <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nama" id="jenis_stok_nama" required
                                placeholder="Contoh: Pembelian, Retur, Transfer">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="feather icon-x"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="feather icon-check"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    {{-- select2 --}}
    <script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}" type="text/javascript"></script>

    {{-- datepicker --}}
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        let detailItems = [];
        let itemCounter = 0;

        // Constants
        const SWAL_COLORS = {
            primary: '#667eea',
            success: '#48bb78',
            error: '#f56565',
            warning: '#f6ad55'
        };

        $(document).ready(function() {
            generateAutoRefNumber();
            setupEventListeners();

            $('input[name="tanggal"]').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });
        });

        function setupEventListeners() {
            initializeBarangSelect2();
            $('#barang_id').off('change').on('change', handleBarangChange);
            $('#addItemBtn').off('click').on('click', handleAddItem);

            $('#harga_beli, #jumlah, #isi').off('input').on('input', calculateItemTotal);

            $('#harga_beli').off('input blur keypress').on('input', handlePriceInput);
            $('#harga_beli').on('blur', formatPriceOnBlur);
            $('#harga_beli').on('keypress', validateNumericInput);

            $('#isi, #harga_beli, #jumlah, #keterangan_detail').off('keypress').on('keypress',
                handleEnterKey);

            $('#barangMasukForm').off('submit').on('submit', handleFormSubmit);
        }

        function initializeBarangSelect2() {
            $('#barang_id').select2({
                placeholder: '-- Cari Nama / Kode Barang --',
                allowClear: true,
                width: '100%',
                dropdownParent: $('body'),
                ajax: {
                    url: '{{ route('barangmasuk.getBarangs') }}',
                    dataType: 'json',
                    delay: 300,
                    data: function(params) {
                        return {
                            q: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function(data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.results,
                            pagination: {
                                more: data.pagination.more
                            }
                        };
                    },
                    cache: false
                },
                minimumInputLength: 1,
                language: {
                    noResults: function() {
                        return 'Barang tidak ditemukan';
                    },
                    searching: function() {
                        return 'Mencari...';
                    },
                    inputTooShort: function() {
                        return 'Ketik minimal 1 karakter untuk mencari';
                    }
                }
            });
        }



        function generateAutoRefNumber() {
            const today = new Date();
            const dateStr = today.getFullYear().toString().substr(-2) +
                String(today.getMonth() + 1).padStart(2, '0') +
                String(today.getDate()).padStart(2, '0');
            const timeStr = String(today.getHours()).padStart(2, '0') +
                String(today.getMinutes()).padStart(2, '0');
            $('#no_reff').val(`BM${dateStr}${timeStr}`);
        }

        function handleBarangChange() {
            const selectedData = $('#barang_id').select2('data');
            if (selectedData && selectedData.length > 0 && selectedData[0].id) {
                const item = selectedData[0];
                const kode = item.kode || '';
                const namaBarang = item.nama_barang || '';
                $('#nama_barang_display').val(kode + ' / ' + namaBarang + ' / Jenis');
            } else {
                $('#nama_barang_display').val('');
                $('#satuan_select').html('<option value="">-- Pilih --</option>');
            }
            calculateItemTotal();
        }

        // Calculate item total
        function calculateItemTotal() {
            const hargaBeli = getNumericValue($('#harga_beli').val()) || 0;
            const jumlah = parseFloat($('#jumlah').val()) || 0;
            const isi = parseFloat($('#isi').val()) || 1;

            const total = hargaBeli * jumlah * isi;
            $('#total').val(formatRupiah(total));
        }

        // Handle add item
        function handleAddItem() {
            const headerData = getHeaderFormData();
            const itemData = getItemFormData();

            // Validate header data first
            if (!validateHeaderData(headerData)) {
                showAlert('warning', 'Data Header Belum Lengkap', 'Silakan lengkapi informasi header terlebih dahulu!');
                return;
            }

            // Validate item data
            if (!validateItemData(itemData)) {
                return;
            }

            // Check for duplicate items
            const existingIndex = detailItems.findIndex(item => item.barang_id === itemData.barangId);

            if (existingIndex !== -1) {
                // Update existing item
                detailItems[existingIndex].jumlah += itemData.jumlah;
                detailItems[existingIndex].total = detailItems[existingIndex].harga_beli *
                    detailItems[existingIndex].jumlah *
                    detailItems[existingIndex].isi;
                showAlert('info', 'Item Diperbarui', 'Jumlah item yang sudah ada telah ditambahkan', 1500);
            } else {
                // Add new item
                itemCounter++;
                detailItems.push(createItemObject(itemCounter, itemData));
                showAlert('success', 'Berhasil!', 'Item berhasil ditambahkan ke tabel', 1500);
            }

            renderDetailTable();
            resetItemForm();
            calculateGrandTotal();
        }

        // Get header form data
        function getHeaderFormData() {
            return {
                noReff: $('#no_reff').val().trim(),
                tanggal: $('#tanggal').val(),
                jenisStokId: $('#jenis_stok_id').val(),
                stokPpn: $('#stok_ppn').val(),
                catatan: $('#catatan').val().trim()
            };
        }

        // Get item form data
        function getItemFormData() {
            const selectedData = $('#barang_id').select2('data');
            const barangItem = selectedData && selectedData.length > 0 ? selectedData[0] : null;
            return {
                barangId: $('#barang_id').val(),
                kodeBarang: barangItem ? (barangItem.kode || '') : '',
                namaBarang: barangItem ? (barangItem.nama_barang || '') : '',
                satuan: $('#satuan_select').val(),
                isi: parseFloat($('#isi').val()) || 0,
                hargaBeli: getNumericValue($('#harga_beli').val()) || 0,
                jumlah: parseFloat($('#jumlah').val()) || 0,
                ppn: $('#stok_ppn').val(),
                total: 0, // Will be calculated
                keterangan: $('#keterangan_detail').val().trim()
            };
        }

        // Create item object
        function createItemObject(id, data) {
            const total = data.hargaBeli * data.jumlah * data.isi;
            return {
                id: id,
                barang_id: data.barangId,
                kode_barang: data.kodeBarang,
                nama_barang: data.namaBarang,
                satuan: data.satuan,
                isi: data.isi,
                harga_beli: data.hargaBeli,
                jumlah: data.jumlah,
                ppn: data.ppn,
                total: total,
                keterangan: data.keterangan
            };
        }

        // Validate header data
        function validateHeaderData(data) {
            return data.noReff && data.tanggal && data.jenisStokId;
        }

        // Validate item data
        function validateItemData(data) {
            const validations = [{
                    condition: !data.barangId,
                    message: 'Pilih barang terlebih dahulu!',
                    focus: '#barang_id'
                },
                {
                    condition: data.jumlah <= 0,
                    message: 'Jumlah harus lebih dari 0!',
                    focus: '#jumlah'
                },
                {
                    condition: data.hargaBeli <= 0,
                    message: 'Harga beli harus lebih dari 0!',
                    focus: '#harga_beli'
                },
                {
                    condition: data.isi <= 0,
                    message: 'Isi harus lebih dari 0!',
                    focus: '#isi'
                }
            ];

            for (let validation of validations) {
                if (validation.condition) {
                    showAlert('warning', 'Data Tidak Valid', validation.message);
                    if (validation.focus) $(validation.focus).focus();
                    return false;
                }
            }
            return true;
        }

        // Reset item form
        function resetItemForm() {
            $('#barang_id').val(null).trigger('change');
            $('#nama_barang_display, #harga_beli, #keterangan_detail').val('');
            $('#satuan_select').html('<option value="">-- Pilih --</option>');
            $('#isi, #jumlah').val('0');
            $('#total').val('');
            $('#barang_id').focus();
        }

        // Render detail table
        function renderDetailTable() {
            const tbody = $('#detailBarangBody');

            tbody.fadeOut(200, function() {
                if (detailItems.length === 0) {
                    tbody.html(getEmptyStateHTML());
                } else {
                    tbody.html(getTableRowsHTML());
                }
                tbody.fadeIn(200);
                updateHiddenInput();
            });
        }

        // Get empty state HTML
        function getEmptyStateHTML() {
            return `
            <tr>
                <td colspan="10" class="empty-state">
                    Data Kosong
                </td>
            </tr>
        `;
        }

        // Get table rows HTML
        function getTableRowsHTML() {
            return detailItems.map((item, index) => {
                const ppnText = item.ppn == '1' ? 'PPN' : 'NON PPN';
                return `
                <tr>
                    <td class="text-center">${index + 1}</td>
                    <td>${item.kode_barang}</td>
                    <td>${item.nama_barang}</td>
                    <td>${ppnText}</td>
                    <td>${item.satuan}</td>
                    <td class="text-center">${item.isi}</td>
                    <td class="text-center">${item.jumlah}</td>
                    <td class="text-right">${formatRupiah(item.harga_beli)}</td>
                    <td class="text-right">${formatRupiah(item.total)}</td>
                    <td>${item.keterangan || '-'}</td>
                </tr>
            `;
            }).join('');
        }

        // Calculate grand total
        function calculateGrandTotal() {
            const grandTotal = detailItems.reduce((sum, item) => sum + item.total, 0);
            // Update grand total display if element exists
            if ($('#grandTotal').length) {
                $('#grandTotal').text('Rp ' + formatRupiah(grandTotal));
            }
        }

        // Update hidden input
        function updateHiddenInput() {
            $('#detailItemsData').val(JSON.stringify(detailItems));
        }

        // Handle enter key
        function handleEnterKey(e) {
            if (e.which === 13) {
                e.preventDefault();
                handleAddItem();
            }
        }

        // Handle form submit
        function handleFormSubmit(e) {
            e.preventDefault();

            if (!validateFormSubmit()) return false;

            // Validasi ada detail items
            if (detailItems.length === 0) {
                showAlert('warning', 'Data Tidak Lengkap', 'Mohon tambahkan minimal 1 item barang!');
                return false;
            }

            Swal.fire({
                title: 'Konfirmasi Simpan',
                text: `Akan menyimpan data dengan ${detailItems.length} item barang`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: SWAL_COLORS.primary,
                cancelButtonColor: '#a0aec0',
                confirmButtonText: '<i class="ft-save"></i> Ya, Simpan!',
                cancelButtonText: '<i class="ft-x"></i> Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Siapkan data untuk dikirim
                    const formData = new FormData();
                    formData.append('_token', $('input[name="_token"]').val());
                    formData.append('no_reff', $('#no_reff').val());
                    formData.append('tanggal', $('#tanggal').val());
                    formData.append('jenis_stok_id', $('#jenis_stok_id').val());
                    formData.append('catatan', $('#catatan').val() || '');
                    formData.append('detail_items', JSON.stringify(detailItems));

                    // Disable form and show loading
                    toggleFormState(true);

                    Swal.fire({
                        title: '💾 Menyimpan Data...',
                        html: `
                            <div class="d-flex justify-content-center align-items-center">
                                <div class="spinner-border text-primary mr-3" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <span>Mohon tunggu sebentar...</span>
                            </div>
                        `,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Kirim data via AJAX
                    $.ajax({
                        url: $('#barangMasukForm').attr('action'),
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        success: function(response) {
                            console.log('Success response:', response);

                            if (response.success) {
                                // Success SweetAlert
                                Swal.fire({
                                    icon: 'success',
                                    title: '🎉 Berhasil Disimpan!',
                                    html: `
                                        <div class="text-left">
                                            <p><strong>${response.message}</strong></p>
                                            ${response.data ? `
                                                        <hr>
                                                        <small class="text-muted">
                                                            📋 No. Referensi: <strong>${response.data.no_reff}</strong><br>
                                                            📦 Total Item: <strong>${response.data.total_items}</strong>
                                                        </small>
                                                    ` : ''}
                                        </div>
                                    `,
                                    confirmButtonColor: SWAL_COLORS.success,
                                    confirmButtonText: '✅ OK',
                                    allowOutsideClick: false,
                                    customClass: {
                                        popup: 'animated bounceIn'
                                    }
                                }).then(() => {
                                    // Re-enable form
                                    toggleFormState(false);

                                    // Bersihkan form data
                                    resetForm();

                                    // Redirect ke halaman index
                                    if (response.redirect) {
                                        window.location.href = response.redirect;
                                    }
                                });
                            } else {
                                // Re-enable form
                                toggleFormState(false);

                                // Error dari server tapi response sukses
                                Swal.fire({
                                    icon: 'error',
                                    title: '❌ Gagal Menyimpan',
                                    text: response.message ||
                                        'Terjadi kesalahan tidak diketahui',
                                    confirmButtonColor: SWAL_COLORS.error,
                                    confirmButtonText: '🔄 Coba Lagi'
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error:', xhr.responseJSON);

                            let errorTitle = '❌ Terjadi Kesalahan';
                            let errorMessage = 'Gagal menyimpan data ke server';
                            let errorIcon = 'error';

                            if (xhr.responseJSON) {
                                const response = xhr.responseJSON;

                                // Handle different error types
                                if (response.error_type === 'validation') {
                                    errorTitle = '⚠️ Data Tidak Valid';
                                    errorIcon = 'warning';

                                    if (response.errors) {
                                        // Format validation errors
                                        const errorList = Object.entries(response.errors)
                                            .map(([field, messages]) => {
                                                return `<strong>${field}:</strong> ${messages.join(', ')}`;
                                            })
                                            .join('<br>');
                                        errorMessage = `<div class="text-left">${errorList}</div>`;
                                    } else {
                                        errorMessage = response.message;
                                    }
                                } else if (response.error_type === 'server_error') {
                                    errorTitle = '🔥 Error Server';
                                    errorMessage = response.message || 'Terjadi kesalahan pada server';
                                } else {
                                    errorMessage = response.message || errorMessage;
                                }
                            } else if (xhr.status === 0) {
                                errorTitle = '🌐 Tidak Ada Koneksi';
                                errorMessage = 'Periksa koneksi internet Anda';
                            } else if (xhr.status === 500) {
                                errorTitle = '🔥 Error Server';
                                errorMessage = 'Terjadi kesalahan internal server';
                            } else if (xhr.status === 404) {
                                errorTitle = '🔍 Halaman Tidak Ditemukan';
                                errorMessage = 'Endpoint tidak ditemukan';
                            }

                            // Re-enable form
                            toggleFormState(false);

                            Swal.fire({
                                icon: errorIcon,
                                title: errorTitle,
                                html: errorMessage,
                                confirmButtonColor: SWAL_COLORS.error,
                                confirmButtonText: '🔄 Coba Lagi',
                                customClass: {
                                    popup: 'animated shake'
                                },
                                footer: xhr.status ?
                                    `<small class="text-muted">Error Code: ${xhr.status}</small>` :
                                    ''
                            });
                        }
                    });
                }
            });
        }



        // Validate form before submit
        function validateFormSubmit() {
            const headerData = getHeaderFormData();

            if (!validateHeaderData(headerData)) {
                showAlert('error', 'Data Tidak Lengkap', 'Lengkapi semua field yang wajib diisi!');
                return false;
            }

            if (detailItems.length === 0) {
                showAlert('error', 'Detail Kosong', 'Tambahkan minimal satu item barang!');
                return false;
            }

            return true;
        }



        // Reset entire form
        function resetForm() {
            $('#barangMasukForm')[0].reset();
            $('#barang_id').val(null).trigger('change');
            detailItems = [];
            itemCounter = 0;
            renderDetailTable();
            calculateGrandTotal();
            generateAutoRefNumber();
            $('#no_reff').focus();
        }

        // Disable form during submission
        function toggleFormState(disabled = true) {
            const formElements = $('#barangMasukForm input, #barangMasukForm select, #barangMasukForm button, #addItemBtn');
            formElements.prop('disabled', disabled);

            if (disabled) {
                $('#barangMasukForm').addClass('form-disabled');
            } else {
                $('#barangMasukForm').removeClass('form-disabled');
            }
        }

        // Show SweetAlert with consistent styling
        function showAlert(icon, title, text, timer = null) {
            const config = {
                icon: icon,
                title: title,
                text: text,
                confirmButtonColor: SWAL_COLORS.primary
            };

            if (timer) {
                config.timer = timer;
                config.showConfirmButton = false;
            }

            Swal.fire(config);
        }

        // Format currency
        function formatRupiah(angka) {
            return parseFloat(angka).toLocaleString('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 2
            });
        }

        // Format price input
        function formatPriceInput(value) {
            const cleanValue = value.toString().replace(/\D/g, '');
            if (cleanValue === '') return '';
            return parseInt(cleanValue).toLocaleString('id-ID');
        }

        // Handle price input formatting
        function handlePriceInput(e) {
            const input = $(this);
            const oldValue = input.val();
            const newValue = formatPriceInput(oldValue);

            if (oldValue !== newValue) {
                input.val(newValue);
            }

            calculateItemTotal();
        }

        // Format price on blur
        function formatPriceOnBlur(e) {
            const input = $(this);
            const value = input.val();
            if (value) {
                input.val(formatPriceInput(value));
            }
        }

        // Validate numeric input
        function validateNumericInput(e) {
            const charCode = e.which || e.keyCode;

            // Allow: backspace, delete, tab, escape, enter
            if ([8, 9, 27, 13, 46].indexOf(charCode) !== -1 ||
                // Allow: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
                (charCode === 65 && e.ctrlKey) ||
                (charCode === 67 && e.ctrlKey) ||
                (charCode === 86 && e.ctrlKey) ||
                (charCode === 88 && e.ctrlKey)) {
                return;
            }

            // Allow only numbers (0-9)
            if (charCode < 48 || charCode > 57) {
                e.preventDefault();
            }
        }

        // Get numeric value from formatted price
        function getNumericValue(formattedValue) {
            if (!formattedValue) return 0;
            return parseFloat(formattedValue.toString().replace(/\./g, '')) || 0;
        }

        // Global function to remove item
        window.removeItem = function(itemId) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: 'Yakin ingin menghapus item ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: SWAL_COLORS.error,
                cancelButtonColor: '#a0aec0',
                confirmButtonText: '<i class="ft-trash-2"></i> Ya, Hapus!',
                cancelButtonText: '<i class="ft-x"></i> Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    detailItems = detailItems.filter(item => item.id !== itemId);
                    renderDetailTable();
                    calculateGrandTotal();
                    showAlert('success', 'Terhapus!', 'Item telah dihapus dari tabel', 1500);
                }
            });
        };

        // Global function to reset form (for external use)
        window.resetBarangMasukForm = function() {
            Swal.fire({
                title: 'Konfirmasi Reset',
                text: 'Yakin ingin mereset semua data form?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: SWAL_COLORS.error,
                cancelButtonColor: '#a0aec0',
                confirmButtonText: '<i class="ft-refresh-cw"></i> Ya, Reset!',
                cancelButtonText: '<i class="ft-x"></i> Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    resetForm();
                    showAlert('success', 'Berhasil!', 'Form telah direset', 1500);
                }
            });
        };

        // Handle jenis stok form submit
        $('#formJenisStok').on('submit', function(e) {
            e.preventDefault();

            const formData = {
                nama: $('#jenis_stok_nama').val()
            };

            $.ajax({
                url: '{{ route('jenisstok.store') }}',
                type: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    Swal.fire({
                        title: 'Menyimpan...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function(response) {
                    Swal.close();
                    $('#formJenisStok')[0].reset();

                    const newOption = new Option(response.data.nama, response.data.id, true, true);
                    $('#jenis_stok_id').append(newOption).trigger('change');

                    $('#modalJenisStok').fadeOut(300, function() {
                        $(this).removeClass('show');
                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
                    });

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Jenis stok berhasil ditambahkan',
                        timer: 1500,
                        showConfirmButton: false
                    });
                },
                error: function(xhr) {
                    Swal.close();

                    let errorMessage = 'Terjadi kesalahan';

                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        const errors = Object.values(xhr.responseJSON.errors).flat();
                        errorMessage = errors.join('<br>');
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        html: errorMessage
                    });
                }
            });
        });

        // Reset jenis stok form when modal is closed
        $(document).on('click', '[data-dismiss="modal"]', function() {
            if ($(this).closest('#modalJenisStok').length) {
                $('#formJenisStok')[0].reset();
                $('#modalJenisStok').fadeOut(300, function() {
                    $(this).removeClass('show');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                });
            }
        });
    </script>
@endsection
