<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pixel extends Model
{
    use HasFactory;

    protected $table = 'pixels';

    protected $fillable = [
        'user_id',
        'domain_id',
        'sub_domain_id',
        'pixel_id',
        'status',
    ];
}
