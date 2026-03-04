@extends('layouts.main')

@section('css')
    <style>
        .card {
            border: 1px solid #dee2e6;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .detail-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 5px 5px 0 0;
        }

        .detail-badge {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 500;
        }

        .info-card {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .info-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 5px;
        }

        .info-value {
            color: #212529;
            font-size: 16px;
            margin-bottom: 15px;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            text-align: center;
            vertical-align: middle;
            padding: 12px 8px;
            font-size: 13px;
        }

        .table tbody td {
            vertical-align: middle;
            padding: 12px 8px;
            font-size: 13px;
        }

        .table-responsive {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            overflow: hidden;
        }

        .summary-card {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }

        .summary-value {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .summary-label {
            font-size: 14px;
            opacity: 0.9;
        }

        .btn-back {
            background-color: #6c757d;
            border-color: #6c757d;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            font-weight: 500;
        }

        .btn-back:hover {
            background-color: #545b62;
            border-color: #4e555b;
            color: white;
            text-decoration: none;
        }

        .ppn-badge {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 500;
        }

        .ppn-badge.ppn {
            background-color: #28a745;
            color: white;
        }

        .ppn-badge.non-ppn {
            background-color: #6c757d;
            color: white;
        }

        @media (max-width: 768px) {
            .detail-header {
                padding: 15px;
            }

            .info-card {
                padding: 15px;
            }

            .table thead th,
            .table tbody td {
                padding: 8px 4px;
                font-size: 12px;
            }

            .summary-value {
                font-size: 20px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <!-- Header -->
                <div class="detail-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-2">Detail Barang Masuk</h4>
                            <span class="detail-badge">{{ $barangMasuk->no_reff }}</span>
                        </div>
                        <div class="text-right">
                            <div class="text-white-50 small">Tanggal Masuk</div>
                            <div class="h5 mb-0">{{ $barangMasuk->tanggal_masuk->format('d F Y') }}</div>
                        </div>
                    </div>
                </div>

                <div class="card-content">
                    <div class="card-body">

                        <!-- Informasi Header -->
                        <div class="row">
                            <div class="col-md-8">
                                <div class="info-card">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="info-label">No. Referensi</div>
                                            <div class="info-value">{{ $barangMasuk->no_reff }}</div>

                                            <div class="info-label">Jenis Stok</div>
                                            <div class="info-value">{{ $barangMasuk->jenisStok->nama ?? '-' }}</div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-label">Tanggal Masuk</div>
                                            <div class="info-value">{{ $barangMasuk->tanggal_masuk->format('d F Y') }}</div>

                                            <div class="info-label">Catatan</div>
                                            <div class="info-value">{{ $barangMasuk->catatan ?: '-' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="summary-card mb-3">
                                    <div class="summary-value">{{ $barangMasuk->detailBarangMasuk->count() }}</div>
                                    <div class="summary-label">Total Item</div>
                                </div>
                                <div class="summary-card mb-3">
                                    <div class="summary-value">{{ number_format($barangMasuk->detailBarangMasuk->sum('jumlah')) }}</div>
                                    <div class="summary-label">Total Jumlah</div>
                                </div>
                                <div class="summary-card">
                                    <div class="summary-value">Rp {{ number_format($barangMasuk->detailBarangMasuk->sum('total'), 0, ',', '.') }}</div>
                                    <div class="summary-label">Grand Total</div>
                                </div>
                            </div>
                        </div>

                        <!-- Detail Barang -->
                        <div class="mt-4">
                            <h5 class="mb-3">Detail Barang</h5>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th width="5%">NO</th>
                                            <th width="12%">KODE</th>
                                            <th width="20%">NAMA BARANG</th>
                                            <th width="8%">PPN</th>
                                            <th width="10%">SATUAN</th>
                                            <th width="7%">ISI</th>
                                            <th width="8%">JUMLAH</th>
                                            <th width="12%">HARGA BELI</th>
                                            <th width="12%">TOTAL</th>
                                            <th width="6%">KETERANGAN</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($barangMasuk->detailBarangMasuk as $index => $detail)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $detail->barang->kode ?? '-' }}</td>
                                            <td>{{ $detail->barang->nama ?? '-' }}</td>
                                            <td class="text-center">
                                                <span class="ppn-badge {{ strtolower($detail->stok_ppn) === 'ppn' ? 'ppn' : 'non-ppn' }}">
                                                    {{ $detail->stok_ppn }}
                                                </span>
                                            </td>
                                            <td class="text-center">{{ $detail->satuan->nama ?? '-' }}</td>
                                            <td class="text-center">{{ number_format($detail->isi) }}</td>
                                            <td class="text-center">{{ number_format($detail->jumlah) }}</td>
                                            <td class="text-right">Rp {{ number_format($detail->harga_beli, 0, ',', '.') }}</td>
                                            <td class="text-right">Rp {{ number_format($detail->total, 0, ',', '.') }}</td>
                                            <td>{{ $detail->keterangan ?: '-' }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="10" class="text-center py-4">
                                                <em class="text-muted">Tidak ada detail barang</em>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                    @if($barangMasuk->detailBarangMasuk->count() > 0)
                                    <tfoot>
                                        <tr class="table-info">
                                            <th colspan="6" class="text-right">TOTAL:</th>
                                            <th class="text-center">{{ number_format($barangMasuk->detailBarangMasuk->sum('jumlah')) }}</th>
                                            <th></th>
                                            <th class="text-right">Rp {{ number_format($barangMasuk->detailBarangMasuk->sum('total'), 0, ',', '.') }}</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                    @endif
                                </table>
                            </div>
                        </div>

                        <!-- Informasi Audit -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="info-card">
                                    <h6 class="mb-3">Informasi Audit</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="info-label">Dibuat Oleh</div>
                                            <div class="info-value">{{ $barangMasuk->createdBy->name ?? '-' }}</div>

                                            <div class="info-label">Tanggal Dibuat</div>
                                            <div class="info-value">{{ $barangMasuk->created_at->format('d F Y, H:i:s') }}</div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-label">Diperbarui Oleh</div>
                                            <div class="info-value">{{ $barangMasuk->updatedBy->name ?? '-' }}</div>

                                            <div class="info-label">Tanggal Diperbarui</div>
                                            <div class="info-value">{{ $barangMasuk->updated_at->format('d F Y, H:i:s') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="form-group mt-4 text-center">
                            <a href="{{ route('barangmasuk.index') }}" class="btn-back mr-3">
                                <i class="feather icon-arrow-left mr-2"></i> Kembali ke Daftar
                            </a>
                            <a href="{{ route('barangmasuk.edit', $barangMasuk->id) }}" class="btn btn-warning">
                                <i class="feather icon-edit mr-2"></i> Edit Data
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            // Add any JavaScript functionality if needed
            console.log('Detail barang masuk loaded');
        });
    </script>
@endsection
