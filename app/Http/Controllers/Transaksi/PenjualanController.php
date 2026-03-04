<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\DetailBarangKeluar;
use App\Models\DetailPenjualan;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class PenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $penjualans = Penjualan::with(['pelanggan', 'createdBy'])
                ->select(['id', 'no_invoice', 'tanggal_penjualan', 'pelanggan_id', 'grand_total', 'status', 'payment_method', 'created_by', 'created_at'])
                ->orderBy('created_at', 'desc');

            // Filter by date range
            if ($request->has('date_range') && !empty($request->date_range)) {
                $dates = explode(' - ', $request->date_range);
                if (count($dates) == 2) {
                    $startDate = \Carbon\Carbon::createFromFormat('d/m/Y', trim($dates[0]))->startOfDay();
                    $endDate = \Carbon\Carbon::createFromFormat('d/m/Y', trim($dates[1]))->endOfDay();
                    $penjualans->whereBetween('tanggal_penjualan', [$startDate, $endDate]);
                }
            }

            // Filter by status
            if ($request->has('status') && !empty($request->status)) {
                $penjualans->where('status', $request->status);
            }

            // Filter by payment method
            if ($request->has('payment') && !empty($request->payment)) {
                $penjualans->where('payment_method', $request->payment);
            }

            // Filter by pelanggan name
            if ($request->has('pelanggan') && !empty($request->pelanggan)) {
                $penjualans->whereHas('pelanggan', function($query) use ($request) {
                    $query->where('nama', 'like', '%' . $request->pelanggan . '%');
                });
            }

            return datatables()->of($penjualans)
                ->addColumn('tanggal_penjualan', function($penjualan) {
                    return $penjualan->tanggal_penjualan->format('d/m/Y');
                })
                ->addColumn('grand_total', function($penjualan) {
                    return $penjualan->grand_total;
                })
                ->addColumn('created_by_name', function($penjualan) {
                    if ($penjualan->createdBy) {
                        return trim($penjualan->createdBy->nama_depan . ' ' . ($penjualan->createdBy->nama_belakang ?? ''));
                    }
                    return '-';
                })
                ->addColumn('actions', function($penjualan) {
                    return '';
                })
                ->addColumn('show_url', function($penjualan) {
                    return route('penjualan.show', $penjualan->id);
                })
                ->addColumn('edit_url', function($penjualan) {
                    return route('penjualan.edit', $penjualan->id);
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        $title = 'Penjualan';

        return view('pages.transaksi.penjualan.penjualan', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Tambah Penjualan';
        $setting = \App\Models\Setting::first();
        $ppnRate = $setting ? $setting->ppn : 11;

        return view('pages.transaksi.penjualan._partials.form', compact('title', 'ppnRate'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal_penjualan' => 'required|date',
            'pelanggan_id' => 'required|exists:pelanggans,id',
            'ppn' => 'required|in:PPN,Non PPN',
            'payment_method' => 'required|in:Cash,Credit',
            'jatuh_tempo' => 'nullable|date|required_if:payment_method,Credit',
            'items' => 'required|array|min:1',
            'items.*.barang_id' => 'required|exists:barangs,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga' => 'required|numeric|min:0',
            'items.*.bonus' => 'nullable|integer|min:0',
            'items.*.diskon' => 'nullable|numeric|min:0|max:100',
            'biaya_kirim' => 'nullable|numeric|min:0',
            'biaya_lain' => 'nullable|numeric|min:0',
            'diskon_total' => 'nullable|numeric|min:0|max:100',
            'bayar' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Calculate totals
            $subtotal = 0;
            $setting = \App\Models\Setting::first();
            $ppnRate = $setting ? ($setting->ppn / 100) : 0.11; // Dynamic PPN from settings

            foreach ($request->items as $item) {
                $jumlah = $item['jumlah'];
                $harga = $item['harga'];
                $diskon = $item['diskon'] ?? 0;
                $itemSubtotal = $jumlah * $harga * (1 - $diskon / 100);
                $subtotal += $itemSubtotal;
            }

            $biayaKirim = $request->biaya_kirim ?? 0;
            $biayaLain = $request->biaya_lain ?? 0;
            $diskonTotal = $request->diskon_total ?? 0;
            $bayar = $request->bayar;

            // Calculate discount amount
            $diskonAmount = $subtotal * ($diskonTotal / 100);

            // Subtotal after discount
            $totalSetelahDiskon = $subtotal - $diskonAmount;

            // Add shipping and other costs
            $totalSetelahDiskon += $biayaKirim + $biayaLain;

            $ppnAmount = 0;
            $grandTotal = $totalSetelahDiskon;

            // Calculate PPN based on type
            if ($request->ppn === 'PPN') {
                $ppnAmount = $totalSetelahDiskon * $ppnRate;
                $grandTotal = $totalSetelahDiskon + $ppnAmount;
            } else { // Non PPN
                $ppnAmount = 0;
                $grandTotal = $totalSetelahDiskon;
            }

            $kembalian = max(0, $bayar - $grandTotal);
            $sisa = max(0, $grandTotal - $bayar);
            $status = $sisa > 0 ? 'Belum Lunas' : 'Lunas';

            $penjualan = Penjualan::create([
                'no_invoice' => (new Penjualan())->generateNoInvoice(),
                'tanggal_penjualan' => $request->tanggal_penjualan,
                'pelanggan_id' => $request->pelanggan_id,
                'ppn' => $request->ppn,
                'payment_method' => $request->payment_method,
                'jatuh_tempo' => $request->jatuh_tempo,
                'total_harga' => $subtotal,
                'diskon' => $diskonTotal,
                'ppn_amount' => $ppnAmount,
                'biaya_kirim' => $biayaKirim,
                'biaya_lain' => $biayaLain,
                'grand_total' => $grandTotal,
                'bayar' => $bayar,
                'sisa' => $sisa,
                'kembalian' => $kembalian,
                'status' => $status,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id()
            ]);

            // Create Barang Keluar for stock reduction
            // Find or create jenis stok for Penjualan
            $jenisStok = \App\Models\JenisStok::firstOrCreate(
                ['nama' => 'Penjualan'],
                ['created_by' => auth()->id(), 'updated_by' => auth()->id()]
            );

            $barangKeluar = BarangKeluar::create([
                'no_reff' => $penjualan->no_invoice,
                'tanggal_keluar' => $request->tanggal_penjualan,
                'jenis_stok_id' => $jenisStok->id,
                'catatan' => 'Barang keluar dari penjualan ' . $penjualan->no_invoice,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id()
            ]);

            foreach ($request->items as $item) {
                $jumlah = $item['jumlah'];
                $bonus = $item['bonus'] ?? 0;
                $harga = $item['harga'];
                $diskon = $item['diskon'] ?? 0;

                $subtotal = ($jumlah + $bonus) * $harga * (1 - $diskon / 100);

                // Get barang detail for satuan_id and isi
                $barang = Barang::with('detailBarang.satuan')->find($item['barang_id']);
                $detailBarang = $barang->detailBarang->first();

                if (!$detailBarang) {
                    throw new \Exception('Detail barang tidak ditemukan untuk ' . $barang->nama_barang);
                }

                // Create detail penjualan
                DetailPenjualan::create([
                    'penjualan_id' => $penjualan->id,
                    'barang_id' => $item['barang_id'],
                    'jumlah' => $jumlah,
                    'harga_satuan' => $harga,
                    'bonus' => $bonus,
                    'diskon_item' => $diskon,
                    'subtotal' => $subtotal,
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id()
                ]);

                // Create detail barang keluar to reduce stock
                DetailBarangKeluar::create([
                    'barang_keluar_id' => $barangKeluar->id,
                    'barang_id' => $item['barang_id'],
                    'satuan_id' => $detailBarang->satuan_id,
                    'isi' => $detailBarang->isi ?? 1,
                    'jumlah' => $jumlah + $bonus,
                    'harga_jual' => $harga,
                    'total' => $subtotal,
                    'stok_ppn' => $request->ppn === 'PPN' ? 'PPN' : 'Non PPN',
                    'keterangan' => 'Penjualan kepada ' . Pelanggan::find($request->pelanggan_id)->nama,
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id()
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi penjualan berhasil disimpan',
                'data' => $penjualan
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $penjualan = Penjualan::with(['pelanggan', 'createdBy', 'updatedBy', 'detailPenjualans.barang'])->findOrFail($id);
        $title = 'Detail Penjualan - ' . $penjualan->no_invoice;
        $setting = \App\Models\Setting::first();

        return view('pages.transaksi.penjualan._partials.show', compact('penjualan', 'title', 'setting'));
    }

    /**
     * Print receipt for the specified resource.
     */
    public function print(string $id)
    {
        $penjualan = Penjualan::with(['pelanggan', 'createdBy', 'updatedBy', 'detailPenjualans.barang'])->findOrFail($id);
        $setting = \App\Models\Setting::first();

        return view('pages.transaksi.penjualan.struk', compact('penjualan', 'setting'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $penjualan = Penjualan::with(['pelanggan', 'detailPenjualans.barang.detailBarang.satuan'])->findOrFail($id);
        $title = 'Edit Penjualan - ' . $penjualan->no_invoice;
        $setting = \App\Models\Setting::first();
        $ppnRate = $setting ? $setting->ppn : 11;

        // Calculate real-time stock for each item including current transaction
        foreach ($penjualan->detailPenjualans as $detail) {
            $stokMasuk = (float) DB::table('detail_barang_masuks')
                ->join('barang_masuks', 'detail_barang_masuks.barang_masuk_id', '=', 'barang_masuks.id')
                ->where('detail_barang_masuks.barang_id', $detail->barang_id)
                ->whereNull('detail_barang_masuks.deleted_at')
                ->whereNull('barang_masuks.deleted_at')
                ->sum('detail_barang_masuks.jumlah');

            $stokKeluar = (float) DB::table('detail_barang_keluars')
                ->join('barang_keluars', 'detail_barang_keluars.barang_keluar_id', '=', 'barang_keluars.id')
                ->where('detail_barang_keluars.barang_id', $detail->barang_id)
                ->whereNull('detail_barang_keluars.deleted_at')
                ->whereNull('barang_keluars.deleted_at')
                ->sum('detail_barang_keluars.jumlah');

            // Add back the stock from current transaction
            $barangKeluar = BarangKeluar::where('no_reff', $penjualan->no_invoice)->first();
            if ($barangKeluar) {
                $currentStokKeluar = (float) DB::table('detail_barang_keluars')
                    ->where('barang_keluar_id', $barangKeluar->id)
                    ->where('barang_id', $detail->barang_id)
                    ->whereNull('deleted_at')
                    ->sum('jumlah');
                $stokKeluar -= $currentStokKeluar;
            }

            $stok = $stokMasuk - $stokKeluar;
            $detail->stok_tersedia = max(0, $stok);
        }

        return view('pages.transaksi.penjualan.edit', compact('penjualan', 'title', 'ppnRate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $penjualan = Penjualan::findOrFail($id);

        if ($penjualan->status === 'Lunas') {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi yang sudah Lunas tidak dapat diubah'
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'tanggal_penjualan' => 'required|date',
            'pelanggan_id' => 'required|exists:pelanggans,id',
            'ppn' => 'required|in:PPN,Non PPN',
            'payment_method' => 'required|in:Cash,Credit',
            'jatuh_tempo' => 'nullable|date|required_if:payment_method,Credit',
            'items' => 'required|array|min:1',
            'items.*.barang_id' => 'required|exists:barangs,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga' => 'required|numeric|min:0',
            'items.*.bonus' => 'nullable|integer|min:0',
            'items.*.diskon' => 'nullable|numeric|min:0|max:100',
            'biaya_kirim' => 'nullable|numeric|min:0',
            'biaya_lain' => 'nullable|numeric|min:0',
            'diskon_total' => 'nullable|numeric|min:0|max:100',
            'bayar' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Delete old barang keluar
            $oldBarangKeluar = BarangKeluar::where('no_reff', $penjualan->no_invoice)->first();
            if ($oldBarangKeluar) {
                $oldBarangKeluar->detailBarangKeluar()->delete();
                $oldBarangKeluar->delete();
            }

            // Calculate totals
            $subtotal = 0;
            $setting = \App\Models\Setting::first();
            $ppnRate = $setting ? ($setting->ppn / 100) : 0.11; // Dynamic PPN from settings

            foreach ($request->items as $item) {
                $jumlah = $item['jumlah'];
                $harga = $item['harga'];
                $diskon = $item['diskon'] ?? 0;
                $itemSubtotal = $jumlah * $harga * (1 - $diskon / 100);
                $subtotal += $itemSubtotal;
            }

            $biayaKirim = $request->biaya_kirim ?? 0;
            $biayaLain = $request->biaya_lain ?? 0;
            $diskonTotal = $request->diskon_total ?? 0;
            $bayar = $request->bayar;

            // Calculate discount amount
            $diskonAmount = $subtotal * ($diskonTotal / 100);

            // Subtotal after discount
            $totalSetelahDiskon = $subtotal - $diskonAmount;

            // Add shipping and other costs
            $totalSetelahDiskon += $biayaKirim + $biayaLain;

            $ppnAmount = 0;
            $grandTotal = $totalSetelahDiskon;

            if ($request->ppn === 'PPN') {
                $ppnAmount = $totalSetelahDiskon * $ppnRate;
                $grandTotal = $totalSetelahDiskon + $ppnAmount;
            }

            $kembalian = max(0, $bayar - $grandTotal);
            $sisa = max(0, $grandTotal - $bayar);
            $status = $sisa > 0 ? 'Belum Lunas' : 'Lunas';

            $penjualan->update([
                'tanggal_penjualan' => $request->tanggal_penjualan,
                'pelanggan_id' => $request->pelanggan_id,
                'ppn' => $request->ppn,
                'payment_method' => $request->payment_method,
                'jatuh_tempo' => $request->jatuh_tempo,
                'total_harga' => $subtotal,
                'diskon' => $diskonTotal,
                'ppn_amount' => $ppnAmount,
                'biaya_kirim' => $biayaKirim,
                'biaya_lain' => $biayaLain,
                'grand_total' => $grandTotal,
                'bayar' => $bayar,
                'sisa' => $sisa,
                'kembalian' => $kembalian,
                'status' => $status,
                'updated_by' => auth()->id()
            ]);

            // Delete existing detail penjualan
            $penjualan->detailPenjualans()->delete();

            // Create new barang keluar
            $jenisStok = \App\Models\JenisStok::firstOrCreate(
                ['nama' => 'Penjualan'],
                ['created_by' => auth()->id(), 'updated_by' => auth()->id()]
            );

            $barangKeluar = BarangKeluar::create([
                'no_reff' => $penjualan->no_invoice,
                'tanggal_keluar' => $request->tanggal_penjualan,
                'jenis_stok_id' => $jenisStok->id,
                'catatan' => 'Barang keluar dari penjualan ' . $penjualan->no_invoice,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id()
            ]);

            // Create new detail penjualan and barang keluar
            foreach ($request->items as $item) {
                $jumlah = $item['jumlah'];
                $bonus = $item['bonus'] ?? 0;
                $harga = $item['harga'];
                $diskon = $item['diskon'] ?? 0;

                $subtotal = ($jumlah + $bonus) * $harga * (1 - $diskon / 100);

                $barang = Barang::with('detailBarang.satuan')->find($item['barang_id']);
                $detailBarang = $barang->detailBarang->first();

                if (!$detailBarang) {
                    throw new \Exception('Detail barang tidak ditemukan untuk ' . $barang->nama_barang);
                }

                DetailPenjualan::create([
                    'penjualan_id' => $penjualan->id,
                    'barang_id' => $item['barang_id'],
                    'jumlah' => $jumlah,
                    'harga_satuan' => $harga,
                    'bonus' => $bonus,
                    'diskon_item' => $diskon,
                    'subtotal' => $subtotal,
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id()
                ]);

                DetailBarangKeluar::create([
                    'barang_keluar_id' => $barangKeluar->id,
                    'barang_id' => $item['barang_id'],
                    'satuan_id' => $detailBarang->satuan_id,
                    'isi' => $detailBarang->isi ?? 1,
                    'jumlah' => $jumlah + $bonus,
                    'harga_jual' => $harga,
                    'total' => $subtotal,
                    'stok_ppn' => $request->ppn === 'PPN' ? 'PPN' : 'Non PPN',
                    'keterangan' => 'Penjualan kepada ' . Pelanggan::find($request->pelanggan_id)->nama,
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id()
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi penjualan berhasil diperbarui',
                'data' => $penjualan
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $penjualan = Penjualan::findOrFail($id);

        try {
            DB::beginTransaction();

            // Delete barang keluar related to this penjualan
            $barangKeluar = BarangKeluar::where('no_reff', $penjualan->no_invoice)->first();
            if ($barangKeluar) {
                // Delete detail barang keluar first (this will restore stock)
                $barangKeluar->detailBarangKeluar()->delete();
                $barangKeluar->delete();
            }

            // Delete detail penjualan
            $penjualan->detailPenjualans()->delete();

            // Delete penjualan
            $penjualan->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi penjualan berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Get pelanggan data for select2
     */
    public function getPelanggan(Request $request)
    {
        $search = $request->get('q');
        $pelanggans = Pelanggan::where('nama', 'like', '%' . $search . '%')
            ->limit(10)
            ->get(['id', 'nama', 'alamat', 'no_hp']);

        return response()->json([
            'results' => $pelanggans->map(function ($pelanggan) {
                return [
                    'id' => $pelanggan->id,
                    'text' => $pelanggan->nama,
                    'nama' => $pelanggan->nama,
                    'alamat' => $pelanggan->alamat,
                    'telepon' => $pelanggan->no_hp
                ];
            })
        ]);
    }

    /**
     * API: Get produk/barang data for select2
     */
    public function getProduk(Request $request)
    {
        $search = $request->get('q');
        $barangs = Barang::with(['jenisBarang', 'merek', 'detailBarang.satuan'])
            ->where(function($query) use ($search) {
                $query->where('nama_barang', 'like', '%' . $search . '%')
                      ->orWhere('kode', 'like', '%' . $search . '%');
            })
            ->limit(10)
            ->get();

        $results = [];

        foreach ($barangs as $barang) {
            $detailBarang = $barang->detailBarang->first();

            if (!$detailBarang) {
                continue;
            }

            // Calculate stock from barang_masuk (total stock in)
            $stokMasuk = (float) DB::table('detail_barang_masuks')
                ->join('barang_masuks', 'detail_barang_masuks.barang_masuk_id', '=', 'barang_masuks.id')
                ->where('detail_barang_masuks.barang_id', $barang->id)
                ->whereNull('detail_barang_masuks.deleted_at')
                ->whereNull('barang_masuks.deleted_at')
                ->sum('detail_barang_masuks.jumlah');

            // Calculate stock from barang_keluar (total stock out)
            $stokKeluar = (float) DB::table('detail_barang_keluars')
                ->join('barang_keluars', 'detail_barang_keluars.barang_keluar_id', '=', 'barang_keluars.id')
                ->where('detail_barang_keluars.barang_id', $barang->id)
                ->whereNull('detail_barang_keluars.deleted_at')
                ->whereNull('barang_keluars.deleted_at')
                ->sum('detail_barang_keluars.jumlah');

            // Calculate remaining stock
            $stok = $stokMasuk - $stokKeluar;

            // Debug: Log stock calculation
            Log::info("Barang: {$barang->nama_barang}, Stok Masuk: {$stokMasuk}, Stok Keluar: {$stokKeluar}, Stok Sisa: {$stok}");

            // Only show products with stock > 0
            if ($stok <= 0) {
                continue;
            }

            $results[] = [
                'id' => $barang->id,
                'text' => $barang->nama_barang . ' (' . $barang->kode . ') - Stok: ' . $stok . ' ' . $detailBarang->satuan->nama_satuan,
                'nama_barang' => $barang->nama_barang,
                'stok' => $stok,
                'harga_jual' => $detailBarang->harga_jual,
                'satuan' => $detailBarang->satuan->nama_satuan
            ];
        }

        return response()->json(['results' => $results]);
    }

    /**
     * API: Get detail produk by ID
     */
    public function getProdukDetail($id)
    {
        $barang = Barang::with(['jenisBarang', 'merek', 'satuan'])->findOrFail($id);

        return response()->json([
            'id' => $barang->id,
            'nama_barang' => $barang->nama_barang,
            'stok' => $barang->stok,
            'harga_jual' => $barang->harga_jual,
            'satuan' => $barang->satuan->nama_satuan,
            'jenis' => $barang->jenisBarang->nama_jenis,
            'merek' => $barang->merek->nama_merek,
            'deskripsi' => $barang->deskripsi
        ]);
    }
}
