@extends('layouts.main')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/forms/selects/select2.min.css') }}">
    <style>
        .card-body {
            padding: 1.5rem;
        }

        .form-group label {
            font-weight: 600;
            color: #2c2c2c;
            margin-bottom: 0.5rem;
        }

        .product-search-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            color: white;
        }

        .product-search-card label {
            color: white;
            font-weight: 600;
        }

        .items-table-wrapper {
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .items-table {
            width: 100%;
            margin-bottom: 0;
        }

        .items-table thead th {
            position: sticky;
            top: 0;
            background: royalblue;
            color: white;
            font-weight: 600;
            padding: 1rem 0.75rem;
            border: none;
            z-index: 10;
            white-space: nowrap;
        }

        .items-table tbody td {
            padding: 0.75rem;
            vertical-align: middle;
            background: white;
        }

        .items-table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .items-table input.form-control {
            padding: 0.375rem 0.5rem;
            font-size: 0.875rem;
        }

        .btn-remove-item {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            border: none;
            color: white;
            padding: 0.375rem 0.75rem;
            border-radius: 4px;
            font-size: 0.875rem;
            transition: all 0.3s;
        }

        .btn-remove-item:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(245, 87, 108, 0.4);
        }

        .summary-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1.5rem;
            margin-top: 1rem;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #dee2e6;
        }

        .summary-row.total-row {
            border-top: 2px solid #667eea;
            border-bottom: none;
            padding-top: 1rem;
            margin-top: 0.5rem;
            font-size: 1.3rem;
            font-weight: 700;
            color: #667eea;
        }

        .summary-label {
            font-weight: 600;
        }

        .summary-value {
            font-weight: 700;
            text-align: right;
        }

        .btn-add-item {
            background: white;
            border: 2px solid white;
            color: #667eea;
            padding: 0.5rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-add-item:hover {
            background: rgba(255, 255, 255, 0.9);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-save {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            border: none;
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 50px;
            font-weight: 700;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(56, 239, 125, 0.4);
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(56, 239, 125, 0.6);
        }

        .btn-cancel {
            background: linear-gradient(135deg, #757f9a 0%, #d7dde8 100%);
            border: none;
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 50px;
            font-weight: 700;
            transition: all 0.3s;
        }

        .btn-cancel:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(117, 127, 154, 0.4);
        }

        .input-group-text {
            background: #667eea;
            color: white;
            border: 1px solid #667eea;
            font-weight: 600;
        }

        .form-control:focus,
        .select2-container--default .select2-selection--single:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .badge-info-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .payment-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 8px;
            padding: 1.5rem;
            margin-top: 1rem;
        }

        .payment-section label {
            color: white;
            font-weight: 600;
        }

        .payment-section .form-control {
            border: 2px solid white;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Penjualan - {{ $penjualan->no_invoice }}</h4>
                </div>
                <div class="card-body">
                    <form id="formPenjualan">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>No. Invoice</label>
                                    <input type="text" class="form-control" id="no_invoice" readonly
                                        value="{{ $penjualan->no_invoice }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Tanggal <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="tanggal_penjualan"
                                        id="tanggal_penjualan" value="{{ $penjualan->tanggal_penjualan->format('Y-m-d') }}" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Pelanggan <span class="text-danger">*</span></label>
                                    <select class="form-control select2" name="pelanggan_id" id="pelanggan_id" required>
                                        <option value="{{ $penjualan->pelanggan_id }}" selected>{{ $penjualan->pelanggan->nama }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>PPN <span class="text-danger">*</span></label>
                                    <select class="form-control" name="ppn" id="ppn" required>
                                        <option value="Non PPN" {{ $penjualan->ppn == 'Non PPN' ? 'selected' : '' }}>Non PPN</option>
                                        <option value="PPN" {{ $penjualan->ppn == 'PPN' ? 'selected' : '' }}>PPN</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Pembayaran <span class="text-danger">*</span></label>
                                    <select class="form-control" name="payment_method" id="payment_method" required>
                                        <option value="Cash" {{ $penjualan->payment_method == 'Cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="Credit" {{ $penjualan->payment_method == 'Credit' ? 'selected' : '' }}>Credit</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row" id="jatuh_tempo_row" style="{{ $penjualan->payment_method == 'Credit' ? '' : 'display:none;' }}">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Tanggal Jatuh Tempo <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="jatuh_tempo" id="jatuh_tempo"
                                        value="{{ $penjualan->jatuh_tempo ? $penjualan->jatuh_tempo->format('Y-m-d') : '' }}">
                                </div>
                            </div>
                        </div>

                        <div class="row" id="customer-info" style="display:{{ $penjualan->pelanggan->alamat || $penjualan->pelanggan->no_hp ? 'block' : 'none' }};">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <strong>Info Pelanggan:</strong>
                                    <span id="customer-alamat">{{ $penjualan->pelanggan->alamat ?? '-' }}</span> |
                                    <strong>Telp:</strong> <span id="customer-telepon">{{ $penjualan->pelanggan->no_hp ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Detail Item Penjualan</h4>
                </div>
                <div class="card-body">
                    <div class="product-search-card">
                        <div class="row align-items-end">
                            <div class="col-md-10">
                                <div class="form-group mb-0">
                                    <label>Cari & Tambah Produk</label>
                                    <select class="form-control select2" id="search_barang" style="width: 100%;">
                                        <option value="">Ketik nama atau kode barang...</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-add-item btn-block" id="btnAddItem">
                                    <i class="ft-plus-circle"></i> Tambah
                                </button>
                            </div>
                        </div>
                    </div>

                    <div id="items-container">
                        <!-- Items akan di-load via JavaScript -->
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Biaya Kirim</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="text" class="form-control" name="biaya_kirim" id="biaya_kirim"
                                                value="{{ number_format($penjualan->biaya_kirim, 0, ',', '.') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Biaya Lain</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="text" class="form-control" name="biaya_lain" id="biaya_lain"
                                                value="{{ number_format($penjualan->biaya_lain, 0, ',', '.') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Diskon Total (%)</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="diskon_total"
                                                id="diskon_total" value="{{ $penjualan->diskon }}" min="0" max="100"
                                                step="0.01">
                                            <div class="input-group-append">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="summary-section">
                                <h5 class="mb-3"><i class="ft-file-text"></i> Ringkasan Pembayaran</h5>
                                <div class="summary-row">
                                    <span class="summary-label">Subtotal:</span>
                                    <span class="summary-value" id="display_subtotal">Rp 0</span>
                                </div>
                                <div class="summary-row">
                                    <span class="summary-label">Diskon:</span>
                                    <span class="summary-value" id="display_diskon">Rp 0</span>
                                </div>
                                <div class="summary-row">
                                    <span class="summary-label">PPN ({{ $ppnRate ?? 11 }}%):</span>
                                    <span class="summary-value" id="display_ppn">Rp 0</span>
                                </div>
                                <div class="summary-row">
                                    <span class="summary-label">Biaya Kirim:</span>
                                    <span class="summary-value" id="display_biaya_kirim">Rp 0</span>
                                </div>
                                <div class="summary-row">
                                    <span class="summary-label">Biaya Lain:</span>
                                    <span class="summary-value" id="display_biaya_lain">Rp 0</span>
                                </div>
                                <div class="summary-row total-row">
                                    <span class="summary-label">GRAND TOTAL:</span>
                                    <span class="summary-value" id="display_grand_total">Rp 0</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="payment-section">
                                <h5 class="mb-3"><i class="ft-dollar-sign"></i> Pembayaran</h5>
                                <div class="form-group">
                                    <label>Jumlah Bayar <span class="text-warning">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white text-dark">Rp</span>
                                        </div>
                                        <input type="text" class="form-control" name="bayar" id="bayar"
                                            value="{{ number_format($penjualan->bayar, 0, ',', '.') }}" required>
                                    </div>
                                </div>

                                <div class="summary-row"
                                    style="border-bottom: 1px solid rgba(255,255,255,0.3); padding: 0.5rem 0;">
                                    <span class="summary-label">Kembalian:</span>
                                    <span class="summary-value" id="display_kembalian">Rp 0</span>
                                </div>
                                <div class="summary-row"
                                    style="border-bottom: 1px solid rgba(255,255,255,0.3); padding: 0.5rem 0;">
                                    <span class="summary-label">Sisa:</span>
                                    <span class="summary-value text-warning" id="display_sisa">Rp 0</span>
                                </div>

                                <div class="mt-4">
                                    <a href="{{ route('penjualan.print', $penjualan->id) }}" target="_blank" class="btn btn-info btn-block btn-lg mb-2">
                                        <i class="ft-printer"></i> CETAK STRUK
                                    </a>
                                    <button type="button" class="btn btn-save btn-block btn-lg" id="btnSave">
                                        <i class="ft-save"></i> UPDATE TRANSAKSI
                                    </button>
                                    <a href="{{ route('penjualan.index') }}" class="btn btn-cancel btn-block btn-lg mt-2">
                                        <i class="ft-x-circle"></i> BATAL
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            let itemCounter = 0;
            let items = [];
            const ppnRate = {{ $ppnRate ?? 11 }} / 100; // PPN rate from settings

            // Load existing items
            const existingItems = @json($penjualan->detailPenjualans);

            $('.select2').select2({
                placeholder: 'Pilih...',
                allowClear: true
            });

            $('#search_barang').select2({
                placeholder: 'Ketik nama atau kode barang...',
                allowClear: true,
                ajax: {
                    url: '{{ route('api.barang.search') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.results.map(function(item) {
                                return {
                                    id: item.id,
                                    text: item.text,
                                    stok: item.stok,
                                    harga_jual: item.harga_jual,
                                    satuan: item.satuan,
                                    nama_barang: item.nama_barang
                                };
                            })
                        };
                    }
                }
            });

            $('#pelanggan_id').select2({
                placeholder: 'Pilih Pelanggan',
                allowClear: true,
                ajax: {
                    url: '{{ route('api.pelanggan.search') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.results
                        };
                    }
                }
            });

            $('#pelanggan_id').on('select2:select', function(e) {
                const data = e.params.data;
                if (data.alamat || data.telepon) {
                    $('#customer-alamat').text(data.alamat || '-');
                    $('#customer-telepon').text(data.telepon || '-');
                    $('#customer-info').slideDown();
                }
            });

            $('#payment_method').on('change', function() {
                if ($(this).val() === 'Credit') {
                    $('#jatuh_tempo_row').slideDown();
                    $('#jatuh_tempo').prop('required', true);
                } else {
                    $('#jatuh_tempo_row').slideUp();
                    $('#jatuh_tempo').prop('required', false);
                }
            });

            // Load existing items
            existingItems.forEach(function(detail) {
                itemCounter++;
                const detailBarang = detail.barang.detail_barang[0];
                const stokTersedia = detail.stok_tersedia || 0;
                const satuan = (detailBarang && detailBarang.satuan) ? detailBarang.satuan.nama_satuan : '-';
                const barang = {
                    id: detail.barang_id,
                    nama_barang: detail.barang.nama_barang,
                    stok: stokTersedia,
                    harga_jual: detail.harga_satuan,
                    satuan: satuan
                };

                const itemData = {
                    counter: itemCounter,
                    barang_id: barang.id,
                    nama_barang: barang.nama_barang,
                    stok: barang.stok,
                    harga_satuan: barang.harga_jual,
                    satuan: barang.satuan,
                    jumlah: detail.jumlah,
                    bonus: detail.bonus || 0,
                    diskon: detail.diskon_item || 0
                };

                items.push(itemData);
            });

            // Render table
            if (items.length > 0) {
                renderItemsTable();
            }

            $('#btnAddItem').on('click', function() {
                const selectedBarang = $('#search_barang').select2('data')[0];

                if (!selectedBarang || !selectedBarang.id) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: 'Silakan pilih produk terlebih dahulu'
                    });
                    return;
                }

                if (items.find(item => item.barang_id == selectedBarang.id)) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: 'Produk sudah ditambahkan'
                    });
                    return;
                }

                addItemRow(selectedBarang);
                $('#search_barang').val(null).trigger('change');
            });

            function renderItemsTable() {
                const tableHtml = `
                    <div class="items-table-wrapper">
                        <table class="table items-table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 5%">No</th>
                                    <th style="width: 25%">Nama Barang</th>
                                    <th style="width: 10%">Stok</th>
                                    <th style="width: 10%">Jumlah</th>
                                    <th style="width: 12%">Harga</th>
                                    <th style="width: 8%">Bonus</th>
                                    <th style="width: 10%">Disc (%)</th>
                                    <th style="width: 15%">Subtotal</th>
                                    <th style="width: 5%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="items-tbody">
                            </tbody>
                        </table>
                    </div>
                `;
                $('#items-container').html(tableHtml);

                items.forEach(function(item) {
                    const subtotal = item.jumlah * item.harga_satuan * (1 - item.diskon / 100);
                    const rowHtml = `
                        <tr data-counter="${item.counter}">
                            <td class="text-center">${item.counter}</td>
                            <td>
                                <strong>${item.nama_barang || '-'}</strong>
                            </td>
                            <td class="text-center">
                                <span class="badge-info-custom">${item.stok || 0}</span>
                            </td>
                            <td>
                                <input type="number" class="form-control item-jumlah" data-counter="${item.counter}"
                                       value="${item.jumlah}" min="1" max="${item.stok}" required ${item.stok === 0 ? 'readonly' : ''}>
                            </td>
                            <td>
                                <input type="text" class="form-control item-harga" data-counter="${item.counter}"
                                       value="${formatNumber(item.harga_satuan)}" required>
                            </td>
                            <td>
                                <input type="number" class="form-control item-bonus" data-counter="${item.counter}"
                                       value="${item.bonus}" min="0">
                            </td>
                            <td>
                                <input type="number" class="form-control item-diskon" data-counter="${item.counter}"
                                       value="${item.diskon}" min="0" max="100" step="0.01">
                            </td>
                            <td>
                                <input type="text" class="form-control item-subtotal" data-counter="${item.counter}"
                                       value="Rp ${formatNumber(subtotal)}" readonly>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-remove-item btn-sm" onclick="removeItem(${item.counter})">
                                    <i class="ft-trash-2"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                    $('#items-tbody').append(rowHtml);
                });

                bindItemEvents();
                calculateTotal();
            }

            function addItemRow(barang) {
                itemCounter++;

                const itemData = {
                    counter: itemCounter,
                    barang_id: barang.id,
                    nama_barang: barang.nama_barang,
                    stok: barang.stok,
                    harga_satuan: barang.harga_jual,
                    satuan: barang.satuan,
                    jumlah: 1,
                    bonus: 0,
                    diskon: 0
                };

                items.push(itemData);

                if ($('.items-table-wrapper').length === 0) {
                    renderItemsTable();
                } else {
                    const rowHtml = `
                        <tr data-counter="${itemCounter}">
                            <td class="text-center">${itemCounter}</td>
                            <td>
                                <strong>${barang.nama_barang || '-'}</strong>
                            </td>
                            <td class="text-center">
                                <span class="badge-info-custom">${barang.stok || 0}</span>
                            </td>
                            <td>
                                <input type="number" class="form-control item-jumlah" data-counter="${itemCounter}"
                                       value="1" min="1" max="${barang.stok}" required ${barang.stok === 0 ? 'readonly' : ''}>
                            </td>
                            <td>
                                <input type="text" class="form-control item-harga" data-counter="${itemCounter}"
                                       value="${formatNumber(barang.harga_jual)}" required>
                            </td>
                            <td>
                                <input type="number" class="form-control item-bonus" data-counter="${itemCounter}"
                                       value="0" min="0">
                            </td>
                            <td>
                                <input type="number" class="form-control item-diskon" data-counter="${itemCounter}"
                                       value="0" min="0" max="100" step="0.01">
                            </td>
                            <td>
                                <input type="text" class="form-control item-subtotal" data-counter="${itemCounter}"
                                       value="Rp ${formatNumber(barang.harga_jual)}" readonly>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-remove-item btn-sm" onclick="removeItem(${itemCounter})">
                                    <i class="ft-trash-2"></i>
                                </button>
                            </td>
                        </tr>
                    `;

                    $('#items-tbody').append(rowHtml);
                    calculateTotal();
                    bindItemEvents();
                }
            }

            function bindItemEvents() {
                $('.item-jumlah, .item-bonus, .item-diskon').off('input').on('input', function() {
                    const counter = $(this).data('counter');

                    // Validate stock limit for jumlah
                    if ($(this).hasClass('item-jumlah')) {
                        const item = items.find(i => i.counter === counter);
                        if (item && item.stok === 0) {
                            $(this).val(0);
                            Swal.fire({
                                icon: 'error',
                                title: 'Stok Habis',
                                text: 'Stok barang ini sudah habis',
                                timer: 2000
                            });
                            return;
                        }

                        const maxStok = parseInt($(this).attr('max'));
                        const currentVal = parseInt($(this).val());
                        if (currentVal > maxStok) {
                            $(this).val(maxStok);
                            Swal.fire({
                                icon: 'warning',
                                title: 'Melebihi Stok',
                                text: `Stok tersedia hanya ${maxStok}`,
                                timer: 2000
                            });
                        }
                    }

                    updateItemSubtotal(counter);
                });

                $('.item-harga').off('input').on('input', function() {
                    const counter = $(this).data('counter');
                    formatItemHarga($(this));
                    updateItemSubtotal(counter);
                });

                $('.item-harga').off('blur').on('blur', function() {
                    formatInputNumber($(this));
                });
            }

            function updateItemSubtotal(counter) {
                const $row = $(`tr[data-counter="${counter}"]`);
                const jumlah = parseFloat($row.find('.item-jumlah').val()) || 0;
                const harga = unformatNumber($row.find('.item-harga').val().toString());
                const diskon = parseFloat($row.find('.item-diskon').val()) || 0;

                let subtotal = jumlah * harga;
                subtotal = subtotal * (1 - diskon / 100);

                $row.find('.item-subtotal').val('Rp ' + formatNumber(subtotal));

                const itemIndex = items.findIndex(item => item.counter === counter);
                if (itemIndex !== -1) {
                    items[itemIndex].jumlah = jumlah;
                    items[itemIndex].harga_satuan = harga;
                    items[itemIndex].bonus = parseFloat($row.find('.item-bonus').val()) || 0;
                    items[itemIndex].diskon = diskon;
                }

                calculateTotal();
            }

            function formatItemHarga(input) {
                let cursorPos = input[0].selectionStart;
                let oldLength = input.val().length;

                formatInputNumber(input);

                let newLength = input.val().length;
                let newCursorPos = cursorPos + (newLength - oldLength);
                input[0].setSelectionRange(newCursorPos, newCursorPos);
            }

            window.removeItem = function(counter) {
                Swal.fire({
                    title: 'Hapus Item?',
                    text: 'Item akan dihapus dari transaksi',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#f5576c',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(`tr[data-counter="${counter}"]`).fadeOut(300, function() {
                            $(this).remove();
                            items = items.filter(item => item.counter !== counter);

                            if (items.length === 0) {
                                $('#items-container').html('');
                            } else {
                                $('#items-tbody tr').each(function(index) {
                                    $(this).find('td:first').text(index + 1);
                                });
                            }

                            calculateTotal();
                        });
                    }
                });
            };

            $('#biaya_kirim, #biaya_lain, #diskon_total, #ppn, #bayar').on('input change', function() {
                calculateTotal();
            });

            function calculateTotal() {
                let subtotal = 0;

                items.forEach(item => {
                    const $row = $(`tr[data-counter="${item.counter}"]`);
                    const jumlah = parseFloat($row.find('.item-jumlah').val()) || 0;
                    const harga = unformatNumber($row.find('.item-harga').val().toString());
                    const diskon = parseFloat($row.find('.item-diskon').val()) || 0;

                    let itemSubtotal = jumlah * harga;
                    itemSubtotal = itemSubtotal * (1 - diskon / 100);
                    subtotal += itemSubtotal;
                });

                const diskonTotal = parseFloat($('#diskon_total').val()) || 0;
                const diskonAmount = subtotal * (diskonTotal / 100);

                let totalSetelahDiskon = subtotal - diskonAmount;

                const biayaKirim = unformatNumber($('#biaya_kirim').val());
                const biayaLain = unformatNumber($('#biaya_lain').val());

                totalSetelahDiskon += biayaKirim + biayaLain;

                let ppnAmount = 0;
                let grandTotal = totalSetelahDiskon;
                const ppnType = $('#ppn').val();

                if (ppnType === 'PPN') {
                    ppnAmount = totalSetelahDiskon * ppnRate;
                    grandTotal = totalSetelahDiskon + ppnAmount;
                }

                const bayar = unformatNumber($('#bayar').val());
                const kembalian = Math.max(0, bayar - grandTotal);
                const sisa = Math.max(0, grandTotal - bayar);

                $('#display_subtotal').text('Rp ' + formatNumber(subtotal));
                $('#display_diskon').text('Rp ' + formatNumber(diskonAmount));
                $('#display_ppn').text('Rp ' + formatNumber(ppnAmount));
                $('#display_biaya_kirim').text('Rp ' + formatNumber(biayaKirim));
                $('#display_biaya_lain').text('Rp ' + formatNumber(biayaLain));
                $('#display_grand_total').text('Rp ' + formatNumber(grandTotal));
                $('#display_kembalian').text('Rp ' + formatNumber(kembalian));
                $('#display_sisa').text('Rp ' + formatNumber(sisa));
            }

            $('#btnSave').on('click', function() {
                if (items.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: 'Silakan tambahkan minimal 1 item'
                    });
                    return;
                }

                const tanggalPenjualan = $('#tanggal_penjualan').val();
                const pelangganId = $('#pelanggan_id').val();
                const ppn = $('#ppn').val();
                const paymentMethod = $('#payment_method').val();
                const jatuhTempo = $('#jatuh_tempo').val();

                if (!tanggalPenjualan || !pelangganId || !ppn || !paymentMethod) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: 'Silakan lengkapi semua field yang wajib diisi'
                    });
                    return;
                }

                if (paymentMethod === 'Credit' && !jatuhTempo) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: 'Jatuh tempo harus diisi untuk metode Credit'
                    });
                    return;
                }

                const itemsData = items.map(item => {
                    const $row = $(`tr[data-counter="${item.counter}"]`);
                    return {
                        barang_id: item.barang_id,
                        jumlah: parseFloat($row.find('.item-jumlah').val()),
                        harga: unformatNumber($row.find('.item-harga').val()),
                        bonus: parseFloat($row.find('.item-bonus').val()) || 0,
                        diskon: parseFloat($row.find('.item-diskon').val()) || 0
                    };
                });

                const formData = {
                    tanggal_penjualan: tanggalPenjualan,
                    pelanggan_id: pelangganId,
                    ppn: ppn,
                    payment_method: paymentMethod,
                    jatuh_tempo: jatuhTempo,
                    biaya_kirim: unformatNumber($('#biaya_kirim').val()),
                    biaya_lain: unformatNumber($('#biaya_lain').val()),
                    diskon_total: parseFloat($('#diskon_total').val()) || 0,
                    bayar: unformatNumber($('#bayar').val()),
                    items: itemsData
                };

                Swal.fire({
                    title: 'Update Transaksi?',
                    text: 'Pastikan semua data sudah benar',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#11998e',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Update',
                    cancelButtonText: 'Cek Lagi'
                }).then((result) => {
                    if (result.isConfirmed) {
                        updateTransaction(formData);
                    }
                });
            });

            function updateTransaction(formData) {
                $.ajax({
                    url: '{{ route('penjualan.update', $penjualan->id) }}',
                    type: 'PUT',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Menyimpan...',
                            text: 'Mohon tunggu',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = '{{ route('penjualan.index') }}';
                        });
                    },
                    error: function(xhr) {
                        let errorMessage = 'Terjadi kesalahan';

                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            const errors = xhr.responseJSON.errors;
                            errorMessage = Object.values(errors).flat().join('\n');
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: errorMessage
                        });
                    }
                });
            }

            function formatNumber(num) {
                return Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

            function formatInputNumber(input) {
                let value = input.val().replace(/\./g, '');
                if (value === '' || isNaN(value)) {
                    input.val('');
                    return;
                }
                input.val(formatNumber(parseInt(value)));
            }

            function unformatNumber(str) {
                return parseInt(str.replace(/\./g, '')) || 0;
            }

            $('#biaya_kirim, #biaya_lain, #bayar').on('input', function() {
                let cursorPos = this.selectionStart;
                let oldLength = $(this).val().length;

                formatInputNumber($(this));

                let newLength = $(this).val().length;
                let newCursorPos = cursorPos + (newLength - oldLength);
                this.setSelectionRange(newCursorPos, newCursorPos);
            });

            $('#biaya_kirim, #biaya_lain, #bayar').on('blur', function() {
                formatInputNumber($(this));
            });
        });
    </script>
@endsection
