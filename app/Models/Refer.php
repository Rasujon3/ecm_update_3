<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'per_refer_point',
        'total_required_point',
        'website_quantity',
    ]; 
}
