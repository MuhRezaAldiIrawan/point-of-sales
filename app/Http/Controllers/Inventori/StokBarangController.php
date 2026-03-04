<?php

namespace App\Http\Controllers\Inventori;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\Barang;
use App\Models\DetailBarangMasuk;
use App\Models\DetailBarangKeluar;
use Illuminate\Support\Facades\DB;

class StokBarangController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Stok Barang';

        if ($request->ajax()) {
            $filter = $request->get('filter', 'all'); // Default to 'all'

            $barangs = Barang::with(['merek', 'jenisBarang'])->get();

            $stokData = [];

            foreach ($barangs as $barang) {
                $qtyMasuk = DetailBarangMasuk::where('barang_id', $barang->id)->sum('jumlah');
                $qtyKeluar = DetailBarangKeluar::where('barang_id', $barang->id)->sum('jumlah');
                $qtyStok = $qtyMasuk - $qtyKeluar;

                // Apply filter
                if ($filter === 'minim' && $qtyStok > 10) {
                    continue; // Skip items that don't match the filter
                }

                $lastDetailMasuk = DetailBarangMasuk::where('barang_id', $barang->id)->latest()->first();
                $ppnStatus = $lastDetailMasuk ? $lastDetailMasuk->stok_ppn : 'Non PPN';

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
                    $class = 'text-success';
                    if ($row['qty'] < 0) {
                        $class = 'text-danger';
                    } elseif ($row['qty'] == 0) {
                        $class = 'text-warning';
                    }
                    return '<span class="' . $class . '">' . number_format($row['qty']) . '</span>';
                })
                ->addColumn('qty_in_formatted', function ($row) {
                    return '<span class="text-primary">' . number_format($row['qty_in']) . '</span>';
                })
                ->addColumn('qty_out_formatted', function ($row) {
                    return '<span class="text-danger">' . number_format($row['qty_out']) . '</span>';
                })
                ->rawColumns(['ppn_badge', 'qty_formatted', 'qty_in_formatted', 'qty_out_formatted'])
                ->make(true);
        }

        return view('pages.inventori.stokbarang.stokbarang', compact('title'));
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
