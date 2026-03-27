@extends('layouts.main')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/tables/datatable/datatables.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/pickers/daterange/daterangepicker.css') }}">
    <style>
        .filter-section {
            flex-wrap: wrap;
            padding: 0.5rem 0;
        }

        .filter-left {
            flex: 1;
            min-width: 300px;
            margin-right: 1rem;
        }

        .filter-right {
            flex-shrink: 0;
            margin-left: 1rem;
        }

        .filter-label {
            white-space: nowrap;
            min-width: fit-content;
            margin-right: 0.5rem;
        }

        .table th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            text-align: center;
            vertical-align: middle;
            font-size: 13px;
        }

        .table td {
            vertical-align: middle;
            font-size: 13px;
        }

        .text-right {
            text-align: right !important;
        }

        .text-center {
            text-align: center !important;
        }

        .filter-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 1.25rem;
            margin: 0 1.25rem 1.5rem 1.25rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .filter-title {
            font-size: 0.95rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .filter-title i {
            color: #007bff;
        }

        .daterange {
            min-width: 240px;
            border-radius: 4px 0 0 4px;
        }

        .input-group-text {
            background-color: #f8f9fa;
            border-left: none;
            border-radius: 0 4px 4px 0;
        }

        .btn-primary {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            border: none;
            transition: all 0.3s ease;
            border-radius: 4px;
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.3);
        }

        .btn-outline-secondary {
            border-color: #6c757d;
            color: #6c757d;
            transition: all 0.3s ease;
            border-radius: 4px;
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }

        .btn-outline-secondary:hover {
            background-color: #6c757d;
            transform: translateY(-1px);
        }

        @media (max-width: 768px) {
            .filter-card {
                margin: 0 0.75rem 1rem 0.75rem;
                padding: 1rem;
            }

            .filter-section {
                flex-direction: column;
                align-items: stretch;
                gap: 1rem;
                padding: 0;
            }

            .filter-left {
                flex: none;
                min-width: auto;
                margin-right: 0;
            }

            .filter-right {
                justify-content: center;
                flex-direction: column;
                gap: 0.5rem;
                margin-left: 0;
            }

            .filter-right .btn {
                width: 100%;
                padding: 0.5rem 1rem;
            }

            .daterange {
                min-width: 100%;
            }

            .filter-label {
                margin-right: 0.25rem;
            }
        }

        @media (max-width: 576px) {
            .filter-card {
                margin: 0 0.5rem 1rem 0.5rem;
                padding: 0.875rem;
            }

            .filter-title {
                font-size: 0.9rem;
                margin-bottom: 0.625rem;
            }

            .daterange {
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
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">{{ $title }}</h4>
                    </div>
                </div>

                <!-- Filter Section -->
                <div class="filter-card">
                    <div class="filter-title">
                        <i class="fa fa-filter"></i>
                        Filter Data
                    </div>
                    <div class="filter-section d-flex justify-content-between align-items-center gap-4">
                        <div class="filter-left d-flex align-items-center gap-3">
                            <label for="daterange" class="mb-0 font-weight-bold text-muted filter-label">Rentang
                                Tanggal:</label>
                            <div class='input-group'>
                                <input type='text' id="daterange" class="form-control daterange"
                                    placeholder="Pilih Rentang Tanggal" />
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <span class="ft-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="filter-right d-flex align-items-center gap-2">
                            <button type="button" id="btn-filter" class="btn btn-primary btn-sm">
                                <i class="fa fa-search"></i> Terapkan Filter
                            </button>
                            <button type="button" id="btn-reset" class="btn btn-outline-secondary btn-sm">
                                <i class="fa fa-refresh"></i> Reset
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-content">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" id="laporan-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No. Reff</th>
                                        <th>Tanggal Keluar</th>
                                        <th>Jenis Stok</th>
                                        <th>Total Item</th>
                                        <th>Total Nilai</th>
                                        <th>Status</th>
                                        <th>Alasan Cancel</th>
                                        <th>Dibuat Oleh</th>
                                        <th>Detail Barang</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail -->
    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Detail Barang Keluar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="detail-content">
                        <!-- Detail content will be loaded here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/datatables.min.js') }}" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="{{ asset('app-assets/vendors/js/pickers/dateTime/moment-with-locales.min.js') }}" type="text/javascript">
    </script>
    <script src="{{ asset('app-assets/vendors/js/pickers/daterange/daterangepicker.js') }}" type="text/javascript"></script>

    <script>
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Initialize DateRange Picker
            $('.daterange').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear',
                    applyLabel: 'Apply',
                    format: 'DD/MM/YYYY',
                    separator: ' - ',
                    fromLabel: 'Dari',
                    toLabel: 'Sampai',
                    customRangeLabel: 'Custom Range',
                    daysOfWeek: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                    monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',
                        'September', 'Oktober', 'November', 'Desember'
                    ],
                    firstDay: 1
                },
                startDate: moment().startOf('month'),
                endDate: moment().endOf('month')
            });

            // Set initial value
            const startOfMonth = moment().startOf('month').format('DD/MM/YYYY');
            const endOfMonth = moment().endOf('month').format('DD/MM/YYYY');
            $('.daterange').val(startOfMonth + ' - ' + endOfMonth);

            // Handle apply event
            $('.daterange').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format(
                    'DD/MM/YYYY'));
            });

            // Handle cancel event
            $('.daterange').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });


            if (typeof $.fn.DataTable === 'undefined') {
                console.error('DataTables is not loaded!');
                return;
            }


            const table = $('#laporan-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('laporanbarangkeluar.data') }}',
                    data: function(d) {
                        const dateRange = $('.daterange').val();
                        if (dateRange && dateRange.includes(' - ')) {
                            const dates = dateRange.split(' - ');
                            // Parse DD/MM/YYYY to YYYY-MM-DD for backend
                            const startDate = moment(dates[0], 'DD/MM/YYYY').format('YYYY-MM-DD');
                            const endDate = moment(dates[1], 'DD/MM/YYYY').format('YYYY-MM-DD');
                            d.start_date = startDate;
                            d.end_date = endDate;
                        }

                    },
                    error: function(xhr, error, thrown) {
                        console.error('DataTables AJAX error:', error, thrown, xhr.responseText);
                        toastr.error('Terjadi kesalahan saat memuat data');
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'no_reff'
                    },
                    {
                        data: 'tanggal_formatted'
                    },
                    {
                        data: 'jenis_stok'
                    },
                    {
                        data: 'total_item',
                        className: 'text-center'
                    },
                    {
                        data: 'total_nilai',
                        className: 'text-right'
                    },
                    {
                        data: 'status_badge',
                        className: 'text-center'
                    },
                    {
                        data: 'cancel_reason_display',
                        className: 'text-center'
                    },
                    {
                        data: 'created_by_name'
                    },
                    {
                        data: 'detail_items'
                    },
                    {
                        data: 'action',
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
                    processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span>',
                    emptyTable: "Tidak ada data yang tersedia",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 entri",
                    infoFiltered: "(difilter dari _MAX_ total entri)",
                    lengthMenu: "Tampilkan _MENU_ entri",
                    search: "Cari:",
                    zeroRecords: "Tidak ditemukan data yang sesuai",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                }
            });


            // Filter button click
            $('#btn-filter').on('click', function() {
                const dateRange = $('.daterange').val();

                if (!dateRange || !dateRange.includes(' - ')) {
                    toastr.warning('Silakan pilih rentang tanggal terlebih dahulu');
                    return;
                }

                const dates = dateRange.split(' - ');
                const startDate = moment(dates[0], 'DD/MM/YYYY');
                const endDate = moment(dates[1], 'DD/MM/YYYY');

                if (!startDate.isValid() || !endDate.isValid()) {
                    toastr.warning('Format tanggal tidak valid');
                    return;
                }

                if (startDate.isAfter(endDate)) {
                    toastr.warning('Tanggal mulai tidak boleh lebih besar dari tanggal akhir');
                    return;
                }

                table.ajax.reload();
                toastr.success('Filter berhasil diterapkan');
            });

            // Reset button click
            $('#btn-reset').on('click', function() {
                const startOfMonth = moment().startOf('month').format('DD/MM/YYYY');
                const endOfMonth = moment().endOf('month').format('DD/MM/YYYY');
                $('.daterange').val(startOfMonth + ' - ' + endOfMonth);

                table.ajax.reload();
                toastr.info('Filter telah direset ke bulan berjalan');
            });
        });

        // Function to show detail modal
        function showDetail(id) {
            $.ajax({
                url: '{{ route('laporanbarangkeluar.detail', ':id') }}'.replace(':id', id),
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        const data = response.data;
                        let html = `
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <th width="40%">No. Reff:</th>
                                    <td>${data.no_reff || '-'}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Keluar:</th>
                                    <td>${new Date(data.tanggal_keluar).toLocaleDateString('id-ID')}</td>
                                </tr>
                                <tr>
                                    <th>Jenis Stok:</th>
                                    <td>${data.jenis_stok ? data.jenis_stok.nama : '-'}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>${data.status === 'cancelled' ? '<span class="badge badge-danger">Cancelled</span>' : '<span class="badge badge-success">Success</span>'}</td>
                                </tr>
                                <tr>
                                    <th>Alasan Cancel:</th>
                                    <td>${data.cancel_reason || '-'}</td>
                                </tr>
                                <tr>
                                    <th>Catatan:</th>
                                    <td>${data.catatan || '-'}</td>
                                </tr>
                                <tr>
                                    <th>Dibuat Oleh:</th>
                                    <td>${data.created_by ? data.created_by.name : '-'}</td>
                                </tr>
                                <tr>
                                    <th>Dicancel Oleh:</th>
                                    <td>${data.cancelled_by ? data.cancelled_by.name : '-'}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Detail Barang:</h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nama Barang</th>
                                            <th>Jumlah</th>
                                            <th>Satuan</th>
                                            <th>Harga Jual</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>`;

                        data.detail_barang_keluar.forEach(function(detail) {
                            html += `
                        <tr>
                            <td>${detail.barang ? detail.barang.nama_barang : '-'}</td>
                            <td class="text-center">${detail.jumlah}</td>
                            <td>${detail.satuan ? detail.satuan.nama : '-'}</td>
                            <td class="text-right">Rp ${new Intl.NumberFormat('id-ID').format(detail.harga_jual)}</td>
                            <td class="text-right">Rp ${new Intl.NumberFormat('id-ID').format(detail.total)}</td>
                        </tr>`;
                        });

                        html += `
                                    </tbody>
                                    <tfoot>
                                        <tr class="font-weight-bold">
                                            <td colspan="4" class="text-right">Total:</td>
                                            <td class="text-right">Rp ${new Intl.NumberFormat('id-ID').format(data.detail_barang_keluar.reduce((sum, detail) => sum + parseFloat(detail.total), 0))}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>`;

                        $('#detail-content').html(html);
                        $('#detailModal').modal('show');
                    } else {
                        toastr.error('Gagal memuat detail');
                    }
                },
                error: function() {
                    toastr.error('Terjadi kesalahan saat memuat detail');
                }
            });
        }
    </script>
@endsection
