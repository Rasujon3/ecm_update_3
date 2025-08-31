<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllProductContent extends Model
{
    use HasFactory;

    protected $table = 'all_product_contents';

    protected $fillable = [
        'user_id',
        'domain_id',
        'sub_domain_id',
        'title',
        'description',
    ];
}
