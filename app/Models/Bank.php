<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bank extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nama',
        'no_rekening',
        'atas_nama',
        'created_by',
        'updated_by',
    ];

    protected $dates = ['deleted_at'];
}
