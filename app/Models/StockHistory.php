<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'merchandise_id',
        'change_amount',
        'previous_stock',
        'current_stock',
        'action',
    ];

    public function product()
    {
        return $this->belongsTo(Produk::class, 'product_id');
    }

    public function produk()
    {
        return $this->product();
    }

    public function merchandise()
    {
        return $this->belongsTo(Merchandise::class, 'merchandise_id');
    }
}
