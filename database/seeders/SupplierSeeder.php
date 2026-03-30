<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            [
                'nama' => 'PT. Sumber Makmur',
                'alamat' => 'Jl. Industri No. 10, Jakarta',
                'kota' => 'Jakarta',
                'no_hp' => '081298765432',
                'email' => 'info@sumbermakmur.co.id',
                'contact_person' => 'Budi Santoso',
                'telepon_contact_person' => '081212345678',
                'keterangan' => 'Supplier utama untuk elektronik',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'CV. Sentosa Abadi',
                'alamat' => 'Jl. Perdagangan No. 5, Bandung',
                'kota' => 'Bandung',
                'no_hp' => '082134567890',
                'email' => 'info@sentosaabadi.co.id',
                'contact_person' => 'Siti Aminah',
                'telepon_contact_person' => '082198765432',
                'keterangan' => 'Spesialis perlengkapan kantor',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($suppliers as $supplier) {
            DB::table('suppliers')->updateOrInsert(
                ['email' => $supplier['email']],
                $supplier
            );
        }
    }
}
