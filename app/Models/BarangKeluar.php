<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BarangKeluar extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'no_reff',
        'tanggal_keluar',
        'jenis_stok_id',
        'catatan',
        'created_by',
        'updated_by',
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'tanggal_keluar' => 'date',
    ];

    public function jenisStok()
    {
        return $this->belongsTo(JenisStok::class, 'jenis_stok_id');
    }

    public function detailBarangKeluar()
    {
        return $this->hasMany(DetailBarangKeluar::class, 'barang_keluar_id');
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
        return $this->belongsToMany(Barang::class, 'detail_barang_keluars', 'barang_keluar_id', 'barang_id');
    }
}
