<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nip',
        'ktp',
        'nama_depan',
        'nama_belakang',
        'jenis_kelamin',
        'alamat',
        'tanggal_lahir',
        'email',
        'no_telepon',
        'jabatan_id',
        'tanggungan',
        'referensi',
        'status_karyawan',
        'status_login',
        'tanggal_masuk',
        'password',
        'foto',
        'gaji_pokok',
        'tunjangan_jabatan',
        'uang_makan',
        'tunjangan_lain',
        'created_by',
        'updated_by',
        'deleted_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'tanggal_lahir' => 'date',
            'tanggal_masuk' => 'date',
            'deleted_at' => 'date',
            'gaji_pokok' => 'decimal:2',
            'tunjangan_jabatan' => 'decimal:2',
            'uang_makan' => 'decimal:2',
            'tunjangan_lain' => 'decimal:2',
        ];
    }

    public function getNameAttribute(): string
    {
        $fullName = trim(implode(' ', array_filter([
            $this->nama_depan,
            $this->nama_belakang,
        ])));

        if ($fullName !== '') {
            return $fullName;
        }

        return $this->email ?: ($this->nip ?: 'System');
    }

    /**
     * Get the jabatan that owns the user.
     */
    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }
}
