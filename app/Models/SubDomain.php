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
}
