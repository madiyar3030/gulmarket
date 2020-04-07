<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    protected $table = 'shipping';

    protected $hidden = ['city_id', 'updated_at', 'created_at'];

    protected $fillable = ['city_id', 'title', 'price'];

    public function city()
    {
        return $this->hasOne(City::class, 'id', 'city_id');
    }
}
