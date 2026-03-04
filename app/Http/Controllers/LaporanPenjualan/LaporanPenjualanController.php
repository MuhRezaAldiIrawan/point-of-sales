<?php

namespace App\Http\Controllers\LaporanPenjualan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\Penjualan;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\DB;

class LaporanPenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = 'Laporan Penjualan';
        $pelanggans = Pelanggan::orderBy('nama')->get();

        if ($request->ajax() && !$request->has('summary_only')) {
            $query = Penjualan::with(['pelanggan', 'createdBy', 'detailPenjualans.barang']);

            if ($request->filled('start_date') && $request->start_date != '') {
                $query->whereDate('tanggal_penjualan', '>=', $request->start_date);
            }

            if ($request->filled('end_date') && $request->end_date != '') {
                $query->whereDate('tanggal_penjualan', '<=', $request->end_date);
            }

            if ($request->filled('status') && $request->status != '') {
                $query->where('status', $request->status);
            }

            if ($request->filled('payment_method') && $request->payment_method != '') {
                $query->where('payment_method', $request->payment_method);
            }

            if ($request->filled('pelanggan_id') && $request->pelanggan_id != '') {
                $query->where('pelanggan_id', $request->pelanggan_id);
            }

            return datatables()->eloquent($query)
                ->addIndexColumn()
                ->orderColumn('tanggal_penjualan', function ($query, $order) {
                    $query->orderBy('tanggal_penjualan', $order);
                })
                ->addColumn('tanggal', function ($row) {
                    return $row->tanggal_penjualan ? $row->tanggal_penjualan->format('d/m/Y') : '-';
                })
                ->addColumn('pelanggan', function ($row) {
                    return $row->pelanggan ? $row->pelanggan->nama : 'Umum';
                })
                ->addColumn('items', function ($row) {
                    $count = $row->detailPenjualans ? $row->detailPenjualans->count() : 0;
                    return '<span class="badge badge-secondary">' . $count . ' item</span>';
                })
                ->addColumn('total_harga', function ($row) {
                    return '<div class="text-right">Rp ' . number_format($row->total_harga ?? 0, 0, ',', '.') . '</div>';
                })
                ->addColumn('diskon', function ($row) {
                    return '<div class="text-right">Rp ' . number_format($row->diskon ?? 0, 0, ',', '.') . '</div>';
                })
                ->addColumn('grand_total', function ($row) {
                    return '<div class="text-right font-weight-bold">Rp ' . number_format($row->grand_total ?? 0, 0, ',', '.') . '</div>';
                })
                ->addColumn('bayar', function ($row) {
                    return '<div class="text-right">Rp ' . number_format($row->bayar ?? 0, 0, ',', '.') . '</div>';
                })
                ->addColumn('sisa', function ($row) {
                    $class = $row->sisa > 0 ? 'text-danger' : 'text-success';
                    return '<div class="text-right ' . $class . '">Rp ' . number_format($row->sisa ?? 0, 0, ',', '.') . '</div>';
                })
                ->addColumn('status_badge', function ($row) {
                    $badges = [
                        'Pending' => 'secondary',
                        'Lunas' => 'success',
                        'Belum Lunas' => 'warning'
                    ];
                    $badge = $badges[$row->status] ?? 'secondary';
                    return '<span class="badge badge-' . $badge . '">' . $row->status . '</span>';
                })
                ->addColumn('payment_badge', function ($row) {
                    $badges = [
                        'Cash' => 'primary',
                        'Credit' => 'info'
                    ];
                    $badge = $badges[$row->payment_method] ?? 'secondary';
                    return '<span class="badge badge-' . $badge . '">' . $row->payment_method . '</span>';
                })
                ->addColumn('kasir', function ($row) {
                    return $row->createdBy ? $row->createdBy->nama_depan . ' ' . $row->createdBy->nama_belakang : '-';
                })
                ->addColumn('action', function ($row) {
                    return '<button class="btn btn-sm btn-info btn-detail" data-id="' . $row->id . '" title="Detail">
                                <i class="ft-eye"></i>
                            </button>';
                })
                ->rawColumns(['items', 'total_harga', 'diskon', 'grand_total', 'bayar', 'sisa', 'status_badge', 'payment_badge', 'action'])
                ->make(true);
        }

        if ($request->ajax() && $request->has('summary_only')) {
            $summary = $this->getSummary($request);
            return response()->json($summary);
        }

        $summary = $this->getSummary($request);

        return view('pages.laporan.penjualan.laporanpenjualan', compact('title', 'pelanggans', 'summary'));
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

    private function getSummary(Request $request)
    {
        $query = Penjualan::query();

        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_penjualan', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_penjualan', '<=', $request->end_date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('pelanggan_id')) {
            $query->where('pelanggan_id', $request->pelanggan_id);
        }

        return [
            'total_transaksi' => $query->count(),
            'total_penjualan' => $query->sum('grand_total'),
            'total_diskon' => $query->sum('diskon'),
            'total_bayar' => $query->sum('bayar'),
            'total_sisa' => $query->sum('sisa'),
        ];
    }

    public function detail($id)
    {
        try {
            $penjualan = Penjualan::with(['pelanggan', 'createdBy', 'detailPenjualans.barang'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $penjualan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan: ' . $e->getMessage()
            ], 404);
        }
    }
}
