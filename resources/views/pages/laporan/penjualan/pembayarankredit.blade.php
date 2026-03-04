@extends('layouts.main')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/tables/datatable/datatables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <style>
        .card {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 1rem;
        }

        .card-header {
            border-bottom: 2px solid #f1f1f1;
        }

        #pembayaran-kredit-table {
            font-size: 0.9rem;
            width: 100% !important;
        }

        #pembayaran-kredit-table thead th {
            font-weight: 600;
            white-space: nowrap;
            font-size: 0.85rem;
        }

        #pembayaran-kredit-table tbody td {
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
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
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
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
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

        .modal {
            z-index: 9999 !important;
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            overflow: hidden;
        }

        .modal-backdrop {
            z-index: 9998 !important;
        }

        .modal.show {
            display: flex !important;
            align-items: center;
            justify-content: center;
        }

        .modal-dialog {
            margin: 1rem auto;
            max-width: 1100px;
            width: 90%;
            position: relative;
            z-index: 10000;
        }

        .modal-xl {
            max-width: 1100px;
        }

        .modal-content {
            border-radius: 8px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            border: none;
            position: relative;
        }

        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 8px 8px 0 0;
            padding: 1rem 1.5rem;
            border-bottom: none;
        }

        .modal-header .close {
            color: white;
            opacity: 0.9;
            text-shadow: none;
            padding: 0;
            margin: -0.5rem -0.5rem -0.5rem auto;
        }

        .modal-header .close:hover {
            opacity: 1;
        }

        .modal-body {
            padding: 1.5rem;
            max-height: 70vh;
            overflow-y: auto;
            background: white;
        }

        .modal-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid #dee2e6;
            background: #f8f9fa;
            border-radius: 0 0 8px 8px;
        }

        @media (max-width: 1200px) {
            .modal-dialog {
                width: 95%;
                max-width: 95%;
            }
        }

        @media (max-width: 768px) {
            #pembayaran-kredit-table {
                font-size: 0.8rem;
            }

            .btn {
                padding: 0.375rem 0.75rem;
                font-size: 0.875rem;
            }

            .modal-dialog {
                width: 98%;
                max-width: 98%;
                margin: 0.5rem auto;
            }

            .modal-body {
                padding: 1rem;
                max-height: 65vh;
            }

            .modal-header {
                padding: 0.75rem 1rem;
            }

            .modal-footer {
                padding: 0.75rem 1rem;
            }
        }
    </style>
@endsection

@section('content')
<section id="bootstrap3">
	<div class="row">
		<div class="col-12">
			<div class="filter-card">
				<div class="filter-header">
					<h5>
						<i class="ft-filter"></i>
						Filter Pembayaran Kredit
					</h5>
				</div>
				<div class="filter-body">
					<form id="filterForm">
						<div class="row">
							<div class="col-md-4">
								<div class="filter-group">
									<label for="filter_date">
										<i class="ft-calendar"></i>
										Periode Tanggal
									</label>
									<input type="text" class="form-control filter-input" id="filter_date" name="filter_date" placeholder="Pilih rentang tanggal">
								</div>
							</div>
							<div class="col-md-3">
								<div class="filter-group">
									<label for="filter_status">
										<i class="ft-info"></i>
										Status
									</label>
									<select class="form-control filter-input" id="filter_status" name="filter_status">
										<option value="">Semua Status</option>
										<option value="Lunas">Lunas</option>
										<option value="Belum Lunas">Belum Lunas</option>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="filter-group">
									<label for="filter_pelanggan">
										<i class="ft-user"></i>
										Nama Pelanggan
									</label>
									<input type="text" class="form-control filter-input" id="filter_pelanggan" name="filter_pelanggan" placeholder="Cari nama pelanggan...">
								</div>
							</div>
							<div class="col-md-2">
								<div class="filter-group">
									<label>&nbsp;</label>
									<button type="button" class="btn btn-primary btn-filter btn-block" id="applyFilter">
										<i class="ft-search"></i> Filter
									</button>
									<button type="button" class="btn btn-secondary btn-filter btn-block mt-1" id="resetFilter">
										<i class="ft-refresh-cw"></i> Reset
									</button>
								</div>
							</div>
						</div>

						<div id="filterStatus" style="display: none;">
							<div class="filter-badge">
								<span id="filterStatusText"></span>
								<span class="badge-close" onclick="resetFilterStatus()">&times;</span>
							</div>
						</div>
					</form>
				</div>
			</div>

			<div class="card">
				<div class="card-header">
					<h4 class="card-title">
						<i class="ft-file-text"></i> Daftar Pembayaran Kredit
					</h4>
				</div>
				<div class="card-content collapse show">
					<div class="card-body card-dashboard">
						<div class="table-responsive">
							<table class="table table-striped table-bordered" id="pembayaran-kredit-table" style="width: 100%;">
								<thead class="bg-primary text-white">
									<tr>
										<th width="5%">No</th>
										<th>No Invoice</th>
										<th>Tanggal</th>
										<th>Jatuh Tempo</th>
										<th>Pelanggan</th>
										<th>Grand Total</th>
										<th>Terbayar</th>
										<th>Sisa</th>
										<th>Status</th>
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
	</div>
