@extends('layouts.main')

@section('css')
<style>
.chart-container {
    position: relative;
    width: 100%;
}

.card .card-header {
    border-bottom: 1px solid #f1f3f4;
}

.card-title i {
    margin-right: 8px;
}

/* Custom chart styling */
#salesChart, #categoryChart {
    max-width: 100%;
    height: auto !important;
}

/* Responsive chart containers */
@media (max-width: 768px) {
    .chart-container {
        height: 250px !important;
    }
}

/* Stats card hover effect */
.card.pull-up:hover {
    transform: translateY(-5px);
    transition: all 0.3s ease;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

/* Button hover effects */
.btn-glow:hover {
    transform: translateY(-2px);
    transition: all 0.2s ease;
}

/* Date Filter Styles */
.date-filter-card {
    border: 1px solid #e3ebf0;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    margin-bottom: 2rem;
}

.date-filter-card .card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 8px 8px 0 0;
    border-bottom: none;
}

.date-filter-card .card-header .card-title {
    color: white;
    font-weight: 600;
    margin-bottom: 0;
}

.date-filter-card .card-header .btn-outline-primary {
    background: rgba(255,255,255,0.1);
    border-color: rgba(255,255,255,0.3);
    color: white;
    font-size: 0.875rem;
}

.date-filter-card .card-header .btn-outline-primary:hover {
    background: rgba(255,255,255,0.2);
    border-color: rgba(255,255,255,0.5);
    color: white;
}

.filter-form-group {
    margin-bottom: 1.5rem;
}

.filter-form-group label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.filter-form-group label i {
    margin-right: 0.5rem;
    color: #6c757d;
}

.filter-input {
    border: 1px solid #e3ebf0;
    border-radius: 6px;
    padding: 0.75rem 1rem;
    font-size: 0.875rem;
    transition: all 0.3s ease;
}

.filter-input:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.btn-filter {
    border-radius: 6px;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    font-size: 0.875rem;
    transition: all 0.3s ease;
}

.btn-filter:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.filter-status {
    border: none;
    border-radius: 8px;
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
    color: white;
    border-left: 4px solid #0085a3;
}

.filter-status .close {
    color: white;
    opacity: 0.8;
}

.filter-status .close:hover {
    opacity: 1;
}

/* Filter responsive adjustments */
@media (max-width: 576px) {
    .date-filter-card .btn-group {
        flex-direction: column;
    }

    .date-filter-card .btn-group .btn {
        margin-bottom: 0.5rem;
    }

    .date-filter-card .card-header .heading-elements {
        margin-top: 0.5rem;
    }
}
</style>
@endsection

