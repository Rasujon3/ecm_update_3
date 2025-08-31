<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhyChooseUsTitle extends Model
{
    use HasFactory;

    protected $table = 'why_choose_us_title';

    protected $fillable = [
        'user_id',
        'domain_id',
        'sub_domain_id',
        'title',
    ];
}
