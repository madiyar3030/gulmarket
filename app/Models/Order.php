<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    const DECLINE = 'declined';
    const WAITING = 'waiting';
    const ACCEPTED = 'accepted';
    const CASH_PAYMENT = 'cash';
    const CARD_PAYMENT = 'card';

    protected $fillable = ['user_id', 'total', 'bonusUser', 'bonusPrice', 'payType', 'shipping_id', 'orderDate', 'status'];

    protected $hidden = ['bonusUser', 'shipping_id'];

    public function shipping()
    {
        return $this->belongsTo(Shipping::class);
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