@section('content')

    {{-- Statistics Cards --}}
    <div class="row">
        <div class="col-xl-3 col-lg-6 col-md-6 col-12">
            <div class="card pull-up">
                <div class="card-content">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="media-body text-left">
                                <h3 class="success">Rp {{ number_format($totalPenjualanHariIni, 0, ',', '.') }}</h3>
                                <h6>Total Penjualan Hari Ini</h6>
                            </div>
                            <div class="align-self-center">
                                <i class="ft-pie-chart success font-large-2 float-right"></i>
                            </div>
                        </div>
                        <div class="progress progress-sm mt-1 mb-0 box-shadow-2">
                            <div class="progress-bar bg-gradient-x-success" role="progressbar" style="width: {{ abs($percentageChange) > 100 ? 100 : abs($percentageChange) }}%"
                                 aria-valuenow="{{ abs($percentageChange) }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <p class="text-muted text-sm mb-0">
                            <span class="{{ $percentageChange >= 0 ? 'text-success' : 'text-danger' }}">{{ $percentageChange >= 0 ? '+' : '' }}{{ number_format($percentageChange, 1) }}%</span> dari kemarin
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 col-12">
            <div class="card pull-up">
                <div class="card-content">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="media-body text-left">
                                <h3 class="warning">{{ $transaksiPending }}</h3>
                                <h6>Transaksi Pending</h6>
                            </div>
                            <div class="align-self-center">
                                <i class="ft-clock warning font-large-2 float-right"></i>
                            </div>
                        </div>
                        <div class="progress progress-sm mt-1 mb-0 box-shadow-2">
                            <div class="progress-bar bg-gradient-x-warning" role="progressbar" style="width: {{ $transaksiPending > 0 ? 45 : 0 }}%"
                                 aria-valuenow="{{ $transaksiPending > 0 ? 45 : 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <p class="text-muted text-sm mb-0">
                            <span class="text-warning">{{ $transaksiPerluKonfirmasi }} transaksi</span> butuh konfirmasi
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 col-12">
            <div class="card pull-up">
                <div class="card-content">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="media-body text-left">
                                <h3 class="danger">{{ $stokMenipis }}</h3>
                                <h6>Total Item Produk</h6>
                            </div>
                            <div class="align-self-center">
                                <i class="ft-alert-triangle danger font-large-2 float-right"></i>
                            </div>
                        </div>
                        <div class="progress progress-sm mt-1 mb-0 box-shadow-2">
                            <div class="progress-bar bg-gradient-x-danger" role="progressbar" style="width: {{ $stokMenipis > 0 ? 30 : 0 }}%"
                                 aria-valuenow="{{ $stokMenipis > 0 ? 30 : 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <p class="text-muted text-sm mb-0">
                            <span class="text-danger">{{ $stokHabis }} produk</span> perlu dicek
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 col-12">
            <div class="card pull-up">
                <div class="card-content">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="media-body text-left">
                                <h3 class="info">Rp {{ number_format($piutangKredit, 0, ',', '.') }}</h3>
                                <h6>Piutang Kredit</h6>
                            </div>
                            <div class="align-self-center">
                                <i class="ft-credit-card info font-large-2 float-right"></i>
                            </div>
                        </div>
                        <div class="progress progress-sm mt-1 mb-0 box-shadow-2">
                            <div class="progress-bar bg-gradient-x-info" role="progressbar" style="width: {{ $pelangganBelumLunas > 0 ? 65 : 0 }}%"
                                 aria-valuenow="{{ $pelangganBelumLunas > 0 ? 65 : 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <p class="text-muted text-sm mb-0">
                            <span class="text-info">{{ $pelangganBelumLunas }} pelanggan</span> belum lunas
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Date Filter Section --}}
    <div class="row">
        <div class="col-12">
            <div class="card date-filter-card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="ft-filter"></i> Filter Data Dashboard
                    </h4>
                    <a class="heading-elements-toggle"><i class="ft-more-horizontal font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><button class="btn btn-sm btn-outline-primary" onclick="resetFilter()">
                                <i class="ft-refresh-cw"></i> Reset
                            </button></li>
                        </ul>
                    </div>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="row align-items-end">
                            <div class="col-lg-4 col-md-6 col-12">
                                <div class="form-group filter-form-group">
                                    <label for="filter_date">
                                        <i class="ft-calendar"></i> Pilih Tanggal
                                    </label>
                                    <input type="date"
                                           id="filter_date"
                                           class="form-control filter-input"
                                           placeholder="Pilih tanggal..."
                                           onchange="applyFilter()">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-12">
                                <div class="form-group filter-form-group">
                                    <label for="filter_preset">
                                        <i class="ft-clock"></i> Periode Cepat
                                    </label>
                                    <select id="filter_preset" class="form-control filter-input" onchange="applyPresetFilter()">
                                        <option value="">Pilih Periode...</option>
                                        <option value="today">Hari Ini</option>
                                        <option value="yesterday">Kemarin</option>
                                        <option value="this_week">Minggu Ini</option>
                                        <option value="last_week">Minggu Lalu</option>
                                        <option value="this_month">Bulan Ini</option>
                                        <option value="last_month">Bulan Lalu</option>
                                        <option value="this_year">Tahun Ini</option>
                                        <option value="last_year">Tahun Lalu</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12 col-12">
                                <div class="form-group filter-form-group">
                                    <label>&nbsp;</label>
                                    <button type="button"
                                            class="btn btn-primary btn-filter btn-block"
                                            onclick="applyFilter()">
                                        <i class="ft-search"></i> Terapkan Filter
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Filter Status Display -->
                        <div class="row mt-2">
                            <div class="col-12">
                                <div id="filter_status" class="alert filter-status alert-dismissible fade" role="alert" style="display: none;">
                                    <i class="ft-info-circle"></i>
                                    <strong>Filter Aktif:</strong>
                                    <span id="filter_status_text">Silakan pilih tanggal atau periode untuk memfilter data</span>
                                    <button type="button" class="close" onclick="hideFilterStatus()" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Aksi Cepat</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-12">
                                <a href="{{ route('penjualan.create') }}" class="btn btn-outline-success btn-block btn-glow">
                                    <i class="ft-shopping-cart"></i> Transaksi Penjualan
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-6 col-12">
                                <a href="{{ route('barangmasuk.create') }}" class="btn btn-outline-info btn-block btn-glow">
                                    <i class="ft-inbox"></i> Input Barang Masuk
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-6 col-12">
                                <a href="{{ route('stokbarang.index') }}" class="btn btn-outline-warning btn-block btn-glow">
                                    <i class="ft-box"></i> Cek Stok Barang
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-6 col-12">
                                <a href="{{ route('laporan.penjualan.index') }}" class="btn btn-outline-primary btn-block btn-glow">
                                    <i class="ft-bar-chart-2"></i> Laporan Penjualan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Section --}}
    <div class="row">
        <div class="col-lg-8 col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="ft-bar-chart-2"></i> Grafik Penjualan Harian
                    </h4>
                    <a class="heading-elements-toggle"><i class="ft-more-horizontal font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="chart-container" style="position: relative; height: 350px;">
                            <canvas id="salesChart" style="max-height: 350px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="ft-pie-chart"></i> Kategori Produk Terlaris
                    </h4>
                    <a class="heading-elements-toggle"><i class="ft-more-horizontal font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="chart-container" style="position: relative; height: 350px;">
                            <canvas id="categoryChart" style="max-height: 350px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Transactions --}}
    <div class="row">
        <div class="col-lg-8 col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Transaksi Terbaru</h4>
                    <a class="heading-elements-toggle"><i class="ft-more-horizontal font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-content">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>No. Transaksi</th>
                                    <th>Pelanggan</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transaksiTerbaru as $transaksi)
                                <tr>
                                    <td>{{ $transaksi->no_invoice }}</td>
                                    <td>{{ $transaksi->pelanggan->nama ?? 'Pelanggan Umum' }}</td>
                                    <td>Rp {{ number_format($transaksi->grand_total, 0, ',', '.') }}</td>
                                    <td>
                                        @if($transaksi->status == 'lunas')
                                            <span class="badge badge-success">Lunas</span>
                                        @elseif($transaksi->status == 'pending')
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif($transaksi->status == 'kredit')
                                            <span class="badge badge-info">Kredit</span>
                                        @else
                                            <span class="badge badge-secondary">{{ ucfirst($transaksi->status) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $transaksi->tanggal_penjualan->format('d/m/Y H:i') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada transaksi</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Produk Terlaris</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="list-group">
                            @forelse($produkTerlaris as $produk)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $produk->barang->nama_barang }}</h6>
                                    <small>Kategori: {{ $produk->barang->jenisBarang->nama_jenis ?? 'Tidak ada kategori' }}</small>
                                </div>
                                <span class="badge badge-primary badge-pill">{{ $produk->total_terjual }} terjual</span>
                            </div>
                            @empty
                            <div class="list-group-item text-center">
                                <p class="mb-0">Belum ada data penjualan</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });
}

