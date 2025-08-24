<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCharacteristicsDetails extends Model
{
    use HasFactory;

    protected $table = 'product_characteristics_details';

    protected $fillable = [
        'user_id',
        'domain_id',
        'description',
    ];
}
