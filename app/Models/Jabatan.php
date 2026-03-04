<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;


class Jabatan extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'nama',
        'deskripsi',
        'created_by',
        'updated_by',
        'deleted_at',
    ];

    protected $casts = [
        'deleted_at' => 'date',
    ];

    /**
     * Relationship dengan Karyawan
     */
    public function karyawans()
    {
        return $this->hasMany(Karyawan::class, 'jabatan_id');
    }
}