function getPresetStatusText(preset) {
    switch(preset) {
        case 'today':
            return 'Menampilkan data hari ini';
        case 'yesterday':
            return 'Menampilkan data kemarin';
        case 'this_week':
            return 'Menampilkan data minggu ini';
        case 'last_week':
            return 'Menampilkan data minggu lalu';
        case 'this_month':
            return 'Menampilkan data bulan ini';
        case 'last_month':
            return 'Menampilkan data bulan lalu';
        case 'this_year':
            return 'Menampilkan data tahun ini';
        case 'last_year':
            return 'Menampilkan data tahun lalu';
        default:
            return 'Filter aktif';
    }
}

$(document).ready(function() {
    const urlParams = new URLSearchParams(window.location.search);
    const filterDate = urlParams.get('filter_date');
    const filterPreset = urlParams.get('filter_preset');

    if (filterDate) {
        document.getElementById('filter_date').value = filterDate;
        const dateFormatted = formatDate(filterDate);
        document.getElementById('filter_status_text').innerHTML = `Menampilkan data tanggal ${dateFormatted}`;
        document.getElementById('filter_status').style.display = 'block';
        document.getElementById('filter_status').classList.add('show');
    } else if (filterPreset) {
        document.getElementById('filter_preset').value = filterPreset;
        const statusText = getPresetStatusText(filterPreset);
        document.getElementById('filter_status_text').innerHTML = statusText;
        document.getElementById('filter_status').style.display = 'block';
        document.getElementById('filter_status').classList.add('show');
    }

    const salesCtx = document.getElementById('salesChart').getContext('2d');
    const salesData = @json($chartDataPenjualan);
    const salesLabels = @json($chartLabelsPenjualan);

    const salesChart = new Chart(salesCtx, {
        type: 'bar',
        data: {
            labels: salesLabels,
            datasets: [{
                label: 'Penjualan (Juta Rupiah)',
                data: salesData,
                backgroundColor: 'rgba(102, 126, 234, 0.8)',
                borderColor: '#667eea',
                borderWidth: 2,
                borderRadius: 8,
                barThickness: 40
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    cornerRadius: 8,
                    padding: 12,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return 'Penjualan: Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                },
                datalabels: {
                    anchor: 'end',
                    align: 'top',
                    formatter: function(value) {
                        return 'Rp ' + value.toFixed(2) + 'jt';
                    },
                    color: '#333',
                    font: {
                        weight: 'bold',
                        size: 11
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 11,
                            weight: '500'
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.05)'
                    },
                    ticks: {
                        font: {
                            size: 11
                        },
                        callback: function(value) {
                            if (value >= 1000000) {
                                return 'Rp ' + (value / 1000000).toFixed(1) + 'jt';
                            } else if (value >= 1000) {
                                return 'Rp ' + (value / 1000).toFixed(0) + 'rb';
                            }
                            return 'Rp ' + value;
                        }
                    }
                }
            }
        },
        plugins: [{
            afterDatasetsDraw: function(chart) {
                const ctx = chart.ctx;
                chart.data.datasets.forEach(function(dataset, i) {
                    const meta = chart.getDatasetMeta(i);
                    if (!meta.hidden) {
                        meta.data.forEach(function(element, index) {
                            ctx.fillStyle = '#333';
                            ctx.font = 'bold 11px Arial';
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'bottom';
                            const value = dataset.data[index];
                            let dataString;
                            if (value >= 1000000) {
                                dataString = 'Rp ' + (value / 1000000).toFixed(1) + 'jt';
                            } else if (value >= 1000) {
                                dataString = 'Rp ' + (value / 1000).toFixed(0) + 'rb';
                            } else {
                                dataString = 'Rp ' + value.toLocaleString('id-ID');
                            }
                            ctx.fillText(dataString, element.x, element.y - 5);
                        });
                    }
                });
            }
        }]
    });

    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    const categoryLabels = @json($chartLabelsKategori);
    const categoryData = @json($chartDataKategori);

    const categoryChart = new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: categoryLabels.length > 0 ? categoryLabels : ['Belum ada data'],
            datasets: [{
                data: categoryData.length > 0 ? categoryData : [100],
                backgroundColor: categoryData.length > 0 ? [
                    '#007bff',
                    '#28a745',
                    '#ffc107',
                    '#dc3545',
                    '#6f42c1'
                ] : ['#e0e0e0'],
                borderColor: categoryData.length > 0 ? [
                    '#007bff',
                    '#28a745',
                    '#ffc107',
                    '#dc3545',
                    '#6f42c1'
                ] : ['#e0e0e0'],
                borderWidth: 2,
                hoverBorderWidth: 3,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '60%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        font: {
                            size: 11,
                            weight: '500'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    cornerRadius: 8,
                    padding: 12,
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed;
                            return label + ': ' + value + '%';
                        }
                    }
                }
            },
            animation: {
                animateRotate: true,
                duration: 2000
            }
        }
    });


    $('[data-action="reload"]').on('click', function(e) {
        e.preventDefault();
        window.location.reload();
    });


    setTimeout(function() {
        salesChart.update('active');
        categoryChart.update('active');
    }, 500);


    window.applyFilter = function() {
        const filterDate = document.getElementById('filter_date').value;
        const preset = document.getElementById('filter_preset').value;

        if (!filterDate && !preset) {
            swal('Error', 'Harap pilih tanggal atau periode terlebih dahulu', 'error');
            return;
        }

        let statusText;
        let queryParams = {};

        if (filterDate) {
            const dateFormatted = formatDate(filterDate);
            statusText = `Menampilkan data tanggal ${dateFormatted}`;
            queryParams.filter_date = filterDate;
            document.getElementById('filter_preset').value = '';
        } else if (preset) {
            statusText = getPresetStatusText(preset);
            queryParams.filter_preset = preset;
        }

        document.getElementById('filter_status_text').innerHTML = statusText;
        document.getElementById('filter_status').style.display = 'block';
        document.getElementById('filter_status').classList.add('show');

        showFilterLoading();

        const queryString = new URLSearchParams(queryParams).toString();
        window.location.href = '{{ route("dashboard.index") }}?' + queryString;
    };

    window.applyPresetFilter = function() {
        const preset = document.getElementById('filter_preset').value;
        if (!preset) return;

        document.getElementById('filter_date').value = '';
        applyFilter();
    };

    window.resetFilter = function() {
        window.location.href = '{{ route("dashboard.index") }}';
    };

    window.hideFilterStatus = function() {
        document.getElementById('filter_status').style.display = 'none';
        document.getElementById('filter_status').classList.remove('show');
    };

    function showFilterLoading() {
        $('.chart-container').each(function() {
            $(this).css({
                'position': 'relative',
                'opacity': '0.6'
            });
        });

        $('.statistics-card').each(function() {
            $(this).css('opacity', '0.6');
        });
    }

    function hideFilterLoading() {
        $('.chart-container').css('opacity', '1');
        $('.statistics-card').css('opacity', '1');
    }
});
</script>
@endsection
