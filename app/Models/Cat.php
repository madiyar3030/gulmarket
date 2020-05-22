<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cat extends Model
{
    protected $fillable = ['title', 'hidden', 'thumb'];

    protected $hidden = ['hidden', 'updated_at', 'created_at'];

    public function subCats()
    {
        return $this->hasMany(SubCat::class);
    }

    public static function getAll()
    {
        return self::where('hidden', 1)->orderBy('order_number', 'ASC')->get();
    }

    public static function getSubCats($catId)
    {
        return self::where('id', $catId)
            ->where('hidden', 1)
            ->with('subCats')
            ->first();
    }
//    const TYPE_WINE = 'wines';
//    const TYPE_BOUQUET = 'bouquets';
//    const TYPE_ACCESSORY = 'accessories';
//    const TYPE_PLANTS = 'houseplants';

//    public static function getWines()
//    {
//        return  SubCat::where('type', self::TYPE_WINE)->get();
//    }
//    public static function getBouquets()
//    {
//        return  SubCat::where('type', self::TYPE_BOUQUET)->get();
//    }
//    public static function getAccessories()
//    {
//        return  SubCat::where('type', self::TYPE_ACCESSORY)->get();
//    }
//    public static function getPlants()
//    {
//        return  SubCat::where('type', self::TYPE_PLANTS)->get();
//    }
}
