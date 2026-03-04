<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailDaftarHarga extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'daftar_harga_id',
        'barang_id',
        'harga_jual',
        'satuan_id',
        'diskon',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'harga_jual' => 'decimal:2',
        'satuan_id' => 'integer',
        'diskon' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the daftar harga that owns the detail.
     */
    public function daftarHarga()
    {
        return $this->belongsTo(DaftarHarga::class, 'daftar_harga_id');
    }

    /**
     * Get the barang for the detail.
     */
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    /**
     * Get the user that created the detail.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user that last updated the detail.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
