<?php

namespace App\Http\Controllers\Inventori;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\Barang;
use App\Models\DetailBarangMasuk;
use App\Models\DetailBarangKeluar;

class StokMinimController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = 'Stok Minim';

        if ($request->ajax()) {
            $barangs = Barang::with(['merek', 'jenisBarang'])->get();

            $stokData = [];

            foreach ($barangs as $barang) {
                $qtyMasuk = DetailBarangMasuk::activeStock()->where('barang_id', $barang->id)->sum('jumlah');
                $qtyKeluar = DetailBarangKeluar::activeStock()->where('barang_id', $barang->id)->sum('jumlah');
                $qtyStok = $qtyMasuk - $qtyKeluar;

                // Filter hanya stok yang <= 10
                if ($qtyStok <= 10) {
                    $lastDetailMasuk = DetailBarangMasuk::activeStock()->where('barang_id', $barang->id)->latest()->first();
                    $ppnStatus = $lastDetailMasuk ? ($lastDetailMasuk->stok_ppn ?? 'Non PPN') : 'Non PPN';

                    $stokData[] = [
                        'id' => $barang->id,
                        'kode' => $barang->kode,
                        'nama_barang' => $barang->nama_barang,
                        'merek' => $barang->merek->nama ?? '-',
                        'jenis' => $barang->jenisBarang->nama ?? '-',
                        'ppn' => $ppnStatus,
                        'qty' => $qtyStok,
                        'qty_in' => $qtyMasuk,
                        'qty_out' => $qtyKeluar
                    ];
                }
            }

            return DataTables::of(collect($stokData))
                ->addIndexColumn()
                ->addColumn('ppn_badge', function ($row) {
                    if ($row['ppn'] === 'PPN') {
                        return '<span class="badge badge-success">PPN</span>';
                    } else {
                        return '<span class="badge badge-secondary">Non PPN</span>';
                    }
                })
                ->addColumn('qty_formatted', function ($row) {
                    $class = 'text-danger'; // Default merah untuk stok minim
                    if ($row['qty'] < 0) {
                        $class = 'text-danger font-weight-bold'; // Bold untuk stok minus
                    } elseif ($row['qty'] == 0) {
                        $class = 'text-warning font-weight-bold'; // Bold untuk stok habis
                    } elseif ($row['qty'] <= 5) {
                        $class = 'text-danger'; // Merah untuk stok sangat minim
                    }
                    return '<span class="' . $class . '">' . number_format($row['qty']) . '</span>';
                })
                ->addColumn('qty_in_formatted', function ($row) {
                    return '<span class="text-primary">' . number_format($row['qty_in']) . '</span>';
                })
                ->addColumn('qty_out_formatted', function ($row) {
                    return '<span class="text-danger">' . number_format($row['qty_out']) . '</span>';
                })
                ->addColumn('status_alert', function ($row) {
                    if ($row['qty'] < 0) {
                        return '<i class="feather icon-alert-triangle text-danger"></i> <small class="text-danger">Stok Minus</small>';
                    } elseif ($row['qty'] == 0) {
                        return '<i class="feather icon-alert-circle text-warning"></i> <small class="text-warning">Stok Habis</small>';
                    } elseif ($row['qty'] <= 5) {
                        return '<i class="feather icon-alert-triangle text-danger"></i> <small class="text-danger">Stok Kritis</small>';
                    } else {
                        return '<i class="feather icon-info text-info"></i> <small class="text-info">Stok Minim</small>';
                    }
                })
                ->rawColumns(['ppn_badge', 'qty_formatted', 'qty_in_formatted', 'qty_out_formatted', 'status_alert'])
                ->make(true);
        }

        return view('pages.inventori.stokminim.stokminim', compact('title'));
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
