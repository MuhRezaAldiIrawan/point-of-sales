@extends('layouts.main')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/tables/datatable/datatables.min.css') }}">
    <style>
        .badge {
            font-size: 0.75em;
            padding: 0.25em 0.5em;
        }
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
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
        .text-success { color: #28a745 !important; }
        .text-danger { color: #dc3545 !important; }
        .text-warning { color: #ffc107 !important; }
        .text-primary { color: #007bff !important; }
        .filter-select {
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        .filter-select:focus {
            border-color: #80bdff;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        .filter-section {
            display: flex;
            align-items: center;
            background: #f8f9fa;
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid #e9ecef;
        }
        .filter-info {
            font-weight: 500;
            color: #6c757d;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            border: none;
            font-size: 0.8rem;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .filter-info i {
            font-size: 0.9rem;
        }
        .gap-2 {
            gap: 0.5rem;
        }
    </style>
@endsection

@section('content')
    <section id="bootstrap3">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <h4 class="card-title mb-0">{{ $title }}</h4>
                            <div class="d-flex align-items-center gap-2">
                                <div class="filter-section">
                                    <label for="filter-stok" class="mb-0 mr-2" style="font-weight: 500; color: #495057;">Filter:</label>
                                    <select class="form-control form-control-sm filter-select" id="filter-stok" style="width: 160px;">
                                        <option value="all">📦 Semua Barang</option>
                                        <option value="minim">⚠️ Stok Minim (≤10)</option>
                                    </select>
                                </div>
                                <button type="button" class="btn btn-info btn-sm" onclick="refreshTable()" title="Refresh Data">
                                    <i class="la la-refresh"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mt-2">
                            <small class="filter-info" id="filter-info">
                                <i class="la la-info-circle"></i> Menampilkan: Semua Barang
                            </small>
                        </div>
                        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body card-dashboard">
                            <table class="table table-striped table-hover table-bordered" id="stok-table">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Kode</th>
                                        <th class="text-center">Nama Barang</th>
                                        <th class="text-center">Merek</th>
                                        <th class="text-center">Jenis</th>
                                        <th class="text-center">PPN</th>
                                        <th class="text-center">QTY</th>
                                        <th class="text-center">QTY In</th>
                                        <th class="text-center">QTY Out</th>
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
    </section>
@endsection

@section('js')
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/datatables.min.js') }}" type="text/javascript"></script>

    <script>
        var table;

        $(document).ready(function() {
            initializeDataTable();
            setupFilterEvents();
        });

        function setupFilterEvents() {
            $('#filter-stok').on('change', function() {
                updateFilterInfo();
                table.ajax.reload();
            });
        }

        function updateFilterInfo() {
            const filterValue = $('#filter-stok').val();
            const filterText = filterValue === 'minim' ? 'Stok Minim (≤10)' : 'Semua Barang';
            $('#filter-info').html('<i class="la la-info-circle"></i> Menampilkan: ' + filterText);
        }

        function initializeDataTable() {
            table = $('#stok-table').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('stokbarang.index') }}",
                    "data": function (d) {
                        d.filter = $('#filter-stok').val();
                    }
                },
                "columns": [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'kode',
                        name: 'kode',
                        className: 'text-center'
                    },
                    {
                        data: 'nama_barang',
                        name: 'nama_barang'
                    },
                    {
                        data: 'merek',
                        name: 'merek',
                        className: 'text-center'
                    },
                    {
                        data: 'jenis',
                        name: 'jenis',
                        className: 'text-center'
                    },
                    {
                        data: 'ppn_badge',
                        name: 'ppn',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'qty_formatted',
                        name: 'qty',
                        className: 'text-center',
                        orderable: true
                    },
                    {
                        data: 'qty_in_formatted',
                        name: 'qty_in',
                        className: 'text-center',
                        orderable: true
                    },
                    {
                        data: 'qty_out_formatted',
                        name: 'qty_out',
                        className: 'text-center',
                        orderable: true
                    }
                ],
                "language": {
                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                    "zeroRecords": "Data tidak ditemukan",
                    "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                    "infoEmpty": "Tidak ada data yang tersedia",
                    "infoFiltered": "(difilter dari _MAX_ total data)",
                    "search": "Cari:",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    },
                    "processing": "Memproses..."
                },
                "pageLength": 25,
                "order": [[6, "desc"]],
                "scrollX": true,
                "columnDefs": [
                    {
                        "width": "5%",
                        "targets": 0
                    },
                    {
                        "width": "10%",
                        "targets": [1, 3, 4, 5, 6, 7, 8]
                    }
                ]
            });
        }

        function refreshTable() {
            if (table) {
                table.ajax.reload(null, false);
                updateFilterInfo();

                const filterValue = $('#filter-stok').val();
                const filterText = filterValue === 'minim' ? 'Stok Minim (≤10)' : 'Semua Barang';

                Swal.fire({
                    icon: 'success',
                    title: 'Data Diperbarui!',
                    text: `Stok barang berhasil diperbarui - Filter: ${filterText}`,
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        }
    </script>
@endsection
