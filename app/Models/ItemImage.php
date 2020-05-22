<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemImage extends Model
{
    protected $hidden = ['id', 'updated_at', 'created_at'];
    protected $fillable = ['image_id', 'item_id'];
}
