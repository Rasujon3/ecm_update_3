<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TakeALookImg extends Model
{
    use HasFactory;

    protected $table = 'take_a_look_imgs';

    protected $fillable = [
        'user_id',
        'domain_id',
        'sub_domain_id',
        'img',
    ];
}
