<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BarangMasuk extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'no_reff',
        'tanggal_masuk',
        'jenis_stok_id',
        'catatan',
        'created_by',
        'updated_by',
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'tanggal_masuk' => 'date',
    ];

    public function jenisStok()
    {
        return $this->belongsTo(JenisStok::class, 'jenis_stok_id');
    }

    public function detailBarangMasuk()
    {
        return $this->hasMany(DetailBarangMasuk::class, 'barang_masuk_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function barang()
    {
        return $this->belongsToMany(Barang::class, 'detail_barang_masuks', 'barang_masuk_id', 'barang_id');
    }
}
