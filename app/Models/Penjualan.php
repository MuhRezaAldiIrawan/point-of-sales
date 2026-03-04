<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penjualan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'no_invoice',
        'tanggal_penjualan',
        'ppn',
        'payment_method',
        'jatuh_tempo',
        'total_harga',
        'diskon',
        'ppn_amount',
        'biaya_kirim',
        'biaya_lain',
        'grand_total',
        'bayar',
        'sisa',
        'kembalian',
        'status',
        'catatan',
        'pelanggan_id',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'tanggal_penjualan' => 'date',
        'jatuh_tempo' => 'date',
        'total_harga' => 'decimal:2',
        'diskon' => 'decimal:2',
        'ppn_amount' => 'decimal:2',
        'biaya_kirim' => 'decimal:2',
        'biaya_lain' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'bayar' => 'decimal:2',
        'sisa' => 'decimal:2',
        'kembalian' => 'decimal:2'
    ];

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function detailPenjualans(): HasMany
    {
        return $this->hasMany(DetailPenjualan::class);
    }

    public function barangKeluar(): BelongsTo
    {
        return $this->belongsTo(BarangKeluar::class, 'no_invoice', 'no_reff');
    }

    public function generateNoInvoice()
    {
        $tanggal = now()->format('YmdHis');
        $lastPenjualan = self::whereDate('created_at', today())->latest()->first();

        if ($lastPenjualan) {
            $lastNumber = (int) substr($lastPenjualan->no_invoice, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return 'INV-' . $tanggal . '-' . $newNumber;
    }
}
