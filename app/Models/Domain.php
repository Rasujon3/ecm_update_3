<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    use HasFactory;

    public function theme()
    {
    	return $this->belongsTo(Theme::class);
    }

    public function user()
    {
    	return $this->belongsTo(User::class);
    }
    public function websitePurchases()
    {
        return $this->hasMany(WebsitePurchase::class);
    }

    public function orderdetails()
    {
        return $this->hasMany(Orderdetail::class);
    }
    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }
}
