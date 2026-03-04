<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data setting lama jika ada
        Setting::truncate();

        Setting::create([
            'nama_perusahaan' => 'POS System',
            'alamat' => 'Jl. Contoh No. 123, Jakarta',
            'telepon_1' => '021-12345678',
            'telepon_2' => '021-87654321',
            'email' => 'info@possystem.com',
            'nama_direktur' => 'Nama Direktur',
            'no_hp' => '081234567890',
            'stok_minimal' => 10,
            'ppn' => 11,
            'keterangan_struk' => 'Terima kasih atas kunjungan Anda. Barang yang sudah dibeli tidak dapat dikembalikan.',
            'logo' => null, // Logo akan di-upload melalui interface admin
        ]);
    }
}
