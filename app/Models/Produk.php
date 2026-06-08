<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Produk extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'produk_nama', 'produk_harga', 'produk_diskon', 'produk_stok',
        'produk_detail', 'kuota', 'masa_aktif', 'produk_insentif', 'produk_terjual_history',
        'travel_id', // ID travel agent pemilik produk (nullable = semua travel)
    ];


    public function merchandises()
    {
        return $this->belongsToMany(Merchandise::class, 'merchandise_produk');
    }

    /**
     * Travel agent (RoleUsers) yang memiliki produk ini.
     * Jika NULL, produk visible ke semua travel.
     */
    public function travel()
    {
        return $this->belongsTo(RoleUsers::class, 'travel_id');
    }

    protected $casts = [
        'produk_terjual_history' => 'array',
    ];

}
