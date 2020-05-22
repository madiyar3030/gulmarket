<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $hidden = ['created_at', 'updated_at', 'user_id'];

    public function city()
    {
        return $this->hasOne(City::class, 'id', 'city_id');
    }
}
