<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsitePurchase extends Model
{
    use HasFactory;

    protected $table = 'website_purchases';

    protected $fillable = [
        'domain_id',
        'gateway_order_id',
        'user_id',
        'package_id',
        'theme',
        'payment_method',
        'transaction_hash',
        'status',
    ];

    /**
     * Relationships
     */

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
