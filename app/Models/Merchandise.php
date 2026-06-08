<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Merchandise extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'merch_nama',
        'merch_detail',
        'merch_stok',
        'merch_terambil_history'
    ];

    /**
     */
    public function produks()
    {
        return $this->belongsToMany(Produk::class, 'merchandise_produk');
    }

    protected $casts = [
        'merch_terambil_history' => 'array',
    ];
}
