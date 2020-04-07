<?php

namespace App\Http\Resources;

use http\Env\Response;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\ItemDetail;
use App\Models\WineClass;
use App\Models\WineManufacturer;
use App\Models\WineCountry;

class ItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $item = [
            'id' => $this->id,
            'title' => $this->title,
            'price' => $this->price,
            'count' => $this->count,
            'isNew' => $this->isNew,
            'isDiscount' => $this->isDiscount,
            'height' => (int) $this->height,
            'diameter' => (int) $this->diameter,
            'description' => $this->description,
            'updated_at' => $this->updated_at,
            'bonusPercentage'=>$this->bonusPercentage,
            'images' => []
        ];
        if (count($this->images) > 0) {
            foreach ($this->images as $image) {
                $item['images'][] = $image;
            }
        }
//        if (count($this->details) > 0) {
//            foreach ($this->details as $detail) {
//                switch ($detail->title) {
//                    case ItemDetail::WINE_CLASS:
//                        $item[$detail->title] = WineClass::find($detail->value)->title ?? $detail->value;
//                        break;
//                    case ItemDetail::WINE_MANUFACTURER:
//                        $item[$detail->title] = WineManufacturer::find($detail->value)->title ?? $detail->value;
//                        break;
//                    case ItemDetail::WINE_COUNTRY:
//                        $item[$detail->title] = WineCountry::find($detail->value)->title ?? $detail->value;
//                        break;
//                    default :
//                        $item[$detail->title] = $detail->value;
//                }
//            }
//        }
        return $item;
    }
}
