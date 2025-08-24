<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Courier extends Model
{
    use HasFactory;

    protected $fillable = [
        'orderdetail_id',
        'invoice_no',
        'consignment_id',
        'tracking_code',
    ];

    public function orderdetail()
    {
    	return $this->belongsTo(Orderdetail::class);
    }
}
