<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BarangKeluar extends Model
{
    use SoftDeletes;

    public const STATUS_SUCCESS = 'success';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'no_reff',
        'tanggal_keluar',
        'jenis_stok_id',
        'status',
        'catatan',
        'cancel_reason',
        'cancelled_at',
        'cancelled_by',
        'created_by',
        'updated_by',
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'tanggal_keluar' => 'date',
        'cancelled_at' => 'datetime',
    ];

    public function scopeSuccessful($query)
    {
        return $query->where('status', self::STATUS_SUCCESS);
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function canBeEdited(): bool
    {
        return ! $this->isCancelled();
    }

    public function canBeCancelled(): bool
    {
        return ! $this->isCancelled();
    }

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

    public function cancelledBy()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    public function barang()
    {
        return $this->belongsToMany(Barang::class, 'detail_barang_keluars', 'barang_keluar_id', 'barang_id');
    }
}
