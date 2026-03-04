<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Penjualan;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index()
    {
        $title = 'Pembayaran';
        $pembayarans = Penjualan::with(['pelanggan', 'createdBy'])
            ->orderBy('tanggal_penjualan', 'desc')
            ->get();

        return view('pages.transaksi.pembayaran.pembayaran', compact('title', 'pembayarans'));
    }

    public function show(string $id)
    {
        $pembayaran = Penjualan::with(['pelanggan', 'detailPenjualans.barang', 'createdBy', 'updatedBy'])
            ->findOrFail($id);

        return response()->json($pembayaran);
    }
}
