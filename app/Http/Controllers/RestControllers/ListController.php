<?php

namespace App\Http\Controllers\RestControllers;

use App\Models\City;
use App\Models\Contact;
use App\Models\FAQ;
use App\Models\Info;
use App\Models\Shipping;
use App\Models\SubCat;
use App\Models\WineClass;
use App\Models\WineCountry;
use App\Models\WineManufacturer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cat;

class ListController extends Controller
{
    public function getInfo()
    {
        $info = (object)[];
        $info->payment = Info::where('key', 'payment')->first()->description ?? null;
        $info->shipping = Info::where('key', 'shipping')->first()->description ?? null;
        $result['result'] = $info;
        $result['statusCode'] = 200;
        $result['message'] = 'Success!';
        return response()->json($result, $result['statusCode']);
    }

    public function getFaqs()
    {
        $result['result'] = FAQ::all();
        $result['statusCode'] = 200;
        $result['message'] = 'Success!';
        return response()->json($result, $result['statusCode']);
    }

    public function getShipping($cityId)
    {
        $result['result'] = Shipping::where('city_id', $cityId)->get();
        $result['statusCode'] = 200;
        $result['message'] = 'Success!';
        return response()->json($result, $result['statusCode']);
    }

    public function getContacts($cityId)
    {
        $contacts = Contact::where('city_id', $cityId)->get();
        foreach ($contacts as $item) {
            $item->phones = $this->strToArr($item->phone, ',');
        }
        $result['result'] = $contacts;
        $result['statusCode'] = 200;
        $result['message'] = 'Success!';
        return response()->json($result, $result['statusCode']);
    }

    public function getCats()
    {
        $result['result'] = Cat::getAll();
        $result['statusCode'] = 200;
        $result['message'] = 'Success!';
        return response()->json($result, $result['statusCode']);
    }

    public function getCat($catId)
    {
        $result['result'] = Cat::getSubCats($catId);
        $result['statusCode'] = 200;
        $result['message'] = 'Success!';
        return response()->json($result, $result['statusCode']);
    }

    public function getCities()
    {
        $result['result'] = City::all();
        $result['statusCode'] = 200;
        $result['message'] = 'Success!';
        return response()->json($result, $result['statusCode']);
    }

    public function getWineCountries()
    {
        $result['result'] = WineCountry::all();
        $result['statusCode'] = 200;
        $result['message'] = 'Success!';
        return response()->json($result, $result['statusCode']);
    }

    public function getWineManufacturers()
    {
        $result['result'] = WineManufacturer::all();
        $result['statusCode'] = 200;
        $result['message'] = 'Success!';
        return response()->json($result, $result['statusCode']);
    }

    public function getWineClasses()
    {
        $result['result'] = WineClass::all();
        $result['statusCode'] = 200;
        $result['message'] = 'Success!';
        return response()->json($result, $result['statusCode']);
    }
}
