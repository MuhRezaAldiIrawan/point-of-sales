<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PelangganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pelanggans = [
            [
                'nama' => 'John Doe',
                'alamat' => 'Jl. Merdeka No. 1',
                'no_hp' => '081234567890',
                'kota' => 'Jakarta',
                'status_bayar' => 'Tunai',
                'batas_kredit' => 0,
                'keterangan' => 'Pelanggan baru',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        foreach ($pelanggans as $pelanggan) {
            DB::table('pelanggans')->updateOrInsert(
                ['no_hp' => $pelanggan['no_hp']],
                $pelanggan
            );
        }
    }
}
