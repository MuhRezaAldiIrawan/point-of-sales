<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailBarangMasuk extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'barang_masuk_id',
        'stok_ppn',
        'barang_id',
        'satuan_id',
        'isi',
        'harga_beli',
        'jumlah',
        'total',
        'keterangan',
        'created_by',
        'updated_by',
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'harga_beli' => 'decimal:2',
        'total' => 'decimal:2',
        'isi' => 'integer',
        'jumlah' => 'integer',
    ];

    public function barangMasuk()
    {
        return $this->belongsTo(BarangMasuk::class, 'barang_masuk_id');
    }

    public function scopeActiveStock($query)
    {
        return $query->whereHas('barangMasuk', function ($barangMasukQuery) {
            $barangMasukQuery->successful();
        });
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
