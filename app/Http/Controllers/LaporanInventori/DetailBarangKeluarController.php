<?php

namespace App\Http\Controllers\LaporanInventori;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\DetailBarangKeluar;
use App\Models\BarangKeluar;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DetailBarangKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Laporan Detail Barang Keluar';

        return view('pages.laporan.inventori.barangkeluar.detaillaporanbarangkeluar', compact('title'));
    }

    /**
     * Get data for DataTables
     */
    public function data(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $query = DetailBarangKeluar::with([
            'barangKeluar.jenisStok',
            'barangKeluar.createdBy',
            'barang',
            'satuan',
            'createdBy'
        ]);

        // Filter berdasarkan tanggal jika disediakan
        if ($startDate && $endDate) {
            $query->whereHas('barangKeluar', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('tanggal_keluar', [
                    Carbon::parse($startDate)->startOfDay(),
                    Carbon::parse($endDate)->endOfDay()
                ]);
            });
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('tanggal_formatted', function ($row) {
                return $row->barangKeluar->tanggal_keluar->format('d/m/Y');
            })
            ->addColumn('no_reff', function ($row) {
                return $row->barangKeluar->no_reff ?? '-';
            })
            ->addColumn('jenis_stok', function ($row) {
                return $row->barangKeluar->jenis_stok->nama ?? '-';
            })
            ->addColumn('nama_barang', function ($row) {
                return $row->barang->nama_barang ?? '-';
            })
            ->addColumn('harga_jual_formatted', function ($row) {
                return 'Rp ' . number_format($row->harga_jual, 0, ',', '.');
            })
            ->addColumn('total_formatted', function ($row) {
                return 'Rp ' . number_format($row->total, 0, ',', '.');
            })
            ->addColumn('created_by_name', function ($row) {
                return $row->createdBy->name ?? '-';
            })
            ->addColumn('action', function ($row) {
                return '<button class="btn btn-sm btn-info" onclick="showDetail(' . $row->id . ')">
                            <i class="fa fa-eye"></i> Detail
                        </button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

        /**
     * Get detail barang keluar
     */
    public function detail($id)
    {
        try {
            $detailBarangKeluar = DetailBarangKeluar::with([
                'barangKeluar.jenisStok',
                'barangKeluar.createdBy',
                'barang',
                'satuan',
                'createdBy'
            ])->findOrFail($id);

            // Ensure barangKeluar relationship exists
            if (!$detailBarangKeluar->barangKeluar) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data barang keluar tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $detailBarangKeluar
            ]);
        } catch (\Exception $e) {
            Log::error("Error in DetailBarangKeluarController::detail", [
                'id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
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
