<?php

namespace App\Http\Controllers\LaporanInventori;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\BarangMasuk;
use Carbon\Carbon;

class LaporanBarangMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Laporan Barang Masuk';

        return view('pages.laporan.inventori.barangmasuk.laporanbarangmasuk', compact('title'));
    }

    /**
     * Get data for DataTables
     */
    public function data(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $query = BarangMasuk::with(['jenisStok', 'detailBarangMasuk.barang', 'detailBarangMasuk.satuan', 'createdBy', 'cancelledBy']);

        // Filter berdasarkan tanggal jika disediakan
        if ($startDate && $endDate) {
            $query->whereBetween('tanggal_masuk', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('tanggal_formatted', function ($row) {
                return $row->tanggal_masuk->format('d/m/Y');
            })
            ->addColumn('jenis_stok', function ($row) {
                return $row->jenisStok->nama ?? '-';
            })
            ->addColumn('total_item', function ($row) {
                return $row->detailBarangMasuk->sum('jumlah');
            })
            ->addColumn('total_nilai', function ($row) {
                return 'Rp ' . number_format($row->detailBarangMasuk->sum('total'), 0, ',', '.');
            })
            ->addColumn('status_badge', function ($row) {
                if ($row->isCancelled()) {
                    return '<span class="badge badge-danger">Cancelled</span>';
                }

                return '<span class="badge badge-success">Success</span>';
            })
            ->addColumn('cancel_reason_display', function ($row) {
                if (! $row->isCancelled()) {
                    return '-';
                }

                return $row->cancel_reason ?: '-';
            })
            ->addColumn('created_by_name', function ($row) {
                return $row->createdBy->name ?? '-';
            })
            ->addColumn('detail_items', function ($row) {
                $details = $row->detailBarangMasuk->map(function ($detail) {
                    return ($detail->barang->nama_barang ?? '-') . ' (' . $detail->jumlah . ' ' . ($detail->satuan->nama ?? '') . ')';
                })->implode(', ');

                return $details ?: '-';
            })
            ->addColumn('action', function ($row) {
                return '<button class="btn btn-sm btn-info" onclick="showDetail(' . $row->id . ')">
                            <i class="fa fa-eye"></i> Detail
                        </button>';
            })
            ->rawColumns(['status_badge', 'action'])
            ->make(true);
    }

    /**
     * Get detail barang masuk
     */
    public function detail($id)
    {
        $barangMasuk = BarangMasuk::with(['jenisStok', 'detailBarangMasuk.barang', 'detailBarangMasuk.satuan', 'createdBy', 'cancelledBy'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $barangMasuk
        ]);
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
