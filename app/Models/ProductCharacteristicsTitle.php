<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCharacteristicsTitle extends Model
{
    use HasFactory;

    protected $table = 'product_characteristics_titles';

    protected $fillable = [
        'user_id',
        'domain_id',
        'title',
    ];
}
