<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $firstName = fake()->firstName();
        $lastName = fake()->lastName();

        return [
            'nip' => now()->format('ymdHis') . rand(1000, 9999),
            'ktp' => '32' . fake()->unique()->numerify('##############'),
            'nama_depan' => $firstName,
            'nama_belakang' => $lastName,
            'jenis_kelamin' => fake()->randomElement(['L', 'P']),
            'alamat' => fake()->address(),
            'tanggal_lahir' => fake()->date('Y-m-d', '-25 years'),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'no_telepon' => '08' . fake()->numerify('##########'),
            'jabatan_id' => rand(1, 4),
            'tanggungan' => fake()->optional()->randomElement(['1 anak', '2 anak', '3 anak']),
            'referensi' => fake()->randomElement(['Teman', 'Keluarga', 'Media Sosial', 'Walk-in']),
            'status_karyawan' => fake()->randomElement(['aktif', 'non-aktif']),
            'status_login' => fake()->randomElement(['aktif', 'non-aktif']),
            'tanggal_masuk' => fake()->date('Y-m-d', '-2 years'),
            'password' => static::$password ??= Hash::make('password'),
            'foto' => null,
            'gaji_pokok' => fake()->randomFloat(2, 3000000, 8000000),
            'tunjangan_jabatan' => fake()->randomFloat(2, 500000, 2000000),
            'uang_makan' => 500000.00,
            'tunjangan_lain' => fake()->randomFloat(2, 100000, 500000),
            'created_by' => null,
            'updated_by' => null,
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
