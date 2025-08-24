<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    public function services()
	{
	    return $this->belongsToMany(Service::class);
	}
    public function websitePurchases()
    {
        return $this->hasMany(WebsitePurchase::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function domains()
    {
        return $this->hasMany(Domain::class);
    }
}
