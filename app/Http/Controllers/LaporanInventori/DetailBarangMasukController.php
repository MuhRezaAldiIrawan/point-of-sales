<?php

namespace App\Http\Controllers\LaporanInventori;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\DetailBarangMasuk;
use App\Models\BarangMasuk;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DetailBarangMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Laporan Detail Barang Masuk';

        return view('pages.laporan.inventori.barangmasuk.detaillaporanbarangmasuk', compact('title'));
    }

    /**
     * Get data for DataTables
     */
    public function data(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $query = DetailBarangMasuk::with([
            'barangMasuk.jenisStok',
            'barangMasuk.createdBy',
            'barang',
            'satuan',
            'createdBy'
        ]);

        // Filter berdasarkan tanggal jika disediakan
        if ($startDate && $endDate) {
            $query->whereHas('barangMasuk', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('tanggal_masuk', [
                    Carbon::parse($startDate)->startOfDay(),
                    Carbon::parse($endDate)->endOfDay()
                ]);
            });
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('tanggal_formatted', function ($row) {
                return $row->barangMasuk->tanggal_masuk->format('d/m/Y');
            })
            ->addColumn('no_reff', function ($row) {
                return $row->barangMasuk->no_reff ?? '-';
            })
            ->addColumn('jenis_stok', function ($row) {
                return $row->barangMasuk->jenisStok->nama ?? '-';
            })
            ->addColumn('nama_barang', function ($row) {
                return $row->barang->nama_barang ?? '-';
            })
            ->addColumn('harga_beli_formatted', function ($row) {
                return 'Rp ' . number_format($row->harga_beli, 0, ',', '.');
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
     * Get detail barang masuk
     */
    public function detail($id)
    {
        try {
            $detailBarangMasuk = DetailBarangMasuk::with([
                'barangMasuk.jenisStok',
                'barangMasuk.createdBy',
                'barang',
                'satuan',
                'createdBy'
            ])->findOrFail($id);

            // Ensure barangMasuk relationship exists
            if (!$detailBarangMasuk->barangMasuk) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data barang masuk tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $detailBarangMasuk
            ]);
        } catch (\Exception $e) {
            Log::error("Error in DetailBarangMasukController::detail", [
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
