<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SizeMeasurement extends Model
{
    use HasFactory;

    protected $table = 'size_measurements';

    protected $fillable = [
        'user_id',
        'domain_id',
        'sub_domain_id',
        'img',
    ];
}
