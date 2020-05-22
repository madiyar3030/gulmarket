<?php

namespace App\Http\Controllers;

use App\Models\Cat;
use App\Models\City;
use App\Models\Item;
use App\Models\SubCat;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Validator;
use File;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function validator ($errors,$rules,$messages=[])
    {
        return Validator::make($errors,$rules,$messages);
    }

    protected function upload ($media, $dir = 'storage')
    {
        $file_name =  uniqid().'-'.$media->getClientOriginalName();
        $new_dir = $dir.'/'.date('Y/m/d');
        $path = $media->move($new_dir, str_replace(' ','_',$file_name));
        return '/'.$path;
    }

    protected function deleteFile(string $path)
    {
        if (File::exists($path)) {
            File::delete($path);
            return true;
        }
        else{
            return false;
        }
    }

    protected function validatePhone ($phone)
    {
        $phone_number = preg_replace('/[^0-9]/', '', $phone);
        $phone_number = substr($phone_number,1);
        return $phone_number;
    }

    protected function trimToLower ($str)
    {
        $str = str_replace(' ','',$str);
        return strtolower($str);
    }

    protected function arrToString ($arr, $delimiter)
    {
        $new_arr = [];
        foreach ($arr as $item) {
            if($item) {
                $new_arr[] = $item;
            }
        }
        $str = implode($delimiter,$new_arr);
        return $str;
    }

    protected function strToArr ($str, $delimiter)
    {
        $temp = explode($delimiter,$str);
        $arr = [];
        foreach ($temp as $item) {
            if($item) {
                $arr[] = $item;
            }
        }
        return $arr;
    }

    public function JsonParse(){
        $jsonString = File::get('items.json');
        $json = json_decode($jsonString,true);
        foreach ($json['value'][0]['Value']['value']['row'] as $i){
            $city = City::where('city',$i[5]['value'])->first();
            $cat = Cat::where('title',$i[6]['value'])->first();
            $subCat  = SubCat::where('title',$i[7]['value'])->first();
            $item = new Item();
            $item->title = $i[0]['value'];
            $item->cat_id =$cat->id;
            $item->cat_id =$cat->id;
            $item->sub_cat_id = $subCat ? $subCat->id :null;
            $item->city_id  =$city->id;
            $item->price = (int)$i[4]['value'];
            $item->count = $i[3]['value'] ? (int)$i[3]['value'] : 0;
            $item->isNew = $i[10]['value'] ? 1:0;
            $item->isDiscount = $i[11]['value'] ?1:0;
            $item->height = $i[2]['value'] ? (int)$i[2]['value']:0;
            $item->diameter = $i[1]['value'] ? (int)$i[1]['value']:0;
            $item->description = $i[8]['value'];
            $item->bonusPercentage = $i[9]['value'] ? 1:0;
//            $item->save();
        }

        return response(1,200);

    }
}
