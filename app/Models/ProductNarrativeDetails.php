<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductNarrativeDetails extends Model
{
    use HasFactory;

    protected $table = 'product_narrative_details';

    protected $fillable = [
        'user_id',
        'domain_id',
        'description',
    ];
}
