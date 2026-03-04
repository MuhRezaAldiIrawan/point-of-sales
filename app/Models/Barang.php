<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Barang extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'kode',
        'nama_barang',
        'merek_id',
        'jenis_barang_id',
        'supplier_id',
        'foto',
        'keterangan',
        'created_by',
        'updated_by',
    ];

    public function merek()
    {
        return $this->belongsTo(Merek::class, 'merek_id');
    }

    public function jenisBarang()
    {
        return $this->belongsTo(JenisBarang::class, 'jenis_barang_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function detailBarang()
    {
        return $this->hasMany(DetailBarang::class, 'barang_id');
    }

    public function detailDaftarHargas()
    {
        return $this->hasMany(DetailDaftarHarga::class, 'barang_id');
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }
}
