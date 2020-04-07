<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    protected $hidden = ['id', 'order_id', 'item_id', 'updated_at', 'created_at'];
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
