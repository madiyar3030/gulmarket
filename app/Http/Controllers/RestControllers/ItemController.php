<?php

namespace App\Http\Controllers\RestControllers;

use App\Http\Resources\ItemResource;
use App\Models\Basket;
use App\Models\City;
use App\Models\Item;
use App\Models\ItemDetail;
use App\Models\ItemImage;
use App\Models\WineClass;
use App\Models\WineCountry;
use App\Models\WineDetail;
use App\Models\WineManufacturer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ItemController extends Controller
{
    public function getItemsByCat($catId, Request $request)
    {
        $request['catId'] = $catId;
        $rules = [
            'type' => 'required|in:default,new,discount',
            'catId' => 'required|exists:cats,id',
            'subCatId' => 'exists:sub_cats,id',
            'cityId' => 'exists:cities,id',
//            'page' => 'required_if:type,default|min:1|numeric',
            'searchByLetter' => 'string|max:1',
            'searchByText' => 'string',
            'sortBy' => 'string|in:price,title,age,volume,created_at,updated_at',
            'sortRule' => 'string|in:asc,desc, ASC, DESC',
            'priceFrom' => 'min:1|numeric',
            'priceTo' => 'min:1|numeric',
            'filterByWine' => 'boolean',
            'countryId' => 'exists:wine_countries,id',
            'classId' => 'exists:wine_classes,id',
            'manufacturerId' => 'exists:wine_manufacturers,id',
            'ageFrom' => 'min:1|numeric',
            'ageTo' => 'min:1|numeric',
        ];

        $messages = [
            'catId.exists' => 'Категория не существуют',
            'subCatId.exists' => 'Подкатегория не существуют',
            'cityId.exists' => 'Город не существуют',
            'type.required' => 'Укажите тип',
            'type.in' => 'types: default, new, discount',
        ];

        $validator = $this->validator($request->all(),$rules,$messages);

        if ( $validator->fails() ) {
            $result['message'] = $validator->errors();
            $result['statusCode'] = 400;
            $result['result'] = null;
            return response()->json($result,$result['statusCode']);
        }

        $query = Item::where('items.city_id', $request['cityId'])
            ->where('items.cat_id', $request['catId']);

        $query->when(request('subCatId'), function ($q) {
            return $q->where('items.sub_cat_id', request('subCatId'));
        });

        $query->when(request('sortBy') !== 'age' && request('sortBy') !== 'volume' && isset($request['sortBy']), function ($q) {
            return $q->orderBy('items.'.request('sortBy'), request('sortRule', 'desc'));
        });

        $query->when(request('searchByLetter'), function ($q, $searchByLetter) {
            return $q->where('items.title', 'LIKE', $searchByLetter.'%');
        });

        $query->when(request('searchByText'), function ($q, $searchByText) {
            return $q->where('items.title', 'LIKE', '%'.$searchByText.'%');
        });

        $query->when(request('priceFrom'), function ($q, $priceFrom) {
            return $q->where('items.price', '>=', $priceFrom);
        });

        $query->when(request('priceTo'), function ($q, $priceTo) {
            return $q->where('items.price', '<=', $priceTo);
        });

        if ($request['filterByWine']) {
            $query->where('items.type', Item::TYPE_WINE)->join('wine_details', 'wine_details.item_id', 'items.id');
            $query->when(request('countryId'), function ($q, $countryId) {
                 return $q->where('wine_details.country_id', $countryId);
            });
            $query->when(request('classId'), function ($q, $classId) {
                return $q->where('wine_details.class_id', $classId);
            });
            $query->when(request('manufacturerId'), function ($q, $manufacturerId) {
                return $q->where('wine_details.manufacturer_id', $manufacturerId);
            });
            $query->when(request('ageFrom'), function ($q, $ageFrom) {
                return $q->where('wine_details.age', '>=', $ageFrom);
            });
            $query->when(request('ageTo'), function ($q, $ageTo) {
                return $q->where('wine_details.age', '<=', $ageTo);
            });
            $query->when(request('sortBy') == 'age' || request('sortBy') == 'volume', function ($q) {
                return $q->orderBy('wine_details.'.request('sortBy'), request('sortRule', 'desc'));
            });
        }

        $query->when(request('diameterFrom'), function ($q, $diameterFrom) {
            return $q->where('items.diameter', '>=', $diameterFrom);
        });
        $query->when(request('diameterTo'), function ($q, $diameterTo) {
            return $q->where('items.diameter', '<=', $diameterTo);
        });


        $query->when(request('heightFrom'), function ($q, $heightFrom) {
            return $q->where('items.height', '>=', $heightFrom);
        });
        $query->when(request('heightTo'), function ($q, $heightTo) {
            return $q->where('items.height', '<=', $heightTo);
        });

        switch ($request['type']){
            case 'new':
                $data = $query->where('isNew', 1);
                break;
            case 'discount':
                $data = $query->where('isDiscount', 1);
                break;
            case 'default':
                $data = $query->where('isNew', 0)->where('isDiscount', 0);
                break;
        }

        $result['result'] = $data->with('images')->paginate(20);
        $result['message'] = 'success';
        $result['statusCode'] = 200;

        return response()->json($result, $result['statusCode']);
    }

    public function getItemsBySubCat($subCatId, Request $request)
    {
        $request['subCatId'] = $subCatId;
        $rules = [
            'type' => 'required|in:default,new,discount',
            'subCatId' => 'required|exists:sub_cats,id',
            'cityId' => 'exists:cities,id',
//            'page' => 'required_if:type,default|min:1|numeric',
            'searchByLetter' => 'string|max:1',
            'searchByText' => 'string',
            'sortBy' => 'string|in:price,title,age,volume,created_at,updated_at',
            'sortRule' => 'string|in:asc,desc, ASC, DESC',
            'priceFrom' => 'min:1|numeric',
            'priceTo' => 'min:1|numeric',
            'filterByWine' => 'boolean',
            'countryId' => 'exists:wine_countries,id',
            'classId' => 'exists:wine_classes,id',
            'manufacturerId' => 'exists:wine_manufacturers,id',
            'ageFrom' => 'min:1|numeric',
            'ageTo' => 'min:1|numeric',
        ];

        $messages = [
            'catId.exists' => 'Категория не существуют',
            'cityId.exists' => 'Город не существуют',
            'type.required' => 'Укажите тип',
            'type.in' => 'types: default, new, discount',
        ];

        $validator = $this->validator($request->all(),$rules,$messages);

        if ( $validator->fails() ) {
            $result['message'] = $validator->errors();
            $result['statusCode'] = 400;
            $result['result'] = null;
            return response()->json($result,$result['statusCode']);
        }

        $query = Item::where('items.city_id', $request['cityId'])
            ->where('items.sub_cat_id', $request['subCatId']);

        $query->when(request('sortBy') !== 'age' && request('sortBy') !== 'volume', function ($q) {
            return $q->orderBy('items.'.request('sortBy'), request('sortRule', 'desc'));
        });

        $query->when(request('searchByLetter'), function ($q, $searchByLetter) {
            return $q->where('items.title', 'LIKE', $searchByLetter.'%');
        });

        $query->when(request('searchByText'), function ($q, $searchByText) {
            return $q->where('items.title', 'LIKE', '%'.$searchByText.'%');
        });

        $query->when(request('priceFrom'), function ($q, $priceFrom) {
            return $q->where('items.price', '>=', $priceFrom);
        });

        $query->when(request('priceTo'), function ($q, $priceTo) {
            return $q->where('items.price', '<=', $priceTo);
        });

        if ($request['filterByWine']) {
            $query->where('items.type', Item::TYPE_WINE)->join('wine_details', 'wine_details.item_id', 'items.id');
            $query->when(request('countryId'), function ($q, $countryId) {
                 return $q->where('wine_details.country_id', $countryId);
            });
            $query->when(request('classId'), function ($q, $classId) {
                return $q->where('wine_details.class_id', $classId);
            });
            $query->when(request('manufacturerId'), function ($q, $manufacturerId) {
                return $q->where('wine_details.manufacturer_id', $manufacturerId);
            });
            $query->when(request('ageFrom'), function ($q, $ageFrom) {
                return $q->where('wine_details.age', '>=', $ageFrom);
            });
            $query->when(request('ageTo'), function ($q, $ageTo) {
                return $q->where('wine_details.age', '<=', $ageTo);
            });
            $query->when(request('sortBy') == 'age' || request('sortBy') == 'volume', function ($q) {
                return $q->orderBy('wine_details.'.request('sortBy'), request('sortRule', 'desc'));
            });
        }

        $query->when(request('diameterFrom'), function ($q, $diameterFrom) {
            return $q->where('items.diameter', '>=', $diameterFrom);
        });
        $query->when(request('diameterTo'), function ($q, $diameterTo) {
            return $q->where('items.diameter', '<=', $diameterTo);
        });


        $query->when(request('heightFrom'), function ($q, $heightFrom) {
            return $q->where('items.height', '>=', $heightFrom);
        });
        $query->when(request('heightTo'), function ($q, $heightTo) {
            return $q->where('items.height', '<=', $heightTo);
        });

        switch ($request['type']){
            case 'new':
                $data = $query->where('isNew', 1);
                break;
            case 'discount':
                $data = $query->where('isDiscount', 1);
                break;
            case 'default':
                $data = $query->where('isNew', 0)->where('isDiscount', 0);
                break;
        }

        $result['result'] = $data->with('images')->paginate(5);
        $result['message'] = 'success';
        $result['statusCode'] = 200;

        return response()->json($result, $result['statusCode']);
    }

    public function getItemsBySubCaet($subCatId, Request $request)
    {
        $request['subCatId'] = $subCatId;
        $rules = [
            'type' => 'required|in:default,new,discount',
            'subCatId' => 'required|exists:sub_cats,id',
            'cityId' => 'exists:cities,id',
            'page' => 'required_if:type,default|min:1|numeric',
            'searchByLetter' => 'string|max:1',
        ];

        $messages = [
            'subCatId.exists' => 'Подкатегория не существуют',
            'cityId.exists' => 'Город не существуют',
            'type.required' => 'Укажите тип',
            'type.in' => 'types: default, new, discount',
        ];

        $validator = $this->validator($request->all(),$rules,$messages);

        if ( $validator->fails() ) {
            $result['message'] = $validator->errors();
            $result['statusCode'] = 400;
            $result['result'] = null;
            return response()->json($result,$result['statusCode']);
        }

        $query = Item::where('city_id', $request['cityId'])
            ->where('sub_cat_id', $request['subCatId']);

        $query->when(request('sortBy') == 'price', function ($q) {
            return $q->orderBy('price', request('sortRule', 'desc'));
        });

        $query->when(request('searchByLetter'), function ($q, $searchByLetter) {
            return $q->where('title', 'LIKE', $searchByLetter.'%');
        });

        switch ($request['type']){
            case 'new':
                $data = $query->where('isNew', 1)->with('images')->get();
                break;
            case 'discount':
                $data = $query->where('isDiscount', 1)->with('images')->get();
                break;
            case 'default':
                $limit = 4;
                $offset = ($request['page'] == 1 ) ? 0 : $limit * ($request['page'] - 1);
                $data = $query->where('isNew', 0)->where('isDiscount', 0)->limit($limit)->offset($offset)->get();
                $count = count($data);
                $result['offset'] =  $offset;
                $result['limit'] = $limit;
                $result['countProducts'] = $count;
                $result['currentPage'] = (int)$request['page'];
                $result['countPages'] = (int)ceil($count/$limit);
                break;
        }
        $result['result'] = $data;
        $result['message'] = 'success';
        $result['statusCode'] = 200;

        return response()->json($result, $result['statusCode']);
    }

    public function getItem($itemId, Request $request)
    {
        $request['itemId'] = $itemId;

        $rules = [
            'itemId' => 'required|exists:items,id'
        ];

        $validator = $this->validator($request->all(), $rules);

        if ($validator->fails()) {
            $data = ['statusCode' => '404', 'message' => 'not found', 'result' => null];
            return response()->json($data, $data['statusCode']);
        }

        $item = Item::with('details', 'images')->findOrFail($itemId);
        $item->city = City::find($item->city_id)->city ?? null;
        $item->inBasket = Basket::where('item_id', $itemId)->where('user_id', $request->currentUser->id)->first() ? true : false;
        if ($item->details) {
            $item->details->country = WineCountry::find($item->details->country_id)->title ?? null;
            $item->details->class = WineClass::find($item->details->class_id)->title ?? null;
            $item->details->manufacturer = WineManufacturer::find($item->details->manufacturer_id)->title ?? null;
        }
        $data['message'] = 'success';
        $data['statusCode'] = 200;
        $data['result'] = $item;

        return response()->json($data, $data['statusCode']);
    }
}
