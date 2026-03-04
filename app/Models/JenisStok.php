<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class JenisStok extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nama',
        'created_by',
        'updated_by',
    ];

    protected $dates = ['deleted_at'];
}
