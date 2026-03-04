<?php

namespace App\Http\Controllers\LaporanPenjualan;

use App\Http\Controllers\Controller;
use App\Models\Penjualan;
use Illuminate\Http\Request;

class PembayaranKreditController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $penjualans = Penjualan::with(['pelanggan', 'createdBy'])
                ->where('payment_method', 'Credit')
                ->select(['id', 'no_invoice', 'tanggal_penjualan', 'jatuh_tempo', 'pelanggan_id', 'grand_total', 'bayar', 'sisa', 'status', 'created_by', 'created_at'])
                ->orderBy('created_at', 'desc');

            if ($request->has('date_range') && !empty($request->date_range)) {
                $dates = explode(' - ', $request->date_range);
                if (count($dates) == 2) {
                    $startDate = \Carbon\Carbon::createFromFormat('d/m/Y', trim($dates[0]))->startOfDay();
                    $endDate = \Carbon\Carbon::createFromFormat('d/m/Y', trim($dates[1]))->endOfDay();
                    $penjualans->whereBetween('tanggal_penjualan', [$startDate, $endDate]);
                }
            }

            if ($request->has('status') && !empty($request->status)) {
                $penjualans->where('status', $request->status);
            }

            if ($request->has('pelanggan') && !empty($request->pelanggan)) {
                $penjualans->whereHas('pelanggan', function($query) use ($request) {
                    $query->where('nama', 'like', '%' . $request->pelanggan . '%');
                });
            }

            return datatables()->of($penjualans)
                ->addColumn('tanggal_penjualan', function($penjualan) {
                    return $penjualan->tanggal_penjualan->format('d/m/Y');
                })
                ->addColumn('jatuh_tempo', function($penjualan) {
                    return $penjualan->jatuh_tempo ? $penjualan->jatuh_tempo->format('d/m/Y') : '-';
                })
                ->addColumn('grand_total', function($penjualan) {
                    return $penjualan->grand_total;
                })
                ->addColumn('bayar', function($penjualan) {
                    return $penjualan->bayar;
                })
                ->addColumn('sisa', function($penjualan) {
                    return $penjualan->sisa;
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
                ->rawColumns(['actions'])
                ->make(true);
        }

        $title = 'Laporan Pembayaran Kredit';

        return view('pages.laporan.penjualan.pembayarankredit', compact('title'));
    }

    public function show(string $id)
    {
        $penjualan = Penjualan::with(['pelanggan', 'createdBy', 'updatedBy', 'detailPenjualans.barang'])
            ->where('payment_method', 'Credit')
            ->findOrFail($id);

        $title = 'Detail Pembayaran Kredit - ' . $penjualan->no_invoice;
        $setting = \App\Models\Setting::first();

        return view('pages.laporan.penjualan._partials.detail', compact('penjualan', 'title', 'setting'));
    }
}
