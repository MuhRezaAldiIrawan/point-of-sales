<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jabatans = [
            [
                'id' => 1,
                'nama' => 'Owner',
                'deskripsi' => 'Pemilik bisnis dengan akses penuh ke semua fitur dan data',
                'created_by' => 2,
                'updated_by' => 2,
                'deleted_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'nama' => 'Administrator',
                'deskripsi' => 'Mengelola sistem, pengguna, dan konfigurasi aplikasi',
                'created_by' => 2,
                'updated_by' => 2,
                'deleted_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'nama' => 'Kasir',
                'deskripsi' => 'Melayani transaksi penjualan dan pembayaran pelanggan',
                'created_by' => 2,
                'updated_by' => 2,
                'deleted_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'nama' => 'HR',
                'deskripsi' => 'Mengelola sumber daya manusia, rekrutmen, dan administrasi karyawan',
                'created_by' => 2,
                'updated_by' => 2,
                'deleted_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'nama' => 'Purchasing',
                'deskripsi' => 'Mengelola pengadaan barang, inventory, dan master data produk',
                'created_by' => 2,
                'updated_by' => 2,
                'deleted_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($jabatans as $jabatan) {
            DB::table('jabatans')->updateOrInsert(
                ['id' => $jabatan['id']],
                $jabatan
            );
        }
    }
}
