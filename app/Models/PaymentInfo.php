<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentInfo extends Model
{
    use HasFactory;

    protected $table = 'payment_infos';

    protected $fillable = [
        'user_id',
        'logo',
        'payment_method',
        'account_number',
        'payment_type',
        'instructions',
    ];
}
