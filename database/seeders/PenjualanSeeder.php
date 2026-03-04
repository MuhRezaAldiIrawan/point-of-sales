<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\Pelanggan;
use App\Models\Barang;
use App\Models\User;

class PenjualanSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        $pelanggan = Pelanggan::first();
        $barangs = Barang::limit(5)->get();

        if (!$user || !$pelanggan || $barangs->isEmpty()) {
            $this->command->warn('Pastikan ada data User, Pelanggan, dan Barang terlebih dahulu!');
            return;
        }

        for ($i = 1; $i <= 20; $i++) {
            $tanggal = now()->subDays(rand(0, 30));
            $status = ['Pending', 'Lunas', 'Belum Lunas'][rand(0, 2)];
            $paymentMethod = ['Cash', 'Credit'][rand(0, 1)];

            $totalHarga = 0;
            $detailsData = [];

            foreach ($barangs->random(rand(1, 3)) as $barang) {
                $jumlah = rand(1, 10);
                $hargaSatuan = rand(10000, 100000);
                $diskonItem = rand(0, 10000);
                $subtotal = ($jumlah * $hargaSatuan) - $diskonItem;
                $totalHarga += $subtotal;

                $detailsData[] = [
                    'barang_id' => $barang->id,
                    'jumlah' => $jumlah,
                    'harga_satuan' => $hargaSatuan,
                    'bonus' => rand(0, 2),
                    'diskon_item' => $diskonItem,
                    'subtotal' => $subtotal,
                    'created_by' => $user->id,
                    'updated_by' => $user->id,
                ];
            }

            $diskon = rand(0, 50000);
            $ppnAmount = $totalHarga * 0.11;
            $biayaKirim = rand(0, 20000);
            $biayaLain = rand(0, 10000);
            $grandTotal = ($totalHarga - $diskon + $ppnAmount + $biayaKirim + $biayaLain);

            if ($status === 'Lunas') {
                $bayar = $grandTotal;
                $sisa = 0;
                $kembalian = rand(0, 50000);
            } elseif ($status === 'Belum Lunas') {
                $bayar = $grandTotal * 0.5;
                $sisa = $grandTotal - $bayar;
                $kembalian = 0;
            } else {
                $bayar = 0;
                $sisa = $grandTotal;
                $kembalian = 0;
            }

            $penjualan = Penjualan::create([
                'no_invoice' => 'INV-' . $tanggal->format('YmdHis') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'tanggal_penjualan' => $tanggal,
                'ppn' => 11,
                'payment_method' => $paymentMethod,
                'jatuh_tempo' => $tanggal->copy()->addDays(30),
                'total_harga' => $totalHarga,
                'diskon' => $diskon,
                'ppn_amount' => $ppnAmount,
                'biaya_kirim' => $biayaKirim,
                'biaya_lain' => $biayaLain,
                'grand_total' => $grandTotal,
                'bayar' => $bayar,
                'sisa' => $sisa,
                'kembalian' => $kembalian,
                'status' => $status,
                'catatan' => 'Penjualan test #' . $i,
                'pelanggan_id' => $pelanggan->id,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);

            foreach ($detailsData as $detail) {
                $detail['penjualan_id'] = $penjualan->id;
                DetailPenjualan::create($detail);
            }
        }

        $this->command->info('20 data penjualan berhasil dibuat!');
    }
}
