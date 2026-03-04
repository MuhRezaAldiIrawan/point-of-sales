<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DaftarHarga extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nama',
        'diskon',
        'status',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'diskon' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the detail daftar hargas for the daftar harga.
     */
    public function details()
    {
        return $this->hasMany(DetailDaftarHarga::class, 'daftar_harga_id');
    }

    /**
     * Get the user that created the daftar harga.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user that last updated the daftar harga.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