</section>
@endsection

<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">
                    <i class="ft-file-text"></i> Detail Pembayaran Kredit
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="detailModalContent">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="mt-2">Memuat data...</p>
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

@section('js')
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/datatables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('app-assets/js/scripts/tables/datatables/datatable-basic.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        $(document).ready(function() {
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
                                 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
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

            $('#filter_date').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
            });

            $('#filter_date').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });

            var table = $('#pembayaran-kredit-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: '{{ route("pembayarankredit.index") }}',
                    type: 'GET',
                    data: function(d) {
                        d.date_range = $('#filter_date').val();
                        d.status = $('#filter_status').val();
                        d.pelanggan = $('#filter_pelanggan').val();
                    },
                    error: function(xhr, error, thrown) {
                        console.error('DataTables Error:', error, thrown);
                        alert('Terjadi kesalahan saat memuat data. Silakan refresh halaman.');
                    }
                },
                columns: [
                    {
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
                        data: 'jatuh_tempo',
                        name: 'jatuh_tempo',
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
                        data: 'bayar',
                        name: 'bayar',
                        className: 'text-right',
                        render: function(data) {
                            return 'Rp ' + parseFloat(data).toLocaleString('id-ID');
                        }
                    },
                    {
                        data: 'sisa',
                        name: 'sisa',
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
                                <button class="btn btn-info btn-sm view-btn" data-id="${row.id}" data-toggle="tooltip" title="Lihat Detail">
                                    <i class="la la-eye"></i> Detail
                                </button>
                            `;
                        }
                    }
                ],
                order: [[2, 'desc']],
                pageLength: 25,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json',
                    processing: '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>'
                },
                drawCallback: function() {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });

            $('#applyFilter').on('click', function() {
                table.ajax.reload();
                updateFilterStatus();
            });

            $('#resetFilter').on('click', function() {
                $('#filter_date').val('');
                $('#filter_status').val('');
                $('#filter_pelanggan').val('');
                table.ajax.reload();
                $('#filterStatus').hide();
            });

            $('#filter_pelanggan').on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    $('#applyFilter').click();
                }
            });

            function updateFilterStatus() {
                let filters = [];

                if ($('#filter_date').val()) {
                    filters.push('Tanggal: ' + $('#filter_date').val());
                }
                if ($('#filter_status').val()) {
                    filters.push('Status: ' + $('#filter_status').val());
                }
                if ($('#filter_pelanggan').val()) {
                    filters.push('Pelanggan: ' + $('#filter_pelanggan').val());
                }

                if (filters.length > 0) {
                    $('#filterStatusText').text(filters.join(' | '));
                    $('#filterStatus').slideDown();
                } else {
                    $('#filterStatus').slideUp();
                }
            }

            window.resetFilterStatus = function() {
                $('#filter_date').val('');
                $('#filter_status').val('');
                $('#filter_pelanggan').val('');
                table.ajax.reload();
                $('#filterStatus').slideUp();
            };

            $('#pembayaran-kredit-table').on('click', '.view-btn', function() {
                const id = $(this).data('id');

                $('#detailModal').modal({
                    backdrop: 'static',
                    keyboard: true,
                    show: true
                });

                $('#detailModalContent').html(`
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <p class="mt-2">Memuat data...</p>
                    </div>
                `);

                $.ajax({
                    url: `/laporan-pembayaran-kredit/${id}`,
                    type: 'GET',
                    success: function(response) {
                        $('#detailModalContent').html(response);
                    },
                    error: function(xhr) {
                        $('#detailModalContent').html(`
                            <div class="alert alert-danger">
                                <strong>Error!</strong> Gagal memuat detail pembayaran.
                            </div>
                        `);
                    }
                });
            });

            $('#detailModal').on('show.bs.modal', function () {
                $('body').addClass('modal-open');
            });

            $('#detailModal').on('hidden.bs.modal', function () {
                $('body').removeClass('modal-open');
            });
        });
    </script>
@endsection
