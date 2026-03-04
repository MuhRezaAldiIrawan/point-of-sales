@extends('layouts.main')

@section('content')
    <section id="bootstrap3">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            <i class="la la-exclamation-triangle text-warning"></i> {{ $title }}
                        </h4>
                        <div class="heading-elements">
                            <div class="btn-group">
                                <button type="button" class="btn btn-warning btn-sm" onclick="refreshTable()">
                                    <i class="la la-refresh"></i> Refresh
                                </button>
                            </div>
                        </div>
                        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body card-dashboard">
                            <!-- Alert Section -->
                            <div class="alert alert-warning alert-dismissible fade show mb-3" role="alert">
                                <h4 class="alert-heading">
                                    <i class="la la-warning"></i> Peringatan Stok Minim!
                                </h4>
                                <p class="mb-2">Tabel ini menampilkan barang dengan stok ≤ 10 unit yang memerlukan perhatian khusus untuk restocking.</p>
                                <hr>
                                <div class="mb-0">
                                    <span class="badge badge-danger mr-1">Stok Kritis ≤ 5</span>
                                    <span class="badge badge-warning mr-1">Stok Habis = 0</span>
                                    <span class="badge badge-dark mr-1">Stok Minus < 0</span>
                                </div>
                            </div>

                            <table class="table table-striped table-hover table-bordered" id="stokminim-table">
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
                                        <th class="text-center">Status</th>
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
            text-align: center;
        }
        .text-success { color: #28a745 !important; }
        .text-danger { color: #dc3545 !important; }
        .text-warning { color: #ffc107 !important; }
        .text-primary { color: #007bff !important; }

        /* Alert Styling for Stok Minim */
        .alert-warning {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border-left: 4px solid #ffc107;
            border-color: #ffeaa7;
        }

        /* Stok Minim specific styling */
        .alert-heading {
            color: #856404;
            font-weight: 600;
        }

        .badge-danger {
            background-color: #dc3545;
        }

        .badge-warning {
            background-color: #ffc107;
            color: #212529;
        }

        .badge-dark {
            background-color: #343a40;
        }

        /* DataTables custom styling */
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.25rem 0.5rem;
            margin: 0 2px;
            border-radius: 3px;
        }

        .dataTables_wrapper .dataTables_info {
            padding-top: 0.5rem;
        }
    </style>
@endsection

@section('js')
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/datatables.min.js') }}" type="text/javascript"></script>

    <script>
        var table;

        $(document).ready(function() {
            initializeDataTable();
        });

        function initializeDataTable() {
            table = $('#stokminim-table').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('stokminim.index') }}",
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
                        name: 'ppn_badge',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'qty_formatted',
                        name: 'qty',
                        className: 'text-center font-weight-bold'
                    },
                    {
                        data: 'qty_in_formatted',
                        name: 'qty_in',
                        className: 'text-center'
                    },
                    {
                        data: 'qty_out_formatted',
                        name: 'qty_out',
                        className: 'text-center'
                    },
                    {
                        data: 'status_alert',
                        name: 'status_alert',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ],
                "order": [[6, 'asc']], // Order by QTY ascending (stok terkecil dulu)
                "language": {
                    "processing": "Memproses...",
                    "lengthMenu": "Tampilkan _MENU_ entri",
                    "zeroRecords": '<div class="text-center p-3"><i class="la la-check-circle text-success" style="font-size: 3em;"></i><h5 class="mt-2 text-success">Tidak ada stok minim!</h5><p class="text-muted">Semua barang memiliki stok yang aman (> 10 unit)</p></div>',
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
                    "infoFiltered": "(disaring dari _MAX_ total entri)",
                    "search": "Cari:",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    },
                    "emptyTable": '<div class="text-center p-3"><i class="la la-database text-info" style="font-size: 3em;"></i><h5 class="mt-2">Belum ada data barang</h5><p class="text-muted">Silakan tambahkan data barang terlebih dahulu</p></div>'
                },
                "pageLength": 15,
                "lengthMenu": [[10, 15, 25, 50, -1], [10, 15, 25, 50, "Semua"]],
                "responsive": true,
                "scrollX": true,
                "dom": 'Bfrtip',
                "buttons": [
                    {
                        extend: 'excel',
                        text: '<i class="la la-file-excel-o"></i> Excel',
                        className: 'btn btn-success btn-sm'
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="la la-file-pdf-o"></i> PDF',
                        className: 'btn btn-danger btn-sm'
                    },
                    {
                        extend: 'print',
                        text: '<i class="la la-print"></i> Print',
                        className: 'btn btn-info btn-sm'
                    }
                ]
            });
        }

        function refreshTable() {
            if (table) {
                table.ajax.reload(null, false);

                // Show success message
                toastr.success('Data berhasil diperbarui', 'Refresh Berhasil', {
                    timeOut: 2000,
                    preventDuplicates: true
                });
            }
        }

        // Auto refresh every 5 minutes
        setInterval(function() {
            if (table) {
                table.ajax.reload(null, false);
            }
        }, 300000); // 5 minutes
    </script>
@endsection
