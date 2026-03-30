<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banks = [
            [
                'nama' => 'BCA',
                'no_rekening' => '1234567890',
                'atas_nama' => 'PT. Contoh Nama',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'MANDIRI',
                'no_rekening' => '0987654321',
                'atas_nama' => 'PT. Contoh Nama',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'BNI',
                'no_rekening' => '1122334455',
                'atas_nama' => 'PT. Contoh Nama',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'BRI',
                'no_rekening' => '5566778899',
                'atas_nama' => 'PT. Contoh Nama',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($banks as $bank) {
            DB::table('banks')->updateOrInsert(
                ['nama' => $bank['nama']],
                $bank
            );
        }
    }
}
