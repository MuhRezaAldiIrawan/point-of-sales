<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\Barang;
use App\Models\DetailBarang;
use App\Models\JenisBarang;
use App\Models\Pelanggan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Dashboard';

        $filterDate = $request->get('filter_date');
        $filterPreset = $request->get('filter_preset');

        $startDate = null;
        $endDate = null;

        if ($filterDate) {
            $startDate = Carbon::parse($filterDate)->startOfDay();
            $endDate = Carbon::parse($filterDate)->endOfDay();
        } elseif ($filterPreset) {
            $dates = $this->getPresetDates($filterPreset);
            $startDate = $dates['start'];
            $endDate = $dates['end'];
        } else {
            $startDate = Carbon::today()->startOfDay();
            $endDate = Carbon::today()->endOfDay();
        }

        $totalPenjualanHariIni = Penjualan::whereBetween('tanggal_penjualan', [$startDate, $endDate])
            ->whereIn('status', ['lunas', 'kredit'])
            ->sum('grand_total');

        $penjualanKemarin = Penjualan::whereDate('tanggal_penjualan', Carbon::yesterday())
            ->whereIn('status', ['lunas', 'kredit'])
            ->sum('grand_total');

        $percentageChange = 0;
        if ($penjualanKemarin > 0) {
            $percentageChange = (($totalPenjualanHariIni - $penjualanKemarin) / $penjualanKemarin) * 100;
        }

        $transaksiPending = Penjualan::where('status', 'pending')->count();
        $transaksiPerluKonfirmasi = Penjualan::where('status', 'pending')
            ->whereDate('tanggal_penjualan', Carbon::today())
            ->count();

        $stokMenipis = DetailBarang::whereHas('barang', function($q) {
            $q->whereNull('deleted_at');
        })->count();

        $stokHabis = 0;

        $piutangKredit = Penjualan::where('status', 'kredit')
            ->where('sisa', '>', 0)
            ->sum('sisa');

        $pelangganBelumLunas = Penjualan::where('status', 'kredit')
            ->where('sisa', '>', 0)
            ->distinct('pelanggan_id')
            ->count('pelanggan_id');

        $transaksiTerbaru = Penjualan::with(['pelanggan', 'createdBy'])
            ->latest()
            ->take(10)
            ->get();

        $produkTerlaris = DetailPenjualan::select('barang_id', DB::raw('SUM(jumlah) as total_terjual'))
            ->with(['barang.jenisBarang'])
            ->groupBy('barang_id')
            ->orderBy('total_terjual', 'DESC')
            ->take(5)
            ->get();

        // Get daily sales data based on filter
        $chartDataPenjualan = [];
        $chartLabelsPenjualan = [];

        // Determine chart date range based on filter
        $chartStartDate = null;
        $chartEndDate = null;

        if ($filterDate) {
            // If specific date selected, show 7 days around it (3 before, selected day, 3 after)
            $chartStartDate = Carbon::parse($filterDate)->subDays(3);
            $chartEndDate = Carbon::parse($filterDate)->addDays(3);
        } elseif ($filterPreset) {
            // For preset filters, use the preset range
            $chartStartDate = $startDate->copy();
            $chartEndDate = $endDate->copy();

            // Limit to max 30 days for readability
            if ($chartStartDate->diffInDays($chartEndDate) > 30) {
                $chartStartDate = $chartEndDate->copy()->subDays(29);
            }
        } else {
            // Default: show last 7 days
            $chartStartDate = Carbon::now()->subDays(6);
            $chartEndDate = Carbon::now();
        }

        // Generate daily data
        $currentDate = $chartStartDate->copy();
        while ($currentDate <= $chartEndDate) {
            $dayTotal = Penjualan::whereDate('tanggal_penjualan', $currentDate)
                ->whereIn('status', ['lunas', 'kredit'])
                ->sum('grand_total');

            $chartDataPenjualan[] = $dayTotal;
            $chartLabelsPenjualan[] = $currentDate->format('d/m');

            $currentDate->addDay();
        }

        $kategoriTerlaris = DetailPenjualan::select('jenis_barangs.nama', DB::raw('COUNT(detail_penjualans.id) as total'))
            ->join('barangs', 'detail_penjualans.barang_id', '=', 'barangs.id')
            ->join('jenis_barangs', 'barangs.jenis_barang_id', '=', 'jenis_barangs.id')
            ->groupBy('jenis_barangs.nama')
            ->orderBy('total', 'DESC')
            ->take(5)
            ->get();

        $totalKategori = $kategoriTerlaris->sum('total');
        $chartDataKategori = [];
        $chartLabelsKategori = [];

        foreach ($kategoriTerlaris as $kategori) {
            $chartLabelsKategori[] = $kategori->nama;
            $percentage = $totalKategori > 0 ? round(($kategori->total / $totalKategori) * 100, 1) : 0;
            $chartDataKategori[] = $percentage;
        }

        return view('pages.dashboard.dashboard', compact(
            'title',
            'totalPenjualanHariIni',
            'percentageChange',
            'transaksiPending',
            'transaksiPerluKonfirmasi',
            'stokMenipis',
            'stokHabis',
            'piutangKredit',
            'pelangganBelumLunas',
            'transaksiTerbaru',
            'produkTerlaris',
            'chartDataPenjualan',
            'chartLabelsPenjualan',
            'chartDataKategori',
            'chartLabelsKategori'
        ));
    }

    private function getPresetDates($preset)
    {
        $today = Carbon::today();

        switch($preset) {
            case 'today':
                return [
                    'start' => $today->copy()->startOfDay(),
                    'end' => $today->copy()->endOfDay()
                ];
            case 'yesterday':
                return [
                    'start' => $today->copy()->subDay()->startOfDay(),
                    'end' => $today->copy()->subDay()->endOfDay()
                ];
            case 'this_week':
                return [
                    'start' => $today->copy()->startOfWeek(),
                    'end' => $today->copy()->endOfWeek()
                ];
            case 'last_week':
                return [
                    'start' => $today->copy()->subWeek()->startOfWeek(),
                    'end' => $today->copy()->subWeek()->endOfWeek()
                ];
            case 'this_month':
                return [
                    'start' => $today->copy()->startOfMonth(),
                    'end' => $today->copy()->endOfMonth()
                ];
            case 'last_month':
                return [
                    'start' => $today->copy()->subMonth()->startOfMonth(),
                    'end' => $today->copy()->subMonth()->endOfMonth()
                ];
            case 'this_year':
                return [
                    'start' => $today->copy()->startOfYear(),
                    'end' => $today->copy()->endOfYear()
                ];
            case 'last_year':
                return [
                    'start' => $today->copy()->subYear()->startOfYear(),
                    'end' => $today->copy()->subYear()->endOfYear()
                ];
            default:
                return [
                    'start' => $today->copy()->startOfDay(),
                    'end' => $today->copy()->endOfDay()
                ];
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
