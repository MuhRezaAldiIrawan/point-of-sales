<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'nama_perusahaan',
        'alamat',
        'telepon_1',
        'telepon_2',
        'email',
        'nama_direktur',
        'no_hp',
        'stok_minimal',
        'ppn',
        'keterangan_struk',
        'logo',
    ];

    protected function casts(): array
    {
        return [
            'stok_minimal' => 'integer',
            'ppn' => 'integer',
        ];
    }
}
