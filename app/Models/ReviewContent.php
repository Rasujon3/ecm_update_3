<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewContent extends Model
{
    use HasFactory;

    protected $table = 'review_contents';

    protected $fillable = [
        'user_id',
        'domain_id',
        'title',
        'description',
    ];
}
