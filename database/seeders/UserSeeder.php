<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Generate NIP dengan format TAHUN(YY)BULANTTANGGALJAMMENITDETIK
        $baseTimestamp = now();

        $users = [
            [
                'nip' => $baseTimestamp->copy()->addMinutes(1)->format('ymdHis'),
                'ktp' => '3201234567890001',
                'nama_depan' => 'Ahmad',
                'nama_belakang' => 'Wijaya',
                'jenis_kelamin' => 'L',
                'alamat' => 'Jl. Merdeka No. 123, Jakarta',
                'tanggal_lahir' => '1990-05-15',
                'email' => 'ahmad.wijaya@company.com',
                'no_telepon' => '081234567890',
                'jabatan_id' => 1, // Owner
                'tanggungan' => '2 anak',
                'referensi' => 'Saudara',
                'status_karyawan' => 'aktif',
                'status_login' => 'aktif',
                'tanggal_masuk' => '2023-01-15',
                'password' => Hash::make('password123'),
                'foto' => null,
                'gaji_pokok' => 8000000.00,
                'tunjangan_jabatan' => 2000000.00,
                'uang_makan' => 500000.00,
                'tunjangan_lain' => 300000.00,
                'created_by' => null,
                'updated_by' => null
            ],
            [
                'nip' => $baseTimestamp->copy()->addMinutes(2)->format('ymdHis'),
                'ktp' => '3201234567890002',
                'nama_depan' => 'Sari',
                'nama_belakang' => 'Indah',
                'jenis_kelamin' => 'P',
                'alamat' => 'Jl. Sudirman No. 456, Bandung',
                'tanggal_lahir' => '1992-08-22',
                'email' => 'sari.indah@company.com',
                'no_telepon' => '081298765432',
                'jabatan_id' => 2, // Administrator
                'tanggungan' => '1 anak',
                'referensi' => 'Teman',
                'status_karyawan' => 'aktif',
                'status_login' => 'aktif',
                'tanggal_masuk' => '2023-03-10',
                'password' => Hash::make('password123'),
                'foto' => null,
                'gaji_pokok' => 5500000.00,
                'tunjangan_jabatan' => 1200000.00,
                'uang_makan' => 500000.00,
                'tunjangan_lain' => 250000.00,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'nip' => $baseTimestamp->copy()->addMinutes(3)->format('ymdHis'),
                'ktp' => '3201234567890003',
                'nama_depan' => 'Budi',
                'nama_belakang' => 'Santoso',
                'jenis_kelamin' => 'L',
                'alamat' => 'Jl. Gatot Subroto No. 789, Surabaya',
                'tanggal_lahir' => '1988-12-03',
                'email' => 'budi.santoso@company.com',
                'no_telepon' => '081356789012',
                'jabatan_id' => 3, // Kasir
                'tanggungan' => null,
                'referensi' => 'Media Sosial',
                'status_karyawan' => 'aktif',
                'status_login' => 'non-aktif',
                'tanggal_masuk' => '2023-02-20',
                'password' => Hash::make('password123'),
                'foto' => null,
                'gaji_pokok' => 4000000.00,
                'tunjangan_jabatan' => 600000.00,
                'uang_makan' => 500000.00,
                'tunjangan_lain' => 200000.00,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'nip' => $baseTimestamp->copy()->addMinutes(4)->format('ymdHis'),
                'ktp' => '3201234567890004',
                'nama_depan' => 'Dewi',
                'nama_belakang' => 'Lestari',
                'jenis_kelamin' => 'P',
                'alamat' => 'Jl. Diponegoro No. 321, Yogyakarta',
                'tanggal_lahir' => '1995-03-18',
                'email' => 'dewi.lestari@company.com',
                'no_telepon' => '081445678901',
                'jabatan_id' => 4, // HR
                'tanggungan' => null,
                'referensi' => 'Walk-in',
                'status_karyawan' => 'aktif',
                'status_login' => 'aktif',
                'tanggal_masuk' => '2023-06-01',
                'password' => Hash::make('password123'),
                'foto' => null,
                'gaji_pokok' => 4800000.00,
                'tunjangan_jabatan' => 800000.00,
                'uang_makan' => 500000.00,
                'tunjangan_lain' => 150000.00,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'nip' => $baseTimestamp->copy()->addMinutes(5)->format('ymdHis'),
                'ktp' => '3201234567890005',
                'nama_depan' => 'Rizki',
                'nama_belakang' => 'Pratama',
                'jenis_kelamin' => 'L',
                'alamat' => 'Jl. Ahmad Yani No. 654, Medan',
                'tanggal_lahir' => '1993-07-10',
                'email' => 'rizki.pratama@company.com',
                'no_telepon' => '081567890123',
                'jabatan_id' => 3, // Kasir
                'tanggungan' => '3 anak',
                'referensi' => 'Keluarga',
                'status_karyawan' => 'non-aktif',
                'status_login' => 'non-aktif',
                'tanggal_masuk' => '2022-11-15',
                'password' => Hash::make('password123'),
                'foto' => null,
                'gaji_pokok' => 3800000.00,
                'tunjangan_jabatan' => 500000.00,
                'uang_makan' => 500000.00,
                'tunjangan_lain' => 220000.00,
                'created_by' => 1,
                'updated_by' => 1
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
