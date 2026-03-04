@extends('layouts.main')

@section('css')
    <style>
        .invoice-card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .invoice-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2.5rem 2rem;
            border-radius: 8px 8px 0 0;
        }

        .invoice-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            letter-spacing: 2px;
        }

        .invoice-number {
            font-size: 1.3rem;
            opacity: 0.95;
            font-weight: 500;
        }

        .info-section {
            padding: 2rem;
            border-bottom: 1px solid #e9ecef;
        }

        .info-label {
            font-weight: 700;
            color: #667eea;
            font-size: 0.75rem;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-size: 1.1rem;
            color: #2c2c2c;
            font-weight: 600;
        }

        .items-table {
            margin: 0;
        }

        .items-table thead th {
            background: royalblue;
            font-weight: 700;
            font-size: 0.875rem;
            color: white;
            border: none;
            padding: 1rem 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .items-table tbody td {
            padding: 1rem 0.75rem;
            vertical-align: middle;
            font-size: 0.9rem;
            border-bottom: 1px solid #f1f1f1;
        }

        .items-table tbody tr:last-child td {
            border-bottom: 2px solid #dee2e6;
        }

        .summary-section {
            padding: 1.5rem;
            border-top: 1px solid #e9ecef;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            font-size: 1rem;
            border-bottom: 1px solid #f1f1f1;
        }

        .summary-row.total-row {
            border-top: 2px solid #667eea;
            border-bottom: 2px solid #667eea;
            margin-top: 1rem;
            padding: 1.25rem 0;
            font-size: 1.5rem;
            font-weight: 700;
            color: #667eea;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
        }

        .summary-label {
            font-weight: 600;
            color: #495057;
            font-size: 1rem;
        }

        .summary-value {
            font-weight: 700;
            color: #2c2c2c;
            font-size: 1rem;
        }

        .summary-divider {
            border-top: 2px solid #dee2e6;
            margin: 1rem 0;
        }

        .badge-custom {
            padding: 0.6rem 1.5rem;
            font-size: 1rem;
            border-radius: 50px;
            font-weight: 700;
            letter-spacing: 1px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        }

        .badge-lunas {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
        }

        .badge-belum-lunas {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }

        .btn-action {
            padding: 0.75rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-print {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
        }

        .btn-print:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-back {
            background: linear-gradient(135deg, #757f9a 0%, #d7dde8 100%);
            color: white;
            border: none;
        }

        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(117, 127, 154, 0.3);
            color: white;
        }

        .alert-custom {
            border-radius: 8px;
            border: none;
            padding: 1rem 1.5rem;
        }

        @media print {
            .btn-action,
            .card-header,
            .no-print {
                display: none !important;
            }

            .invoice-card {
                box-shadow: none;
            }

            body {
                background: white !important;
            }
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card invoice-card">
                <div class="card-header no-print">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Detail Penjualan</h4>
                        <div>
                            <a href="{{ route('penjualan.print', $penjualan->id) }}" target="_blank" class="btn btn-print btn-action mr-2">
                                <i class="ft-printer"></i> Cetak Struk
                            </a>
                            <a href="{{ route('penjualan.index') }}" class="btn btn-back btn-action">
                                <i class="ft-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>

                <div class="invoice-header">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="invoice-title">INVOICE</div>
                            <div class="invoice-number">{{ $penjualan->no_invoice }}</div>
                        </div>
                        <div class="col-md-6 text-right">
                            <span class="badge-custom {{ $penjualan->status === 'Lunas' ? 'badge-lunas' : 'badge-belum-lunas' }}">
                                {{ $penjualan->status }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="info-section">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="info-label">TANGGAL TRANSAKSI</div>
                            <div class="info-value">{{ $penjualan->tanggal_penjualan->format('d F Y') }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-label">METODE PEMBAYARAN</div>
                            <div class="info-value">{{ $penjualan->payment_method }}</div>
                        </div>
                        @if($penjualan->jatuh_tempo)
                        <div class="col-md-4">
                            <div class="info-label">JATUH TEMPO</div>
                            <div class="info-value text-danger">{{ $penjualan->jatuh_tempo->format('d F Y') }}</div>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="info-section">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-label">PELANGGAN</div>
                            <div class="info-value">{{ $penjualan->pelanggan->nama }}</div>
                            @if($penjualan->pelanggan->alamat)
                                <small class="text-muted">{{ $penjualan->pelanggan->alamat }}</small><br>
                            @endif
                            @if($penjualan->pelanggan->no_hp)
                                <small class="text-muted">Telp: {{ $penjualan->pelanggan->no_hp }}</small>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <div class="info-label">DIBUAT OLEH</div>
                            <div class="info-value">{{ $penjualan->createdBy->nama_depan }} {{ $penjualan->createdBy->nama_belakang ?? '' }}</div>
                            <small class="text-muted">{{ $penjualan->created_at->format('d F Y, H:i') }} WIB</small>
                        </div>
                    </div>
                </div>

                <div class="info-section">
                    <h5 class="mb-4" style="font-weight: 700; color: #2c2c2c;">
                        <i class="ft-shopping-cart" style="color: #667eea;"></i> Detail Item Penjualan
                    </h5>
                    <div class="table-responsive">
                        <table class="table table-bordered items-table">
                            <thead>
                                <tr>
                                    <th width="5%" class="text-center">No</th>
                                    <th width="35%">Nama Barang</th>
                                    <th width="10%" class="text-center">Jumlah</th>
                                    <th width="10%" class="text-center">Bonus</th>
                                    <th width="15%" class="text-right">Harga Satuan</th>
                                    <th width="10%" class="text-center">Diskon</th>
                                    <th width="15%" class="text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($penjualan->detailPenjualans as $index => $detail)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $detail->barang->nama_barang }}</strong>
                                    </td>
                                    <td class="text-center"><strong>{{ $detail->jumlah }}</strong></td>
                                    <td class="text-center">{{ $detail->bonus ?? 0 }}</td>
                                    <td class="text-right">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                    <td class="text-center">{{ $detail->diskon_item ?? 0 }}%</td>
                                    <td class="text-right"><strong>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</strong></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="summary-section">
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="mb-4" style="font-weight: 700; color: #2c2c2c;">
                                <i class="ft-file-text" style="color: #667eea;"></i> Ringkasan Pembayaran
                            </h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            @if($penjualan->catatan)
                            <div class="alert alert-custom alert-info">
                                <strong><i class="ft-info"></i> Catatan:</strong><br>
                                {{ $penjualan->catatan }}
                            </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            @php
                                // Calculate subtotal from items (after item discount, before total discount)
                                $subtotalItems = 0;
                                foreach($penjualan->detailPenjualans as $detail) {
                                    $subtotalItems += $detail->subtotal;
                                }

                                // Calculate total discount amount from subtotal
                                $diskonAmount = $subtotalItems * ($penjualan->diskon / 100);

                                // Subtotal after total discount
                                $subtotalAfterDiskon = $subtotalItems - $diskonAmount;
                            @endphp

                            <div class="summary-row">
                                <span class="summary-label">Subtotal:</span>
                                <span class="summary-value">Rp {{ number_format($subtotalItems, 0, ',', '.') }}</span>
                            </div>
                            <div class="summary-row">
                                <span class="summary-label">Diskon:</span>
                                <span class="summary-value">Rp {{ number_format($diskonAmount, 0, ',', '.') }}</span>
                            </div>
                            <div class="summary-row">
                                <span class="summary-label">PPN ({{ $setting->ppn ?? 11 }}%):</span>
                                <span class="summary-value">Rp {{ number_format($penjualan->ppn_amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="summary-row">
                                <span class="summary-label">Biaya Kirim:</span>
                                <span class="summary-value">Rp {{ number_format($penjualan->biaya_kirim, 0, ',', '.') }}</span>
                            </div>
                            <div class="summary-row">
                                <span class="summary-label">Biaya Lain:</span>
                                <span class="summary-value">Rp {{ number_format($penjualan->biaya_lain, 0, ',', '.') }}</span>
                            </div>
                            <div class="summary-row total-row">
                                <span class="summary-label">GRAND TOTAL:</span>
                                <span class="summary-value">Rp {{ number_format($penjualan->grand_total, 0, ',', '.') }}</span>
                            </div>
                            <div class="summary-divider"></div>
                            <div class="summary-row">
                                <span class="summary-label">Jumlah Bayar:</span>
                                <span class="summary-value">Rp {{ number_format($penjualan->bayar, 0, ',', '.') }}</span>
                            </div>
                            <div class="summary-row">
                                <span class="summary-label">Kembalian:</span>
                                <span class="summary-value">Rp {{ number_format($penjualan->kembalian, 0, ',', '.') }}</span>
                            </div>
                            <div class="summary-row">
                                <span class="summary-label">Sisa:</span>
                                <span class="summary-value {{ $penjualan->sisa > 0 ? 'text-warning' : '' }}">Rp {{ number_format($penjualan->sisa, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
@endsection
