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
                'created_by' => null,
                'updated_by' => null,
                'deleted_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 2,
                'updated_by' => 2
            ],
            [
                'id' => 2,
                'nama' => 'Administrator',
                'deskripsi' => 'Mengelola sistem, pengguna, dan konfigurasi aplikasi',
                'created_by' => null,
                'updated_by' => null,
                'deleted_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 2,
                'updated_by' => 2
            ],
            [
                'id' => 3,
                'nama' => 'Kasir',
                'deskripsi' => 'Melayani transaksi penjualan dan pembayaran pelanggan',
                'created_by' => null,
                'updated_by' => null,
                'deleted_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 2,
                'updated_by' => 2
            ],
            [
                'id' => 4,
                'nama' => 'HR',
                'deskripsi' => 'Mengelola sumber daya manusia, rekrutmen, dan administrasi karyawan',
                'created_by' => null,
                'updated_by' => null,
                'deleted_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 2,
                'updated_by' => 2
            ],
        ];

        DB::table('jabatans')->insert($jabatans);
    }
}
