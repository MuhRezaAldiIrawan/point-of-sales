<?php

namespace App\Http\Controllers\LaporanInventori;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\BarangKeluar;
use Carbon\Carbon;

class LaporanBarangKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Laporan Barang Keluar';

        return view('pages.laporan.inventori.barangkeluar.laporanbarangkeluar', compact('title'));
    }

    /**
     * Get data for DataTables
     */
    public function data(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $query = BarangKeluar::with(['jenisStok', 'detailBarangKeluar.barang', 'detailBarangKeluar.satuan', 'createdBy', 'cancelledBy']);

        // Filter berdasarkan tanggal jika disediakan
        if ($startDate && $endDate) {
            $query->whereBetween('tanggal_keluar', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('tanggal_formatted', function ($row) {
                return $row->tanggal_keluar->format('d/m/Y');
            })
            ->addColumn('jenis_stok', function ($row) {
                return $row->jenisStok->nama ?? '-';
            })
            ->addColumn('total_item', function ($row) {
                return $row->detailBarangKeluar->sum('jumlah');
            })
            ->addColumn('total_nilai', function ($row) {
                return 'Rp ' . number_format($row->detailBarangKeluar->sum('total'), 0, ',', '.');
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
                $details = $row->detailBarangKeluar->map(function ($detail) {
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
     * Get detail barang keluar
     */
    public function detail($id)
    {
        $barangKeluar = BarangKeluar::with(['jenisStok', 'detailBarangKeluar.barang', 'detailBarangKeluar.satuan', 'createdBy', 'cancelledBy'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $barangKeluar
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
