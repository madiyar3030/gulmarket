<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class Item extends Model
{
    const TYPE_DEFAULT = 'default';
    const TYPE_WINE = 'wine';

    protected $hidden = ['cat_id', 'sub_cat_id', 'city_id', 'type', 'created_at'];
    protected $fillable = [
        'title',
        'cat_id',
        'sub_cat_id',
        'city_id',
        'price',
        'count',
        'isNew',
        'isDiscount',
        'height',
        'diameter',
        'type',
        'description',
        'bonusPercentage',
    ];
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('items.id', 'desc');
        });
        static::select(function ($query) {
            $query->bonus = ($query->price) * $query->bonusPercentage/100;
        });
    }

    public function images()
    {
        return $this->belongsToMany(Image::class, 'item_images');
    }

    public function details()
    {
        return $this->hasOne(WineDetail::class);
    }

    public function cat()
    {
        return $this->hasOne(Cat::class, 'id', 'cat_id');
    }

    public function subCat()
    {
        return $this->hasOne(SubCat::class, 'id', 'sub_cat_id');
    }

    public function city()
    {
        return $this->hasOne(City::class, 'id', 'city_id');
    }
//
//    public function details()
//    {
//        return $this->hasMany(ItemDetail::class);
//    }
}
