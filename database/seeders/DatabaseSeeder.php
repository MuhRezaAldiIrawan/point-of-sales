<?php

namespace Database\Seeders;

use App\Models\JenisStok;
use App\Models\Pelanggan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create test user only if not exists

        $this->call([
            JabatanSeeder::class,
            UserSeeder::class,
            SettingSeeder::class,
            JenisStokSeeder::class,
            SatuanSeeder::class,
            BankSeeder::class,
            PelangganSeeder::class,
            JenisBarangSeeder::class,
            SupplierSeeder::class,
        ]);
    }
}
