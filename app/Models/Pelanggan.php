<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pelanggan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nama',
        'alamat',
        'no_hp',
        'kota',
        'status_bayar',
        'batas_kredit',
        'keterangan',
        'created_by',
        'updated_by',
    ];

    protected $dates = ['deleted_at'];

}
