<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailBarangKeluar extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'barang_keluar_id',
        'stok_ppn',
        'barang_id',
        'satuan_id',
        'isi',
        'harga_jual',
        'jumlah',
        'total',
        'keterangan',
        'created_by',
        'updated_by',
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'harga_jual' => 'decimal:2',
        'total' => 'decimal:2',
        'isi' => 'integer',
        'jumlah' => 'integer',
    ];

    public function barangKeluar()
    {
        return $this->belongsTo(BarangKeluar::class, 'barang_keluar_id');
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
