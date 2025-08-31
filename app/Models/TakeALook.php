<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TakeALook extends Model
{
    use HasFactory;

    protected $table = 'take_a_looks';

    protected $fillable = [
        'user_id',
        'domain_id',
        'sub_domain_id',
        'title',
    ];
}
