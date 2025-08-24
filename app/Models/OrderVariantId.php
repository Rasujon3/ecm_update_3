<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderVariantId extends Model
{
    use HasFactory;

    protected $table = 'order_variant_ids';

    protected $fillable = [
        'order_id',
        'product_id',
        'variant_id'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }
}
