@extends('layouts.main')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/tables/datatable/datatables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/forms/selects/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <style>
        .card {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 1rem;
        }

        .card-header {
            border-bottom: 2px solid #f1f1f1;
        }

        #penjualan-table {
            font-size: 0.9rem;
            width: 100% !important;
        }

        #penjualan-table thead th {
            font-weight: 600;
            white-space: nowrap;
            font-size: 0.85rem;
        }

        #penjualan-table tbody td {
            vertical-align: middle;
        }

        .badge {
            padding: 0.375rem 0.75rem;
            font-size: 0.85rem;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            margin: 0 1px;
        }

        .filter-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            margin-bottom: 1.5rem;
            overflow: hidden;
        }

        .filter-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 1rem 1.5rem;
            border-bottom: none;
        }

        .filter-header h5 {
            color: white;
            margin: 0;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .filter-body {
            padding: 1.5rem;
            background: #f8f9fa;
        }

        .filter-group {
            margin-bottom: 1rem;
        }

        .filter-group label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .filter-group label i {
            color: #667eea;
        }

        .filter-input {
            border: 2px solid #e3e8ef;
            border-radius: 8px;
            padding: 0.625rem 1rem;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            background: white;
        }

        .filter-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
            background: white;
        }

        .btn-filter {
            border-radius: 8px;
            padding: 0.625rem 1.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-filter:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .btn-primary.btn-filter {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .btn-secondary.btn-filter {
            background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
        }

        .filter-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
            border-radius: 20px;
            font-size: 0.875rem;
            margin-top: 1rem;
        }

        .filter-badge .badge-close {
            cursor: pointer;
            font-size: 1.2rem;
            line-height: 1;
            opacity: 0.8;
            transition: opacity 0.2s;
        }

        .filter-badge .badge-close:hover {
            opacity: 1;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        @media (max-width: 768px) {
            #penjualan-table {
                font-size: 0.8rem;
            }

            .btn {
                padding: 0.375rem 0.75rem;
                font-size: 0.875rem;
            }
        }
    </style>
@endsection

@section('content')
    <section id="bootstrap3">
        <div class="row">
            <div class="col-12">
                <!-- Filter Card -->
                <div class="filter-card">
                    <div class="filter-header">
                        <h5>
                            <i class="ft-filter"></i>
                            Filter Penjualan
                        </h5>
                    </div>
                    <div class="filter-body">
                        <form id="filterForm">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="filter-group">
                                        <label>
                                            <i class="ft-calendar"></i>
                                            Tanggal
                                        </label>
                                        <input type="text" id="filter_date" name="date_range"
                                            class="form-control filter-input" placeholder="Pilih rentang tanggal..."
                                            autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="filter-group">
                                        <label>
                                            <i class="ft-info"></i>
                                            Status
                                        </label>
                                        <select id="filter_status" class="form-control filter-input">
                                            <option value="">Semua Status</option>
                                            <option value="Lunas">Lunas</option>
                                            <option value="Belum Lunas">Belum Lunas</option>
                                            <option value="Pending">Pending</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="filter-group">
                                        <label>
                                            <i class="ft-credit-card"></i>
                                            Payment
                                        </label>
                                        <select id="filter_payment" class="form-control filter-input">
                                            <option value="">Semua Payment</option>
                                            <option value="Cash">Cash</option>
                                            <option value="Credit">Credit</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="filter-group">
                                        <label>
                                            <i class="ft-user"></i>
                                            Pelanggan
                                        </label>
                                        <select id="filter_pelanggan" class="form-control filter-input"
                                            style="width: 100%;">
                                            <option value="">Semua Pelanggan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="filter-group">
                                        <label>&nbsp;</label>
                                        <div class="d-flex gap-2">
                                            <button type="button" id="applyFilter"
                                                class="btn btn-primary btn-filter btn-block">
                                                <i class="ft-search"></i> Filter
                                            </button>
                                            <button type="button" id="resetFilter" class="btn btn-secondary btn-filter">
                                                <i class="ft-refresh-cw"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Filter Status Badge -->
                            <div id="filterStatus" style="display: none;">
                                <div class="filter-badge">
                                    <i class="ft-info"></i>
                                    <span id="filterStatusText">Filter aktif</span>
                                    <span class="badge-close" onclick="resetFilterStatus()">×</span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Table Card -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            <i class="ft-file-text"></i> Daftar Penjualan
                        </h4>
                        <div class="heading-elements">
                            <a href="{{ route('penjualan.create') }}"
                                class="btn btn-primary btn-md d-flex align-items-center">
                                <i class="la la-plus mr-1"></i> Tambah Penjualan
                            </a>
                        </div>
                        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body card-dashboard">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="penjualan-table" style="width: 100%;">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th width="5%">No</th>
                                            <th>No. Invoice</th>
                                            <th>Tanggal</th>
                                            <th>Pelanggan</th>
                                            <th>Grand Total</th>
                                            <th>Status</th>
                                            <th>Payment</th>
                                            <th>Created By</th>
                                            <th width="10%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data akan diisi oleh DataTables -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/datatables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('app-assets/js/scripts/tables/datatables/datatable-basic.min.js') }}" type="text/javascript">
    </script>
    <script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        $(document).ready(function() {
            initializePelangganFilter();

            // Initialize DateRangePicker
            $('#filter_date').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear',
                    format: 'DD/MM/YYYY',
                    separator: ' - ',
                    applyLabel: 'Terapkan',
                    cancelLabel: 'Batal',
                    fromLabel: 'Dari',
                    toLabel: 'Sampai',
                    customRangeLabel: 'Custom',
                    weekLabel: 'W',
                    daysOfWeek: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                    monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                    ],
                    firstDay: 1
                },
                ranges: {
                    'Hari Ini': [moment(), moment()],
                    'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    '7 Hari Terakhir': [moment().subtract(6, 'days'), moment()],
                    '30 Hari Terakhir': [moment().subtract(29, 'days'), moment()],
                    'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
                    'Bulan Lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')]
                }
            });

            $('#filter_date').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format(
                    'DD/MM/YYYY'));
            });

            $('#filter_date').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });

            // Initialize DataTable
            var table = $('#penjualan-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: '{{ route('penjualan.index') }}',
                    type: 'GET',
                    data: function(d) {
                        const pelangganId = $('#filter_pelanggan').val();
                        d.date_range = $('#filter_date').val();
                        d.status = $('#filter_status').val();
                        d.payment = $('#filter_payment').val();
                        d.pelanggan_id = pelangganId || '';
                        d.pelanggan = pelangganId ? $('#filter_pelanggan').find('option:selected')
                        .text() : '';
                    },
                    error: function(xhr, error, thrown) {
                        console.error('DataTables Error:', error, thrown);
                        alert('Terjadi kesalahan saat memuat data. Silakan refresh halaman.');
                    }
                },
                columns: [{
                        data: null,
                        name: 'no',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'no_invoice',
                        name: 'no_invoice'
                    },
                    {
                        data: 'tanggal_penjualan',
                        name: 'tanggal_penjualan',
                        className: 'text-center'
                    },
                    {
                        data: 'pelanggan.nama',
                        name: 'pelanggan.nama'
                    },
                    {
                        data: 'grand_total',
                        name: 'grand_total',
                        className: 'text-right',
                        render: function(data) {
                            return 'Rp ' + parseFloat(data).toLocaleString('id-ID');
                        }
                    },
                    {
                        data: 'status',
                        name: 'status',
                        className: 'text-center',
                        render: function(data) {
                            let badgeClass = '';
                            if (data === 'Lunas') {
                                badgeClass = 'badge-success';
                            } else if (data === 'Belum Lunas') {
                                badgeClass = 'badge-warning';
                            } else {
                                badgeClass = 'badge-secondary';
                            }
                            return '<span class="badge ' + badgeClass + '">' + data + '</span>';
                        }
                    },
                    {
                        data: 'payment_method',
                        name: 'payment_method'
                    },
                    {
                        data: 'created_by_name',
                        name: 'created_by_name'
                    },
                    {
                        data: null,
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function(data, type, row) {
                            return `
                                <a href="${row.show_url}" class="btn btn-info btn-sm" data-toggle="tooltip" title="Lihat Detail">
                                    <i class="ft-eye"></i>
                                </a>
                                <a href="${row.edit_url}" class="btn btn-warning btn-sm" data-toggle="tooltip" title="Edit">
                                    <i class="ft-edit"></i>
                                </a>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="${row.id}" data-invoice="${row.no_invoice}" data-toggle="tooltip" title="Hapus">
                                    <i class="ft-trash"></i>
                                </button>
                            `;
                        }
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

            // Apply Filter
            $('#applyFilter').on('click', function() {
                table.ajax.reload();
                updateFilterStatus();
            });

            // Reset Filter
            $('#resetFilter').on('click', function() {
                $('#filter_date').val('');
                $('#filter_status').val('');
                $('#filter_payment').val('');
                $('#filter_pelanggan').val(null).trigger('change');
                table.ajax.reload();
                $('#filterStatus').hide();
            });

            $('#filter_pelanggan').on('change', function() {
                updateFilterStatus();
            });

            // Update Filter Status
            function updateFilterStatus() {
                let filters = [];

                if ($('#filter_date').val()) {
                    filters.push('Tanggal: ' + $('#filter_date').val());
                }
                if ($('#filter_status').val()) {
                    filters.push('Status: ' + $('#filter_status').val());
                }
                if ($('#filter_payment').val()) {
                    filters.push('Payment: ' + $('#filter_payment').val());
                }
                if ($('#filter_pelanggan').val()) {
                    filters.push('Pelanggan: ' + $('#filter_pelanggan').find('option:selected').text());
                }

                if (filters.length > 0) {
                    $('#filterStatusText').text(filters.join(' | '));
                    $('#filterStatus').slideDown();
                } else {
                    $('#filterStatus').slideUp();
                }
            }

            // Reset Filter Status
            window.resetFilterStatus = function() {
                $('#filter_date').val('');
                $('#filter_status').val('');
                $('#filter_payment').val('');
                $('#filter_pelanggan').val(null).trigger('change');
                table.ajax.reload();
                $('#filterStatus').slideUp();
            };

            function initializePelangganFilter() {
                $('#filter_pelanggan').select2({
                    placeholder: '-- Cari Nama / No HP Pelanggan --',
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $('body'),
                    ajax: {
                        url: '{{ route('api.pelanggan.search') }}',
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
                                    more: data.pagination ? data.pagination.more : false
                                }
                            };
                        },
                        cache: false
                    },
                    minimumInputLength: 1,
                    language: {
                        noResults: function() {
                            return 'Pelanggan tidak ditemukan';
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

            // Handle delete
            $('#penjualan-table').on('click', '.delete-btn', function() {
                const id = $(this).data('id');
                const invoice = $(this).data('invoice');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: `Hapus transaksi penjualan ${invoice}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/penjualan/${id}`,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire('Berhasil!', response.message, 'success');
                                    table.ajax.reload();
                                } else {
                                    Swal.fire('Error!', response.message, 'error');
                                }
                            },
                            error: function(xhr) {
                                Swal.fire('Error!',
                                    'Terjadi kesalahan saat menghapus data', 'error'
                                );
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
