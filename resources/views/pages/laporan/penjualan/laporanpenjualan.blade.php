@extends('layouts.main')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/tables/datatable/datatables.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <style>
        .card {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 1rem;
        }

        .card-header {
            border-bottom: 2px solid #f1f1f1;
        }

        #table-laporan-penjualan {
            font-size: 0.9rem;
        }

        #table-laporan-penjualan thead th {
            font-weight: 600;
            white-space: nowrap;
            font-size: 0.85rem;
        }

        #table-laporan-penjualan tbody td {
            vertical-align: middle;
        }

        .badge {
            padding: 0.375rem 0.75rem;
            font-size: 0.85rem;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        }

        .bg-gradient-warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important;
        }

        .bg-gradient-danger {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%) !important;
        }

        .card-body .media {
            align-items: center;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        @media (max-width: 768px) {
            #table-laporan-penjualan {
                font-size: 0.8rem;
            }

            .card-body h3,
            .card-body h4 {
                font-size: 1.2rem;
            }

            .btn {
                padding: 0.375rem 0.75rem;
                font-size: 0.875rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="ft-file-text"></i> {{ $title }}
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="card bg-gradient-primary text-white" style="min-height: 120px;">
                                <div class="card-body py-3">
                                    <div class="media">
                                        <div class="media-body text-left">
                                            <h3 class="mb-1 text-white font-weight-bold" id="total-transaksi">
                                                {{ $summary['total_transaksi'] }}</h3>
                                            <span class="text-white font-medium-1">Total Transaksi</span>
                                        </div>
                                        <div class="media-right align-self-center ml-2">
                                            <i class="ft-shopping-cart font-large-2 text-white"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="card bg-gradient-success text-white" style="min-height: 120px;">
                                <div class="card-body py-3">
                                    <div class="media">
                                        <div class="media-body text-left">
                                            <h4 class="mb-1 text-white font-weight-bold" id="total-penjualan">Rp
                                                {{ number_format($summary['total_penjualan'], 0, ',', '.') }}</h4>
                                            <span class="text-white font-medium-1">Total Penjualan</span>
                                        </div>
                                        <div class="media-right align-self-center ml-2">
                                            <i class="ft-trending-up font-large-2 text-white"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="card bg-gradient-warning text-white" style="min-height: 120px;">
                                <div class="card-body py-3">
                                    <div class="media">
                                        <div class="media-body text-left">
                                            <h4 class="mb-1 text-white font-weight-bold" id="total-diskon">Rp
                                                {{ number_format($summary['total_diskon'], 0, ',', '.') }}</h4>
                                            <span class="text-white font-medium-1">Total Diskon</span>
                                        </div>
                                        <div class="media-right align-self-center ml-2">
                                            <i class="ft-percent font-large-2 text-white"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="card bg-gradient-danger text-white" style="min-height: 120px;">
                                <div class="card-body py-3">
                                    <div class="media">
                                        <div class="media-body text-left">
                                            <h4 class="mb-1 text-white font-weight-bold" id="total-sisa">Rp
                                                {{ number_format($summary['total_sisa'], 0, ',', '.') }}</h4>
                                            <span class="text-white font-medium-1">Total Piutang</span>
                                        </div>
                                        <div class="media-right align-self-center ml-2">
                                            <i class="ft-alert-circle font-large-2 text-white"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="ft-filter"></i> Filter Data
                            </h5>
                        </div>
                        <div class="card-body">
                            <form id="filter-form">
                                <input type="hidden" id="start_date" name="start_date">
                                <input type="hidden" id="end_date" name="end_date">

                                <div class="row">
                                    <div class="col-md-4 mb-2">
                                        <label for="daterange">
                                            <i class="ft-calendar"></i> Rentang Tanggal
                                        </label>
                                        <input type="text" class="form-control" id="daterange" placeholder="Pilih rentang tanggal">
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label for="status">Status</label>
                                        <select class="form-control" id="status" name="status">
                                            <option value="">Semua</option>
                                            <option value="Pending">Pending</option>
                                            <option value="Lunas">Lunas</option>
                                            <option value="Belum Lunas">Belum Lunas</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label for="payment_method">Metode Bayar</label>
                                        <select class="form-control" id="payment_method" name="payment_method">
                                            <option value="">Semua</option>
                                            <option value="Cash">Cash</option>
                                            <option value="Credit">Credit</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="">
                                    <label for="pelanggan_id">Pelanggan</label>
                                    <select class="form-control select2" id="pelanggan_id" name="pelanggan_id" >
                                        <option value="">Semua Pelanggan</option>
                                        @foreach ($pelanggans as $pelanggan)
                                            <option value="{{ $pelanggan->id }}">{{ $pelanggan->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-primary" id="btn-filter">
                                            <i class="ft-search"></i> Filter
                                        </button>
                                        <button type="button" class="btn btn-secondary" id="btn-reset">
                                            <i class="ft-refresh-cw"></i> Reset
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive mt-3">
                        <table class="table table-striped table-bordered" id="table-laporan-penjualan"
                            style="width: 100%;">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th width="5%">No</th>
                                    <th>No Invoice</th>
                                    <th>Tanggal</th>
                                    <th>Pelanggan</th>
                                    <th>Items</th>
                                    <th>Total Harga</th>
                                    <th>Diskon</th>
                                    <th>Grand Total</th>
                                    <th>Bayar</th>
                                    <th>Sisa</th>
                                    <th>Status</th>
                                    <th>Metode</th>
                                    <th>Kasir</th>
                                    <th width="8%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-detail" tabindex="-1" role="dialog" aria-labelledby="modalDetailLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalDetailLabel">
                        <i class="ft-file-text"></i> Detail Penjualan
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modal-detail-body">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="ft-x"></i> Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/datatables.min.js') }}" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#daterange').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    format: 'DD/MM/YYYY',
                    separator: ' - ',
                    applyLabel: 'Terapkan',
                    cancelLabel: 'Batal',
                    fromLabel: 'Dari',
                    toLabel: 'Sampai',
                    customRangeLabel: 'Custom',
                    weekLabel: 'W',
                    daysOfWeek: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                    monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                    firstDay: 1
                },
                ranges: {
                    'Hari Ini': [moment(), moment()],
                    'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    '7 Hari Terakhir': [moment().subtract(6, 'days'), moment()],
                    '30 Hari Terakhir': [moment().subtract(29, 'days'), moment()],
                    'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
                    'Bulan Lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            });

            $('#daterange').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                $('#start_date').val(picker.startDate.format('YYYY-MM-DD'));
                $('#end_date').val(picker.endDate.format('YYYY-MM-DD'));
            });

            $('#daterange').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                $('#start_date').val('');
                $('#end_date').val('');
            });

            $('.select2').select2({
                theme: 'bootstrap-5',
                placeholder: 'Pilih Pelanggan',
                allowClear: true,
                width: '100%'
            });

            let table = $('#table-laporan-penjualan').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('laporan.penjualan.index') }}",
                    type: 'GET',
                    data: function(d) {
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();
                        d.status = $('#status').val();
                        d.payment_method = $('#payment_method').val();
                        d.pelanggan_id = $('#pelanggan_id').val();
                    },
                    error: function(xhr, error, thrown) {
                        console.error('DataTables Error:', error, thrown);
                        alert('Terjadi kesalahan saat memuat data. Silakan refresh halaman.');
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'no_invoice',
                        name: 'no_invoice'
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal_penjualan',
                        className: 'text-center'
                    },
                    {
                        data: 'pelanggan',
                        name: 'pelanggan.nama'
                    },
                    {
                        data: 'items',
                        name: 'items',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'total_harga',
                        name: 'total_harga',
                        className: 'text-right'
                    },
                    {
                        data: 'diskon',
                        name: 'diskon',
                        className: 'text-right'
                    },
                    {
                        data: 'grand_total',
                        name: 'grand_total',
                        className: 'text-right'
                    },
                    {
                        data: 'bayar',
                        name: 'bayar',
                        className: 'text-right'
                    },
                    {
                        data: 'sisa',
                        name: 'sisa',
                        className: 'text-right'
                    },
                    {
                        data: 'status_badge',
                        name: 'status',
                        className: 'text-center'
                    },
                    {
                        data: 'payment_badge',
                        name: 'payment_method',
                        className: 'text-center'
                    },
                    {
                        data: 'kasir',
                        name: 'createdBy.nama_depan'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ],
                order: [
                    [2, 'desc']
                ],
                pageLength: 25,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json',
                    processing: '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>'
                },
                drawCallback: function() {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });

            $('#btn-filter').on('click', function() {
                console.log('Filter clicked');
                console.log('Start Date:', $('#start_date').val());
                console.log('End Date:', $('#end_date').val());
                console.log('Status:', $('#status').val());
                console.log('Payment Method:', $('#payment_method').val());
                console.log('Pelanggan ID:', $('#pelanggan_id').val());

                table.ajax.reload(function() {
                    updateSummary();
                });
            });

            $('#btn-reset').on('click', function() {
                $('#filter-form')[0].reset();
                $('#daterange').val('');
                $('#start_date').val('');
                $('#end_date').val('');
                $('#status').val('').trigger('change');
                $('#payment_method').val('').trigger('change');
                $('.select2').val(null).trigger('change');

                table.ajax.reload(function() {
                    updateSummary();
                });
            });

            function updateSummary() {
                $.ajax({
                    url: "{{ route('laporan.penjualan.index') }}",
                    type: 'GET',
                    data: {
                        start_date: $('#start_date').val(),
                        end_date: $('#end_date').val(),
                        status: $('#status').val(),
                        payment_method: $('#payment_method').val(),
                        pelanggan_id: $('#pelanggan_id').val(),
                        summary_only: true
                    },
                    success: function(response) {
                        $('#total-transaksi').text(response.total_transaksi);
                        $('#total-penjualan').text('Rp ' + Number(response.total_penjualan).toLocaleString('id-ID'));
                        $('#total-diskon').text('Rp ' + Number(response.total_diskon).toLocaleString('id-ID'));
                        $('#total-sisa').text('Rp ' + Number(response.total_sisa).toLocaleString('id-ID'));
                        console.log('Summary updated successfully');
                    },
                    error: function(xhr, status, error) {
                        console.error('Error updating summary:', error);
                    }
                });
            }

            $(document).on('click', '.btn-detail', function() {
                let id = $(this).data('id');

                $('#modal-detail').modal('show');
                $('#modal-detail-body').html(`
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            `);

                $.ajax({
                    url: `/laporan/penjualan/${id}/detail`,
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            let data = response.data;
                            let detailItems = '';

                            data.detail_penjualans.forEach((item, index) => {
                                detailItems += `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${item.barang ? item.barang.nama : '-'}</td>
                                    <td>${item.jumlah}</td>
                                    <td>Rp ${Number(item.harga_satuan).toLocaleString('id-ID')}</td>
                                    <td>${item.bonus || 0}</td>
                                    <td>Rp ${Number(item.diskon_item).toLocaleString('id-ID')}</td>
                                    <td>Rp ${Number(item.subtotal).toLocaleString('id-ID')}</td>
                                </tr>
                            `;
                            });

                            let html = `
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="40%">No Invoice</th>
                                            <td>: ${data.no_invoice}</td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal</th>
                                            <td>: ${new Date(data.tanggal_penjualan).toLocaleDateString('id-ID')}</td>
                                        </tr>
                                        <tr>
                                            <th>Pelanggan</th>
                                            <td>: ${data.pelanggan ? data.pelanggan.nama : '-'}</td>
                                        </tr>
                                        <tr>
                                            <th>Metode Bayar</th>
                                            <td>: ${data.payment_method.toUpperCase()}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="40%">Status</th>
                                            <td>: <span class="badge badge-${data.status === 'lunas' ? 'success' : data.status === 'cancel' ? 'danger' : 'warning'}">${data.status.toUpperCase()}</span></td>
                                        </tr>
                                        <tr>
                                            <th>Kasir</th>
                                            <td>: ${data.created_by ? data.created_by.nama_depan + ' ' + data.created_by.nama_belakang : '-'}</td>
                                        </tr>
                                        <tr>
                                            <th>Catatan</th>
                                            <td>: ${data.catatan || '-'}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <h6 class="mb-2"><strong>Detail Item:</strong></h6>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead class="bg-light">
                                        <tr>
                                            <th width="5%">No</th>
                                            <th>Barang</th>
                                            <th>Jumlah</th>
                                            <th>Harga</th>
                                            <th>Bonus</th>
                                            <th>Diskon</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${detailItems}
                                    </tbody>
                                </table>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6 offset-md-6">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Total Harga:</th>
                                            <td class="text-right">Rp ${Number(data.total_harga).toLocaleString('id-ID')}</td>
                                        </tr>
                                        <tr>
                                            <th>Diskon:</th>
                                            <td class="text-right">Rp ${Number(data.diskon).toLocaleString('id-ID')}</td>
                                        </tr>
                                        <tr>
                                            <th>PPN:</th>
                                            <td class="text-right">Rp ${Number(data.ppn_amount).toLocaleString('id-ID')}</td>
                                        </tr>
                                        <tr>
                                            <th>Biaya Kirim:</th>
                                            <td class="text-right">Rp ${Number(data.biaya_kirim).toLocaleString('id-ID')}</td>
                                        </tr>
                                        <tr>
                                            <th>Biaya Lain:</th>
                                            <td class="text-right">Rp ${Number(data.biaya_lain).toLocaleString('id-ID')}</td>
                                        </tr>
                                        <tr class="bg-light">
                                            <th>Grand Total:</th>
                                            <td class="text-right"><strong>Rp ${Number(data.grand_total).toLocaleString('id-ID')}</strong></td>
                                        </tr>
                                        <tr>
                                            <th>Bayar:</th>
                                            <td class="text-right">Rp ${Number(data.bayar).toLocaleString('id-ID')}</td>
                                        </tr>
                                        <tr>
                                            <th>Sisa:</th>
                                            <td class="text-right">Rp ${Number(data.sisa).toLocaleString('id-ID')}</td>
                                        </tr>
                                        <tr>
                                            <th>Kembalian:</th>
                                            <td class="text-right">Rp ${Number(data.kembalian).toLocaleString('id-ID')}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        `;

                            $('#modal-detail-body').html(html);
                        }
                    },
                    error: function() {
                        $('#modal-detail-body').html(
                            '<div class="alert alert-danger">Gagal memuat data</div>');
                    }
                });
            });

            $('#btn-export-excel').on('click', function() {
                alert('Fitur export Excel akan segera tersedia');
            });

            $('#btn-export-pdf').on('click', function() {
                alert('Fitur export PDF akan segera tersedia');
            });

            $('#btn-print').on('click', function() {
                window.print();
            });
        });
    </script>
@endsection
