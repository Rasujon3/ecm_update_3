<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timer extends Model
{
    use HasFactory;

    protected $table = 'timers';

    protected $fillable = [
        'user_id',
        'domain_id',
        'sub_domain_id',
        'title',
        'time',
    ];

}
