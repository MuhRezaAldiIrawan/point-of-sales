@extends('layouts.main')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/tables/datatable/datatables.min.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Custom SweetAlert2 styling for detail modal */
        .swal-wide {
            width: 95% !important;
            max-width: 1200px !important;
        }

        .swal2-popup.swal-wide .swal2-content {
            max-height: 70vh;
            overflow-y: auto;
        }

        /* Table styling in modal */
        .table-sm th, .table-sm td {
            padding: 0.5rem;
            font-size: 0.875rem;
        }

        .badge {
            font-size: 0.75em;
            padding: 0.25em 0.5em;
        }

        /* Responsive table in modal */
        @media (max-width: 768px) {
            .swal-wide {
                width: 98% !important;
                margin: 1% !important;
            }

            .table-sm th, .table-sm td {
                padding: 0.25rem;
                font-size: 0.75rem;
            }
        }
    </style>
@endsection
@section('content')
    <section id="bootstrap3">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Daftar {{ $title }}</h4>
                        <div class="heading-elements">
                            <a href="{{ route('barangmasuk.create') }}" class="btn btn-primary btn-md d-flex align-items-center">
                                <i class="la la-plus mr-1"></i> Tambah {{ $title }}
                            </a>
                        </div>
                        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body card-dashboard">

                            <table class="table table-striped table-bordered" id="barangmasuk-table">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">No. REFF</th>
                                        <th class="text-center">Tanggal</th>
                                        <th class="text-center">Jenis Stok</th>
                                        <th class="text-center">Jumlah</th>
                                        <th class="text-center">Total</th>
                                        <th class="text-center">Catatan</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
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
    <script src="{{ asset('app-assets/js/scripts/tables/datatables/datatable-basic.min.js') }}" type="text/javascript">
    </script>

    <script>
        var table;

        $(document).ready(function() {
            table = $('#barangmasuk-table').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('barangmasuk.index') }}",
                "columns": [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'no_reff',
                        name: 'no_reff'
                    },
                    {
                        data: 'tanggal_masuk',
                        name: 'tanggal_masuk'
                    },
                    {
                        data: 'jenis_stok',
                        name: 'jenis_stok'
                    },
                    {
                        data: 'jumlah',
                        name: 'jumlah'
                    },
                    {
                        data: 'total',
                        name: 'total'
                    },
                    {
                        data: 'catatan',
                        name: 'catatan'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
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
                    }
                },
                "pageLength": 10,
                "order": [
                    [0, "desc"]
                ],
                "scrollX": true,
                "columnDefs": [{
                    "width": "5%",
                    "targets": 0
                }]
            });

            // Handle detail button click
            $('#barangmasuk-table').on('click', '.btn-barangmasuk-detail', function() {
                var id = $(this).data('id');

                Swal.fire({
                    title: '📋 Loading Detail...',
                    html: `
                        <div class="d-flex justify-content-center align-items-center">
                            <div class="spinner-border text-info mr-3" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <span>Memuat detail barang masuk...</span>
                        </div>
                    `,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // AJAX call untuk get detail
                $.ajax({
                    url: `/barang-masuk/${id}`,
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function(response) {
                        if (response.success) {
                            const data = response.data;

                            // Format detail items untuk ditampilkan
                            let detailItemsHtml = '';
                            if (data.details.length > 0) {
                                data.details.forEach(function(detail, index) {
                                    const ppnBadge = detail.stok_ppn === 'PPN'
                                        ? '<span class="badge badge-success">PPN</span>'
                                        : '<span class="badge badge-secondary">Non PPN</span>';

                                    detailItemsHtml += `
                                        <tr>
                                            <td class="text-center">${index + 1}</td>
                                            <td>${detail.kode_barang}</td>
                                            <td>${detail.nama_barang}</td>
                                            <td class="text-center">${ppnBadge}</td>
                                            <td class="text-center">${detail.satuan}</td>
                                            <td class="text-center">${parseInt(detail.isi).toLocaleString('id-ID')}</td>
                                            <td class="text-center">${parseInt(detail.jumlah).toLocaleString('id-ID')}</td>
                                            <td class="text-right">Rp ${parseInt(detail.harga_beli).toLocaleString('id-ID')}</td>
                                            <td class="text-right">Rp ${parseInt(detail.total).toLocaleString('id-ID')}</td>
                                            <td>${detail.keterangan}</td>
                                        </tr>
                                    `;
                                });
                            } else {
                                detailItemsHtml = '<tr><td colspan="10" class="text-center text-muted">Tidak ada detail barang</td></tr>';
                            }

                            // Show detail modal
                            Swal.fire({
                                title: `📋 Detail Barang Masuk - ${data.no_reff}`,
                                html: `
                                    <div class="text-left">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <strong>📅 Tanggal:</strong> ${data.tanggal_masuk}<br>
                                                <strong>📦 Jenis Stok:</strong> ${data.jenis_stok}<br>
                                                <strong>📝 Catatan:</strong> ${data.catatan}
                                            </div>
                                            <div class="col-md-6">
                                                <strong>📊 Total Item:</strong> ${data.total_items}<br>
                                                <strong>🔢 Total Jumlah:</strong> ${parseInt(data.total_jumlah).toLocaleString('id-ID')}<br>
                                                <strong>💰 Grand Total:</strong> Rp ${parseInt(data.grand_total).toLocaleString('id-ID')}
                                            </div>
                                        </div>

                                        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                            <table class="table table-striped table-bordered table-sm">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th class="text-center">No</th>
                                                        <th>Kode</th>
                                                        <th>Nama Barang</th>
                                                        <th class="text-center">PPN</th>
                                                        <th class="text-center">Satuan</th>
                                                        <th class="text-center">Isi</th>
                                                        <th class="text-center">Jumlah</th>
                                                        <th class="text-center">Harga Beli</th>
                                                        <th class="text-center">Total</th>
                                                        <th>Keterangan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    ${detailItemsHtml}
                                                </tbody>
                                                ${data.details.length > 0 ? `
                                                    <tfoot>
                                                        <tr class="table-info">
                                                            <th colspan="6" class="text-right">TOTAL:</th>
                                                            <th class="text-center">${parseInt(data.total_jumlah).toLocaleString('id-ID')}</th>
                                                            <th></th>
                                                            <th class="text-right">Rp ${parseInt(data.grand_total).toLocaleString('id-ID')}</th>
                                                            <th></th>
                                                        </tr>
                                                    </tfoot>
                                                ` : ''}
                                            </table>
                                        </div>

                                        <hr>
                                        <small class="text-muted">
                                            <strong>Dibuat:</strong> ${data.created_by} - ${data.created_at}<br>
                                            <strong>Diperbarui:</strong> ${data.updated_by} - ${data.updated_at}
                                        </small>
                                    </div>
                                `,
                                width: '90%',
                                confirmButtonText: '✅ Tutup',
                                confirmButtonColor: '#007bff',
                                showCancelButton: true,
                                cancelButtonText: '📝 Edit',
                                cancelButtonColor: '#ffc107',
                                reverseButtons: true,
                                customClass: {
                                    popup: 'swal-wide',
                                    content: 'text-left'
                                }
                            }).then((result) => {
                                if (result.dismiss === Swal.DismissReason.cancel) {
                                    // Redirect to edit page
                                    window.location.href = `/barang-masuk/${id}/edit`;
                                }
                            });
                        } else {
                            Swal.fire('❌ Error', response.message || 'Gagal memuat detail', 'error');
                        }
                    },
                    error: function(xhr) {
                        console.error('AJAX Error:', xhr.responseJSON);

                        let errorMessage = 'Terjadi kesalahan saat memuat detail';
                        if (xhr.status === 404) {
                            errorMessage = 'Data barang masuk tidak ditemukan';
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        Swal.fire('❌ Error', errorMessage, 'error');
                    }
                });
            });

            // Handle edit button click
            $('#barangmasuk-table').on('click', '.btn-barangmasuk-edit', function() {
                var id = $(this).data('id');
                window.location.href = `/barang-masuk/${id}/edit`;
            });

            // Handle delete button click
            $('#barangmasuk-table').on('click', '.btn-barangmasuk-delete', function() {
                var id = $(this).data('id');

                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: 'Yakin ingin menghapus data barang masuk ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // AJAX call untuk delete
                        $.ajax({
                            url: `/barang-masuk/${id}`,
                            method: 'DELETE',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire('Berhasil!', response.message, 'success');
                                    table.ajax.reload();
                                } else {
                                    Swal.fire('Error!', response.message, 'error');
                                }
                            },
                            error: function() {
                                Swal.fire('Error!', 'Terjadi kesalahan saat menghapus data', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
