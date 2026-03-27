@extends('layouts.main')

@section('css')
    <style>
        .select-group {
            display: flex;
            gap: 8px;
            align-items: stretch;
        }

        .select-group .select2-container {
            flex: 1;
        }

        .select-group .btn-add {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            padding: 0 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 45px;
        }

        .select-group .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .select-group .btn-add i {
            font-size: 16px;
        }

        .select-group .select2-container .select2-selection--single {
            height: 38px;
            border: 1px solid #ced4da;
        }

        .select-group .select2-container .select2-selection--single:focus-within {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

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

        .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #212529;
        }

        .btn-warning:hover {
            background-color: #e0a800;
            border-color: #d39e00;
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

        .edit-badge {
            background-color: #ffc107;
            color: #212529;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
            margin-left: 10px;
        }

        /* Edit mode styling */
        .edit-mode {
            background-color: #fff3cd !important;
            border-color: #ffc107 !important;
        }

        @media (max-width: 768px) {
            .form-control {
                font-size: 16px;
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

                        <form id="barangKeluarEditForm" method="POST"
                            action="{{ route('barangkeluar.update', $barangKeluar->id) }}">
                            @csrf
                            @method('PUT')

                            <!-- Header Information -->
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>No. Reff <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="no_reff" name="no_reff"
                                            placeholder="" value="{{ $barangKeluar->no_reff }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Tanggal <span class="text-danger">*</span></label>
                                        <div class='input-group'>
                                            <input type='text' class="form-control" id="tanggal" name="tanggal"
                                                value="{{ $barangKeluar->tanggal_keluar->format('Y-m-d') }}" required>
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
                                                        {{ $barangKeluar->jenis_stok_id == $jenisStok->id ? 'selected' : '' }}>
                                                        {{ $jenisStok->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="button" class="btn btn-add" data-toggle="modal"
                                                data-target="#modalJenisStok">
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
                                        <textarea class="form-control" id="catatan" name="catatan" rows="2" placeholder="Input Catatan...">{{ $barangKeluar->catatan }}</textarea>
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
                                                <option value="0">NON PPN</option>
                                                <option value="1">PPN</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Cari Nama / Kode Barang</label>
                                            <select class="form-control form-control-sm" id="barang_id">
                                                <option value=""></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <button type="button" class="btn btn-success btn-block" id="addItemBtn">
                                                <i class="la la-plus"></i> Tambah Item
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <button type="button" class="btn btn-secondary btn-block" id="cancelEditBtn"
                                                style="display: none;" title="Batal Edit">
                                                <i class="la la-chain"></i>
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
                                            <label>Harga Jual Rp.</label>
                                            <input type="text" class="form-control form-control-sm" id="harga_jual"
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
                                            <th width="20%">NAMA BARANG</th>
                                            <th width="8%">PPN</th>
                                            <th width="8%">SATUAN</th>
                                            <th width="7%">ISI</th>
                                            <th width="7%">JUMLAH</th>
                                            <th width="10%">HARGA RP.</th>
                                            <th width="10%">TOTAL RP.</th>
                                            <th width="10%">KETERANGAN</th>
                                            <th width="5%">AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody id="detailBarangBody">
                                        <tr>
                                            <td colspan="11" class="empty-state">
                                                Loading data...
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
                                <button type="submit" class="btn btn-warning">
                                    <i class="feather icon-save"></i> Update Data
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

    <!-- Modal Tambah Jenis Stok -->
    <div class="modal fade" id="modalJenisStok" tabindex="-1" role="dialog" aria-labelledby="modalJenisStokLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalJenisStokLabel">Tambah Jenis Stok Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formJenisStok">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="jenis_stok_nama">Nama Jenis Stok <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="jenis_stok_nama" name="nama" required>
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
        let isEditMode = true;

        // Constants
        const SWAL_COLORS = {
            primary: '#667eea',
            success: '#48bb78',
            error: '#f56565',
            warning: '#f6ad55'
        };

        $(document).ready(function() {
            console.log('Form edit loaded, initializing...');

            // Load existing data
            loadExistingData();

            // Setup event listeners
            setupEventListeners();

            // Initialize datepicker
            $('input[name="tanggal"]').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });

            console.log('Form edit initialization complete');
        });

        // Load existing data from server
        function loadExistingData() {
            console.log('Loading existing data...');

            // Load detail items from existing data
            @if ($barangKeluar->detailBarangKeluar->count() > 0)
                console.log('Found {{ $barangKeluar->detailBarangKeluar->count() }} existing items');

                @foreach ($barangKeluar->detailBarangKeluar as $detail)
                    const item{{ $loop->index }} = {
                        id: {{ $loop->iteration }},
                        barang_id: {{ $detail->barang_id }},
                        kode_barang: '{{ $detail->barang->kode ?? '' }}',
                        nama_barang: '{{ $detail->barang->nama_barang ?? '' }}',
                        ppn: {{ $detail->stok_ppn == 'PPN' ? '1' : '0' }},
                        satuan: {{ $detail->satuan_id }},
                        satuan_nama: '{{ $detail->satuan->nama ?? '' }}',
                        isi: {{ $detail->isi }},
                        harga_jual: {{ $detail->harga_jual }},
                        jumlah: {{ $detail->jumlah }},
                        total: {{ $detail->total }},
                        keterangan: '{{ $detail->keterangan ?? '' }}'
                    };

                    console.log('Adding item {{ $loop->index }}:', item{{ $loop->index }});
                    detailItems.push(item{{ $loop->index }});
                @endforeach

                console.log('Total items loaded:', detailItems.length);
                renderDetailTable();
            @else
                console.log('No existing items found');
            @endif
        }

        // Setup all event listeners
        function setupEventListeners() {
            initializeBarangSelect2();
            // Form interactions
            $('#barang_id').off('change').on('change', handleBarangChange);
            $('#addItemBtn').off('click').on('click', handleAddItem);
            $('#cancelEditBtn').off('click').on('click', handleCancelEdit);

            // Calculation listeners
            $('#harga_jual, #jumlah, #isi').off('input').on('input', calculateItemTotal);

            // Price formatting
            $('#harga_jual').off('input blur keypress').on('input', handlePriceInput);
            $('#harga_jual').on('blur', formatPriceOnBlur);
            $('#harga_jual').on('keypress', validateNumericInput);

            // Enter key handling
            $('#kode_barang, #barang_id, #isi, #harga_jual, #jumlah, #keterangan_detail').off('keypress').on('keypress',
                handleEnterKey);

            // Form submission
            $('#barangKeluarEditForm').off('submit').on('submit', handleFormSubmit);
        }

        function initializeBarangSelect2() {
            $('#barang_id').select2({
                placeholder: '-- Cari Nama / Kode Barang --',
                allowClear: true,
                width: '100%',
                dropdownParent: $('body'),
                ajax: {
                    url: '{{ route('barangkeluar.getBarangs') }}',
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

        // Handle barang selection change
        function handleBarangChange() {
            const selectedData = $('#barang_id').select2('data');
            if (selectedData && selectedData.length > 0 && selectedData[0].id) {
                const item = selectedData[0];
                const kode = item.kode || '';
                const namaBarang = item.nama_barang || '';
                $('#nama_barang_display').val(kode + ' / ' + namaBarang + ' / Jenis');
                populateSatuanOptions(item.satuans || []);
            } else {
                $('#nama_barang_display').val('');
                $('#satuan_select').html('<option value="">-- Pilih --</option>');
            }
            calculateItemTotal();
        }

        function populateSatuanOptions(satuans, selectedSatuanId = null) {
            const satuanSelect = $('#satuan_select');
            satuanSelect.html('<option value="">-- Pilih --</option>');

            satuans.forEach(function(satuan) {
                const isSelected = selectedSatuanId && String(selectedSatuanId) === String(satuan.id);
                const option = new Option(satuan.nama, satuan.id, isSelected, isSelected);
                satuanSelect.append(option);
            });

            if (selectedSatuanId) {
                satuanSelect.val(String(selectedSatuanId));
            }
        }

        // Handle kode barang search
        function handleKodeBarangSearch() {
            calculateItemTotal();
        }

        // Calculate item total
        function calculateItemTotal() {
            const hargaJual = getNumericValue($('#harga_jual').val()) || 0;
            const jumlah = parseFloat($('#jumlah').val()) || 0;
            const isi = parseFloat($('#isi').val()) || 1;

            const total = hargaJual * jumlah * isi;
            $('#total').val(formatRupiah(total));
        }

        // Handle add item
        function handleAddItem() {
            const headerData = getHeaderFormData();
            const itemData = getItemFormData();
            const editId = $('#addItemBtn').data('edit-id');

            // Validate header data first
            if (!validateHeaderData(headerData)) {
                showAlert('warning', 'Data Header Belum Lengkap', 'Silakan lengkapi informasi header terlebih dahulu!');
                return;
            }

            // Validate item data
            if (!validateItemData(itemData)) {
                return;
            }

            if (editId) {
                // Update existing item (edit mode)
                const itemIndex = detailItems.findIndex(item => item.id === editId);
                if (itemIndex !== -1) {
                    // Update item data
                    detailItems[itemIndex] = {
                        ...detailItems[itemIndex],
                        barang_id: itemData.barangId,
                        kode_barang: itemData.kodeBarang,
                        nama_barang: itemData.namaBarang,
                        ppn: itemData.ppn,
                        satuan: itemData.satuan,
                        satuan_nama: itemData.satuanNama,
                        isi: itemData.isi,
                        harga_jual: itemData.hargaJual,
                        jumlah: itemData.jumlah,
                        total: itemData.hargaJual * itemData.jumlah * itemData.isi,
                        keterangan: itemData.keterangan
                    };

                    showAlert('success', 'Item Diperbarui', 'Data item berhasil diperbarui', 1500);
                }

                // Reset edit mode
                resetEditMode();
            } else {
                // Check for duplicate items (add mode)
                const existingIndex = detailItems.findIndex(item => item.barang_id === itemData.barangId);

                if (existingIndex !== -1) {
                    // Update existing item
                    detailItems[existingIndex].jumlah += itemData.jumlah;
                    detailItems[existingIndex].total = detailItems[existingIndex].harga_jual * detailItems[existingIndex]
                        .jumlah * detailItems[existingIndex].isi;
                    showAlert('info', 'Item Diperbarui', 'Jumlah item yang sudah ada telah ditambahkan', 1500);
                } else {
                    // Add new item
                    itemCounter++;
                    const newItem = createItemObject(itemCounter, itemData);
                    detailItems.push(newItem);
                    showAlert('success', 'Item Ditambahkan', 'Item berhasil ditambahkan ke tabel', 1500);
                }
            }

            renderDetailTable();
            resetItemForm();
            calculateGrandTotal();
        }

        // Get header form data
        function getHeaderFormData() {
            return {
                noReff: $('#no_reff').val(),
                tanggal: $('#tanggal').val(),
                jenisStokId: $('#jenis_stok_id').val(),
                catatan: $('#catatan').val()
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
                ppn: $('#stok_ppn').val(),
                satuan: $('#satuan_select').val(),
                satuanNama: $('#satuan_select option:selected').text(),
                isi: parseFloat($('#isi').val()) || 1,
                hargaJual: getNumericValue($('#harga_jual').val()) || 0,
                jumlah: parseFloat($('#jumlah').val()) || 0,
                total: getNumericValue($('#total').val()) || 0,
                keterangan: $('#keterangan_detail').val()
            };
        }

        // Create item object
        function createItemObject(id, data) {
            const total = data.hargaJual * data.jumlah * data.isi;
            return {
                id: id,
                barang_id: data.barangId,
                kode_barang: data.kodeBarang,
                nama_barang: data.namaBarang,
                ppn: data.ppn,
                satuan: data.satuan,
                satuan_nama: data.satuanNama,
                isi: data.isi,
                harga_jual: data.hargaJual,
                jumlah: data.jumlah,
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
                    field: 'barangId',
                    value: data.barangId,
                    message: 'Pilih barang terlebih dahulu'
                },
                {
                    field: 'satuan',
                    value: data.satuan,
                    message: 'Pilih satuan terlebih dahulu'
                },
                {
                    field: 'jumlah',
                    value: data.jumlah,
                    message: 'Masukkan jumlah yang valid (> 0)',
                    condition: data.jumlah > 0
                },
                {
                    field: 'hargaJual',
                    value: data.hargaJual,
                    message: 'Masukkan harga jual yang valid (> 0)',
                    condition: data.hargaJual > 0
                }
            ];

            for (let validation of validations) {
                if (!validation.value || (validation.condition !== undefined && !validation.condition)) {
                    showAlert('warning', 'Data Tidak Lengkap', validation.message);
                    return false;
                }
            }
            return true;
        }

        // Reset item form
        function resetItemForm() {
            const barangSelect = $('#barang_id');
            barangSelect.select2('close');
            barangSelect.empty().append('<option value=""></option>');
            barangSelect.val('').trigger('change');
            $('#kode_barang, #nama_barang_display, #harga_jual, #keterangan_detail').val('');
            $('#satuan_select').html('<option value="">-- Pilih --</option>');
            $('#isi, #jumlah').val('');
            $('#total').val('');
            $('#stok_ppn').val('0');
            $('#barang_id').focus();
        }

        // Reset edit mode
        function resetEditMode() {
            $('#addItemBtn').html('<i class="feather icon-plus"></i> Tambah Item').removeClass('btn-warning').addClass(
                'btn-success');
            $('#addItemBtn').removeData('edit-id');
            $('#cancelEditBtn').hide();
            $('.search-section').removeClass('edit-mode');
        }

        // Handle cancel edit
        function handleCancelEdit() {
            Swal.fire({
                title: 'Konfirmasi Batal Edit',
                text: 'Yakin ingin membatalkan perubahan?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: SWAL_COLORS.warning,
                cancelButtonColor: '#a0aec0',
                confirmButtonText: '<i class="feather icon-check"></i> Ya, Batal',
                cancelButtonText: '<i class="feather icon-x"></i> Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    resetItemForm();
                    resetEditMode();
                    showAlert('info', 'Edit Dibatalkan', 'Mode edit telah dibatalkan', 1500);
                }
            });
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
                <td colspan="11" class="empty-state">
                    Data Kosong
                </td>
            </tr>
        `;
        }

        // Get table rows HTML
        function getTableRowsHTML() {
            console.log('Creating table rows for items:', detailItems);
            return detailItems.map((item, index) => {
                const ppnText = item.ppn == '1' ? 'PPN' : 'NON PPN';
                console.log('Processing item:', item);
                return `
                <tr>
                    <td class="text-center">${index + 1}</td>
                    <td>${item.kode_barang || ''}</td>
                    <td>${item.nama_barang || ''}</td>
                    <td>${ppnText}</td>
                    <td>${item.satuan_nama || ''}</td>
                    <td class="text-center">${item.isi || 0}</td>
                    <td class="text-center">${item.jumlah || 0}</td>
                    <td class="text-right">${formatRupiah(item.harga_jual || 0)}</td>
                    <td class="text-right">${formatRupiah(item.total || 0)}</td>
                    <td>${item.keterangan || '-'}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-warning mr-1" onclick="editItem(${item.id})" title="Edit">
                            <i class="la la-edit"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger" onclick="removeItem(${item.id})" title="Hapus">
                            <i class="la la-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            }).join('');
        }

        // Calculate grand total
        function calculateGrandTotal() {
            // Implementation for grand total if needed
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
                title: 'Konfirmasi Update',
                text: `Akan mengupdate data dengan ${detailItems.length} item barang`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: SWAL_COLORS.warning,
                cancelButtonColor: '#a0aec0',
                confirmButtonText: '<i class="ft-save"></i> Ya, Update!',
                cancelButtonText: '<i class="ft-x"></i> Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Siapkan data untuk dikirim
                    const formData = new FormData();
                    formData.append('_token', $('input[name="_token"]').val());
                    formData.append('_method', 'PUT');
                    formData.append('no_reff', $('#no_reff').val());
                    formData.append('tanggal', $('#tanggal').val());
                    formData.append('jenis_stok_id', $('#jenis_stok_id').val());
                    formData.append('catatan', $('#catatan').val() || '');
                    formData.append('detail_items', JSON.stringify(detailItems));

                    // Disable form and show loading
                    toggleFormState(true);

                    Swal.fire({
                        title: '💾 Mengupdate Data...',
                        html: `
                            <div class="d-flex justify-content-center align-items-center">
                                <div class="spinner-border text-warning mr-3" role="status">
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
                        url: $('#barangKeluarEditForm').attr('action'),
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
                                    title: '🎉 Berhasil Diupdate!',
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
                                    title: '❌ Gagal Mengupdate',
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
                            let errorMessage = 'Gagal mengupdate data ke server';
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

        // Disable form during submission
        function toggleFormState(disabled = true) {
            const formElements = $(
                '#barangKeluarEditForm input, #barangKeluarEditForm select, #barangKeluarEditForm button, #addItemBtn');
            formElements.prop('disabled', disabled);

            if (disabled) {
                $('#barangKeluarEditForm').addClass('form-disabled');
            } else {
                $('#barangKeluarEditForm').removeClass('form-disabled');
            }
        }

        // Show SweetAlert with consistent styling
        function showAlert(icon, title, text, timer = null) {
            const config = {
                icon: icon,
                title: title,
                text: text,
                confirmButtonColor: SWAL_COLORS[icon] || SWAL_COLORS.primary,
                confirmButtonText: 'OK'
            };

            if (timer) {
                config.timer = timer;
                config.showConfirmButton = false;
            }

            Swal.fire(config);
        }

        // Format currency
        function formatRupiah(angka) {
            return parseInt(angka).toLocaleString('id-ID');
        }

        // Format price input
        function formatPriceInput(value) {
            return value.replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
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

        // Global function to edit item
        window.editItem = function(itemId) {
            const item = detailItems.find(item => item.id === itemId);
            if (!item) {
                showAlert('error', 'Item Tidak Ditemukan', 'Item yang akan diedit tidak ditemukan');
                return;
            }

            // Set select2 value for barang
            if ($('#barang_id').find('option[value="' + item.barang_id + '"]').length === 0) {
                const newOption = new Option(item.nama_barang + ' - ' + item.kode_barang, item.barang_id, true, true);
                $('#barang_id').append(newOption);
            }
            $('#barang_id').val(item.barang_id).trigger('change.select2');

            populateSatuanOptions([{
                id: item.satuan,
                nama: item.satuan_nama || item.satuan
            }], item.satuan);

            // Manually trigger change handler to update display
            $('#nama_barang_display').val(item.kode_barang + ' / ' + item.nama_barang + ' / Jenis');
            $('#stok_ppn').val(item.ppn);
            $('#isi').val(item.isi);
            $('#harga_jual').val(formatRupiah(item.harga_jual));
            $('#jumlah').val(item.jumlah);
            $('#total').val(formatRupiah(item.total));
            $('#keterangan_detail').val(item.keterangan);

            // Set edit mode
            $('#addItemBtn').html('<i class="feather icon-save"></i> Update Item').removeClass('btn-success').addClass(
                'btn-warning');
            $('#addItemBtn').data('edit-id', itemId);
            $('#cancelEditBtn').show();

            // Add edit mode styling
            $('.search-section').addClass('edit-mode');

            // Show alert
            showAlert('info', 'Mode Edit', 'Silakan ubah data item dan klik "Update Item"', 3000);

            // Focus on first input
            $('#barang_id').focus();
        };

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
