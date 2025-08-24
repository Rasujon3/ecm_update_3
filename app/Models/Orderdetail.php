<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orderdetail extends Model
{
    use HasFactory;

    public function orders()
    {
    	return $this->hasMany(Order::class);
    }

    public function courier()
    {
    	return $this->hasOne(Courier::class);
    }
    
    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }
}
