<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BannerText extends Model
{
    use HasFactory;

    protected $table = 'banner_texts';

    protected $fillable = [
        'user_id',
        'domain_id',
        'banner_text',
    ];
}
