<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public function orderdetail()
    {
    	return $this->belongsTo(Orderdetail::class);
    }

    public function product()
    {
    	return $this->belongsTo(Product::class);
    }

    public function variant()
    {
    	return $this->belongsTo(Variant::class);
    }
    
    public function variantIds()
    {
        return $this->hasMany(OrderVariantId::class);
    }
}
