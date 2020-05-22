<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentLog extends Model
{
    protected $fillable = [
        'user_id',
        'local_order_id',
        'order_id',
        'session_id',
        'amount',
        'status',
        'description',
    ];
}
