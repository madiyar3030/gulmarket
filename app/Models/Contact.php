<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = ['lng', 'lat', 'phone', 'address', 'city_id', 'workHour'];

    protected $hidden = ['phone', 'created_at', 'updated_at'];

    public function city()
    {
        return $this->hasOne(City::class, 'id', 'city_id');
    }
}
