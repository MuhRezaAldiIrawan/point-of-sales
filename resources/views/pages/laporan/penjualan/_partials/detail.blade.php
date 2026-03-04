<style>
    .detail-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.5rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }

    .detail-title {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }

    .detail-number {
        font-size: 1rem;
        opacity: 0.95;
    }

    .info-box {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
    }

    .info-box-label {
        font-weight: 700;
        color: #667eea;
        font-size: 0.7rem;
        margin-bottom: 0.3rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-box-value {
        font-size: 0.95rem;
        color: #2c2c2c;
        font-weight: 600;
    }

    .items-table-modal {
        margin: 0;
        font-size: 0.875rem;
    }

    .items-table-modal thead th {
        background: royalblue;
        font-weight: 700;
        font-size: 0.8rem;
        color: white;
        border: none;
        padding: 0.75rem 0.5rem;
        text-transform: uppercase;
    }

    .items-table-modal tbody td {
        padding: 0.75rem 0.5rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f1f1;
    }

    .summary-box {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 8px;
    }

    .summary-row-modal {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        font-size: 0.9rem;
        border-bottom: 1px dashed #dee2e6;
    }

    .summary-row-modal:last-child {
        border-bottom: none;
    }

    .summary-row-modal.total {
        border-top: 2px solid #667eea;
        border-bottom: 2px solid #667eea;
        margin-top: 0.75rem;
        padding: 0.75rem 0;
        font-size: 1.25rem;
        font-weight: 700;
        color: #667eea;
    }

    .badge-status {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        border-radius: 50px;
        font-weight: 700;
    }
</style>

<div class="detail-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <div class="detail-title">PEMBAYARAN KREDIT</div>
            <div class="detail-number">{{ $penjualan->no_invoice }}</div>
        </div>
        <div>
            <span class="badge-status {{ $penjualan->status === 'Lunas' ? 'badge-success' : 'badge-warning' }}">
                {{ $penjualan->status }}
            </span>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-3">
        <div class="info-box">
            <div class="info-box-label">TANGGAL TRANSAKSI</div>
            <div class="info-box-value">{{ $penjualan->tanggal_penjualan->format('d/m/Y') }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box">
            <div class="info-box-label">JATUH TEMPO</div>
            <div class="info-box-value text-danger">
                {{ $penjualan->jatuh_tempo ? $penjualan->jatuh_tempo->format('d/m/Y') : '-' }}
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box">
            <div class="info-box-label">METODE PEMBAYARAN</div>
            <div class="info-box-value">{{ $penjualan->payment_method }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box">
            <div class="info-box-label">DIBUAT OLEH</div>
            <div class="info-box-value">{{ $penjualan->createdBy->nama_depan }} {{ $penjualan->createdBy->nama_belakang ?? '' }}</div>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-12">
        <div class="info-box">
            <div class="info-box-label">PELANGGAN</div>
            <div class="info-box-value">{{ $penjualan->pelanggan->nama }}</div>
            @if($penjualan->pelanggan->alamat)
                <small class="text-muted">{{ $penjualan->pelanggan->alamat }}</small><br>
            @endif
            @if($penjualan->pelanggan->no_hp)
                <small class="text-muted">Telp: {{ $penjualan->pelanggan->no_hp }}</small>
            @endif
        </div>
    </div>
</div>

<h6 class="mb-2" style="font-weight: 700; color: #2c2c2c;">
    <i class="ft-shopping-cart" style="color: #667eea;"></i> Detail Item
</h6>
<div class="table-responsive mb-3">
    <table class="table table-bordered items-table-modal">
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="40%">Nama Barang</th>
                <th width="10%" class="text-center">Jumlah</th>
                <th width="10%" class="text-center">Bonus</th>
                <th width="15%" class="text-right">Harga</th>
                <th width="10%" class="text-center">Diskon</th>
                <th width="15%" class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penjualan->detailPenjualans as $index => $detail)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td><strong>{{ $detail->barang->nama_barang }}</strong></td>
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

<div class="row">
    <div class="col-md-12">
        <h6 class="mb-2" style="font-weight: 700; color: #2c2c2c;">
            <i class="ft-file-text" style="color: #667eea;"></i> Ringkasan Pembayaran
        </h6>
        <div class="summary-box">
            @php
                $subtotalItems = 0;
                foreach($penjualan->detailPenjualans as $detail) {
                    $subtotalItems += $detail->subtotal;
                }
                $diskonAmount = $subtotalItems * ($penjualan->diskon / 100);
            @endphp

            <div class="summary-row-modal">
                <span>Subtotal:</span>
                <span><strong>Rp {{ number_format($subtotalItems, 0, ',', '.') }}</strong></span>
            </div>
            <div class="summary-row-modal">
                <span>Diskon ({{ $penjualan->diskon }}%):</span>
                <span><strong>Rp {{ number_format($diskonAmount, 0, ',', '.') }}</strong></span>
            </div>
            <div class="summary-row-modal">
                <span>PPN ({{ $setting->ppn ?? 11 }}%):</span>
                <span><strong>Rp {{ number_format($penjualan->ppn_amount, 0, ',', '.') }}</strong></span>
            </div>
            <div class="summary-row-modal">
                <span>Biaya Kirim:</span>
                <span><strong>Rp {{ number_format($penjualan->biaya_kirim, 0, ',', '.') }}</strong></span>
            </div>
            <div class="summary-row-modal">
                <span>Biaya Lain:</span>
                <span><strong>Rp {{ number_format($penjualan->biaya_lain, 0, ',', '.') }}</strong></span>
            </div>
            <div class="summary-row-modal total">
                <span>GRAND TOTAL:</span>
                <span>Rp {{ number_format($penjualan->grand_total, 0, ',', '.') }}</span>
            </div>
            <div class="summary-row-modal">
                <span>Jumlah Bayar:</span>
                <span><strong>Rp {{ number_format($penjualan->bayar, 0, ',', '.') }}</strong></span>
            </div>
            <div class="summary-row-modal">
                <span>Kembalian:</span>
                <span><strong>Rp {{ number_format($penjualan->kembalian, 0, ',', '.') }}</strong></span>
            </div>
            <div class="summary-row-modal">
                <span>Sisa Pembayaran:</span>
                <span class="{{ $penjualan->sisa > 0 ? 'text-danger' : 'text-success' }}">
                    <strong>Rp {{ number_format($penjualan->sisa, 0, ',', '.') }}</strong>
                </span>
            </div>
        </div>
    </div>
</div>

@if($penjualan->catatan)
<div class="row mt-3">
    <div class="col-md-12">
        <div class="alert alert-info">
            <strong><i class="ft-info"></i> Catatan:</strong><br>
            {{ $penjualan->catatan }}
        </div>
    </div>
</div>
@endif
