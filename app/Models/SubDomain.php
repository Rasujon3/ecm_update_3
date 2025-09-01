<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubDomain extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'domain_id',
        'theme_id',
        'package_id',
        'slug',
        'full_domain',
        'shop_name',
        'logo',
        'address',
        'status',
    ];

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }
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
    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }
}
