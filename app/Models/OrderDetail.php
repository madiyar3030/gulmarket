<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $fillable = ['order_id','name','surname','phone','email','city_id','street','house','entrance','floor','building','flatNumber','code'];

    protected $hidden = ['order_id', 'updated_at', 'created_at', 'id'];
}
