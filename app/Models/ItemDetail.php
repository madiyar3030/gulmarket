<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemDetail extends Model
{
    const WINE_COUNTRY = 'countryId';
    const WINE_MANUFACTURER = 'manufacturerId';
    const WINE_CLASS = 'classId';
    const WINE_AGE = 'age';
    const HOUSE_PLANT_DIAMETER = 'diameter';
    const HOUSE_PLANT_HEIGHT = 'height';

    protected $hidden = ['id','created_at', 'updated_at', 'item_id'];

}
