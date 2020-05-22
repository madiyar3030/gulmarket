<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Basket extends Model
{
    protected $hidden = ['item_id', 'user_id', 'created_at', 'updated_at'];
    protected $fillable = ['item_id', 'user_id', 'count'];

    public function item()
    {
        return $this->hasOne(Item::class, 'id', 'item_id');
    }

    public static function getTotalPriceBonus ($userId)
    {
        $baskets = self::where('user_id', $userId)->with('item')->get();
        $items = [];
        $price = 0;
        $bonus = 0;
        foreach ($baskets as $item) {
            $bonus += (($item->item->price * $item->item->bonusPercentage / 100) * $item->count);
            $price += ($item->item->price * $item->count);
            $items[] = (object) [
                'item_id' => $item->item->id,
                'count' => $item->count
            ];
        }
        $data['totalPrice'] = $price;
        $data['totalBonus'] = $bonus;
        $data['items'] = $items;
        return $data;
    }
}
