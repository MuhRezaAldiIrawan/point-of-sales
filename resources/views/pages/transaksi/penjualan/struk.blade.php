<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Penjualan - {{ $penjualan->no_invoice }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            line-height: 1.4;
            color: #000;
            background: #f5f5f5;
            padding: 20px;
        }

        .struk-container {
            max-width: 80mm;
            margin: 0 auto;
            background: white;
            padding: 10mm;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .header {
            text-align: center;
            border-bottom: 2px dashed #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .company-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .company-info {
            font-size: 11px;
            margin: 2px 0;
        }

        .invoice-info {
            margin: 10px 0;
            font-size: 11px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 3px 0;
        }

        .info-label {
            font-weight: bold;
        }

        .separator {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }

        .items-table {
            width: 100%;
            margin: 10px 0;
        }

        .item-row {
            margin: 5px 0;
        }

        .item-name {
            font-weight: bold;
            margin-bottom: 2px;
        }

        .item-detail {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
        }

        .totals {
            margin-top: 10px;
            border-top: 1px solid #000;
            padding-top: 5px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin: 3px 0;
            font-size: 11px;
        }

        .total-row.grand-total {
            font-weight: bold;
            font-size: 13px;
            border-top: 2px solid #000;
            padding-top: 5px;
            margin-top: 5px;
        }

        .payment-info {
            margin: 10px 0;
            border-top: 1px dashed #000;
            padding-top: 10px;
        }

        .footer {
            text-align: center;
            border-top: 2px dashed #000;
            padding-top: 10px;
            margin-top: 10px;
            font-size: 11px;
        }

        .footer-note {
            margin: 5px 0;
            font-style: italic;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .struk-container {
                box-shadow: none;
                max-width: 80mm;
                width: 80mm;
            }

            @page {
                size: 80mm auto;
                margin: 0;
            }
        }

        .no-print {
            text-align: center;
            margin: 20px 0;
        }

        .btn-print {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 10px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
        }

        .btn-print:hover {
            opacity: 0.9;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" class="btn-print">
            <i class="ft-printer"></i> Cetak Struk
        </button>
    </div>

    <div class="struk-container">
        <div class="header">
            <div class="company-name">{{ $setting->nama_perusahaan ?? 'TOKO' }}</div>
            @if($setting)
                @if($setting->alamat)
                    <div class="company-info">{{ $setting->alamat }}</div>
                @endif
                @if($setting->telepon_1)
                    <div class="company-info">Telp: {{ $setting->telepon_1 }}</div>
                @endif
                @if($setting->email)
                    <div class="company-info">Email: {{ $setting->email }}</div>
                @endif
            @endif
        </div>

        <div class="invoice-info">
            <div class="info-row">
                <span class="info-label">No Invoice</span>
                <span>{{ $penjualan->no_invoice }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tanggal</span>
                <span>{{ $penjualan->tanggal_penjualan->format('d/m/Y H:i') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Kasir</span>
                <span>{{ $penjualan->createdBy ? trim($penjualan->createdBy->nama_depan . ' ' . ($penjualan->createdBy->nama_belakang ?? '')) : '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Pelanggan</span>
                <span>{{ $penjualan->pelanggan->nama }}</span>
            </div>
            @if($penjualan->payment_method == 'Credit')
                <div class="info-row">
                    <span class="info-label">Metode</span>
                    <span>{{ $penjualan->payment_method }} - {{ $penjualan->jatuh_tempo ? $penjualan->jatuh_tempo->format('d/m/Y') : '-' }}</span>
                </div>
            @else
                <div class="info-row">
                    <span class="info-label">Metode</span>
                    <span>{{ $penjualan->payment_method }}</span>
                </div>
            @endif
        </div>

        <div class="separator"></div>

        <div class="items-table">
            @foreach($penjualan->detailPenjualans as $index => $detail)
                <div class="item-row">
                    <div class="item-name">{{ $detail->barang->nama_barang }}</div>
                    <div class="item-detail">
                        <span>
                            {{ $detail->jumlah }}
                            @if($detail->bonus > 0)
                                + {{ $detail->bonus }} (Bonus)
                            @endif
                            x Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}
                            @if($detail->diskon_item > 0)
                                (Disc {{ number_format($detail->diskon_item, 1) }}%)
                            @endif
                        </span>
                        <span class="bold">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="totals">
            <div class="total-row">
                <span>Subtotal</span>
                <span>Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</span>
            </div>

            @if($penjualan->diskon > 0)
                <div class="total-row">
                    <span>Diskon ({{ number_format($penjualan->diskon, 1) }}%)</span>
                    <span>Rp {{ number_format($penjualan->total_harga * ($penjualan->diskon / 100), 0, ',', '.') }}</span>
                </div>
            @endif

            @if($penjualan->ppn == 'PPN' && $penjualan->ppn_amount > 0)
                <div class="total-row">
                    <span>PPN ({{ $setting ? $setting->ppn : 11 }}%)</span>
                    <span>Rp {{ number_format($penjualan->ppn_amount, 0, ',', '.') }}</span>
                </div>
            @endif

            @if($penjualan->biaya_kirim > 0)
                <div class="total-row">
                    <span>Biaya Kirim</span>
                    <span>Rp {{ number_format($penjualan->biaya_kirim, 0, ',', '.') }}</span>
                </div>
            @endif

            @if($penjualan->biaya_lain > 0)
                <div class="total-row">
                    <span>Biaya Lain</span>
                    <span>Rp {{ number_format($penjualan->biaya_lain, 0, ',', '.') }}</span>
                </div>
            @endif

            <div class="total-row grand-total">
                <span>GRAND TOTAL</span>
                <span>Rp {{ number_format($penjualan->grand_total, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="payment-info">
            <div class="total-row">
                <span>Bayar</span>
                <span>Rp {{ number_format($penjualan->bayar, 0, ',', '.') }}</span>
            </div>

            @if($penjualan->kembalian > 0)
                <div class="total-row">
                    <span>Kembalian</span>
                    <span>Rp {{ number_format($penjualan->kembalian, 0, ',', '.') }}</span>
                </div>
            @endif

            @if($penjualan->sisa > 0)
                <div class="total-row">
                    <span class="bold">Sisa Tagihan</span>
                    <span class="bold">Rp {{ number_format($penjualan->sisa, 0, ',', '.') }}</span>
                </div>
            @endif

            <div class="info-row">
                <span class="info-label">Status</span>
                <span class="bold">{{ $penjualan->status }}</span>
            </div>
        </div>

        <div class="footer">
            @if($setting && $setting->keterangan_struk)
                <div class="footer-note">{{ $setting->keterangan_struk }}</div>
            @else
                <div class="footer-note">Terima kasih atas kunjungan Anda</div>
            @endif
            <div class="footer-note">Barang yang sudah dibeli tidak dapat dikembalikan</div>
            <div style="margin-top: 10px;">*** STRUK INI ADALAH BUKTI PEMBAYARAN YANG SAH ***</div>
        </div>
    </div>

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>
