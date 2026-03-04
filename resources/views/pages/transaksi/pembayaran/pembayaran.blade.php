@extends('layouts.main')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/tables/datatable/datatables.min.css') }}">
@endsection

@section('content')
<section id="configuration">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">List Pembayaran Penjualan</h4>
                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered zero-configuration" id="pembayaranTable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No Invoice</th>
                                        <th>Tanggal</th>
                                        <th>Pelanggan</th>
                                        <th>Bentuk Pembayaran</th>
                                        <th>Grand Total</th>
                                        <th>Bayar</th>
                                        <th>Sisa</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pembayarans as $index => $pembayaran)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $pembayaran->no_invoice }}</td>
                                        <td>{{ $pembayaran->tanggal_penjualan->format('d/m/Y') }}</td>
                                        <td>{{ $pembayaran->pelanggan->nama ?? '-' }}</td>
                                        <td>
                                            @if($pembayaran->payment_method == 'Cash')
                                                <span class="badge badge-success">Cash</span>
                                            @else
                                                <span class="badge badge-warning">Credit</span>
                                            @endif
                                        </td>
                                        <td class="text-right">Rp {{ number_format($pembayaran->grand_total, 0, ',', '.') }}</td>
                                        <td class="text-right">Rp {{ number_format($pembayaran->bayar, 0, ',', '.') }}</td>
                                        <td class="text-right">Rp {{ number_format($pembayaran->sisa, 0, ',', '.') }}</td>
                                        <td>
                                            @if($pembayaran->sisa <= 0 || strtolower($pembayaran->status) == 'lunas')
                                                <span class="badge badge-success">Lunas</span>
                                            @elseif(strtolower($pembayaran->status) == 'pending')
                                                <span class="badge badge-warning">Pending</span>
                                            @else
                                                <span class="badge badge-danger">Belum Lunas</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-info btn-detail" data-id="{{ $pembayaran->id }}">
                                                <i class="la la-eye"></i> Detail
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title text-white" id="detailModalLabel">Detail Pembayaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="40%"><strong>No Invoice</strong></td>
                                <td width="5%">:</td>
                                <td id="detail-invoice"></td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal</strong></td>
                                <td>:</td>
                                <td id="detail-tanggal"></td>
                            </tr>
                            <tr>
                                <td><strong>Pelanggan</strong></td>
                                <td>:</td>
                                <td id="detail-pelanggan"></td>
                            </tr>
                            <tr>
                                <td><strong>Bentuk Pembayaran</strong></td>
                                <td>:</td>
                                <td id="detail-payment"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="40%"><strong>PPN</strong></td>
                                <td width="5%">:</td>
                                <td id="detail-ppn"></td>
                            </tr>
                            <tr>
                                <td><strong>Jatuh Tempo</strong></td>
                                <td>:</td>
                                <td id="detail-jatuh-tempo"></td>
                            </tr>
                            <tr>
                                <td><strong>Status</strong></td>
                                <td>:</td>
                                <td id="detail-status"></td>
                            </tr>
                            <tr>
                                <td><strong>Dibuat Oleh</strong></td>
                                <td>:</td>
                                <td id="detail-created-by"></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <hr>

                <h5 class="mb-3">Rincian Biaya</h5>
                <table class="table table-striped">
                    <tr>
                        <td width="30%"><strong>Subtotal</strong></td>
                        <td width="5%">:</td>
                        <td class="text-right" id="detail-subtotal"></td>
                    </tr>
                    <tr>
                        <td><strong>Diskon</strong></td>
                        <td>:</td>
                        <td class="text-right" id="detail-diskon"></td>
                    </tr>
                    <tr>
                        <td><strong>PPN Amount</strong></td>
                        <td>:</td>
                        <td class="text-right" id="detail-ppn-amount"></td>
                    </tr>
                    <tr>
                        <td><strong>Total</strong></td>
                        <td>:</td>
                        <td class="text-right" id="detail-total"></td>
                    </tr>
                    <tr>
                        <td><strong>Biaya Kirim</strong></td>
                        <td>:</td>
                        <td class="text-right" id="detail-biaya-kirim"></td>
                    </tr>
                    <tr>
                        <td><strong>Biaya Lain</strong></td>
                        <td>:</td>
                        <td class="text-right" id="detail-biaya-lain"></td>
                    </tr>
                    <tr class="bg-light">
                        <td><strong>Grand Total</strong></td>
                        <td>:</td>
                        <td class="text-right"><strong id="detail-grand-total"></strong></td>
                    </tr>
                    <tr class="bg-success text-white">
                        <td><strong>Bayar</strong></td>
                        <td>:</td>
                        <td class="text-right"><strong id="detail-bayar"></strong></td>
                    </tr>
                    <tr class="bg-warning">
                        <td><strong>Sisa</strong></td>
                        <td>:</td>
                        <td class="text-right"><strong id="detail-sisa"></strong></td>
                    </tr>
                    <tr>
                        <td><strong>Kembalian</strong></td>
                        <td>:</td>
                        <td class="text-right" id="detail-kembalian"></td>
                    </tr>
                </table>

                <div class="mt-3" id="detail-catatan-section">
                    <hr>
                    <h5>Keterangan</h5>
                    <p id="detail-catatan" class="text-muted"></p>
                </div>

                <div class="mt-3">
                    <hr>
                    <h5>Detail Barang</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="bg-info text-white">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Barang</th>
                                    <th>Jumlah</th>
                                    <th>Harga Satuan</th>
                                    <th>Diskon Item</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody id="detail-barang-list">
                            </tbody>
                        </table>
                    </div>
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
<script src="{{ asset('app-assets/vendors/js/tables/datatable/datatables.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#pembayaranTable').DataTable({
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
            "order": [[2, 'desc']]
        });

        $('.btn-detail').on('click', function() {
            const id = $(this).data('id');

            $.ajax({
                url: `/pembayaran/${id}`,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#detail-invoice').text(data.no_invoice);
                    $('#detail-tanggal').text(new Date(data.tanggal_penjualan).toLocaleDateString('id-ID'));
                    $('#detail-pelanggan').text(data.pelanggan ? data.pelanggan.nama : '-');

                    const paymentBadge = data.payment_method === 'cash'
                        ? '<span class="badge badge-success">Cash</span>'
                        : '<span class="badge badge-warning">Credit</span>';
                    $('#detail-payment').html(paymentBadge);

                    $('#detail-ppn').text(data.ppn + '%');
                    $('#detail-jatuh-tempo').text(data.jatuh_tempo ? new Date(data.jatuh_tempo).toLocaleDateString('id-ID') : '-');

                    let statusBadge = '<span class="badge badge-danger">Belum Lunas</span>';
                    if (data.sisa <= 0 || data.status.toLowerCase() === 'lunas') {
                        statusBadge = '<span class="badge badge-success">Lunas</span>';
                    } else if (data.status.toLowerCase() === 'pending') {
                        statusBadge = '<span class="badge badge-warning">Pending</span>';
                    }
                    $('#detail-status').html(statusBadge);

                    $('#detail-created-by').text(data.created_by ? data.created_by.name : '-');

                    $('#detail-subtotal').text('Rp ' + parseFloat(data.total_harga).toLocaleString('id-ID'));
                    $('#detail-diskon').text('Rp ' + parseFloat(data.diskon).toLocaleString('id-ID'));
                    $('#detail-ppn-amount').text('Rp ' + parseFloat(data.ppn_amount).toLocaleString('id-ID'));
                    $('#detail-total').text('Rp ' + (parseFloat(data.total_harga) - parseFloat(data.diskon) + parseFloat(data.ppn_amount)).toLocaleString('id-ID'));
                    $('#detail-biaya-kirim').text('Rp ' + parseFloat(data.biaya_kirim).toLocaleString('id-ID'));
                    $('#detail-biaya-lain').text('Rp ' + parseFloat(data.biaya_lain).toLocaleString('id-ID'));
                    $('#detail-grand-total').text('Rp ' + parseFloat(data.grand_total).toLocaleString('id-ID'));
                    $('#detail-bayar').text('Rp ' + parseFloat(data.bayar).toLocaleString('id-ID'));
                    $('#detail-sisa').text('Rp ' + parseFloat(data.sisa).toLocaleString('id-ID'));
                    $('#detail-kembalian').text('Rp ' + parseFloat(data.kembalian).toLocaleString('id-ID'));

                    if (data.catatan) {
                        $('#detail-catatan').text(data.catatan);
                        $('#detail-catatan-section').show();
                    } else {
                        $('#detail-catatan-section').hide();
                    }

                    let barangHtml = '';
                    if (data.detail_penjualans && data.detail_penjualans.length > 0) {
                        data.detail_penjualans.forEach((item, index) => {
                            barangHtml += `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${item.barang ? item.barang.nama_barang : '-'}</td>
                                    <td>${item.jumlah}</td>
                                    <td class="text-right">Rp ${parseFloat(item.harga_satuan).toLocaleString('id-ID')}</td>
                                    <td class="text-right">Rp ${parseFloat(item.diskon_item).toLocaleString('id-ID')}</td>
                                    <td class="text-right">Rp ${parseFloat(item.subtotal).toLocaleString('id-ID')}</td>
                                </tr>
                            `;
                        });
                    } else {
                        barangHtml = '<tr><td colspan="6" class="text-center">Tidak ada data barang</td></tr>';
                    }
                    $('#detail-barang-list').html(barangHtml);

                    $('#detailModal').modal('show');
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Gagal mengambil detail pembayaran'
                    });
                }
            });
        });
    });
</script>
@endsection
