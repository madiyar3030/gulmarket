<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WineDetail extends Model
{
    protected $hidden = ['id', 'country_id', 'class_id', 'manufacturer_id', 'created_at', 'updated_at', 'item_id'];

    protected static function boot()
    {
        parent::boot();
    }

    public function country()
    {
        return $this->hasOne(WineCountry::class);
    }

    public function class()
    {
        return $this->hasOne(WineClass::class);
    }

    public function manufacturer()
    {
        return $this->hasOne(WineManufacturer::class);
    }
}
