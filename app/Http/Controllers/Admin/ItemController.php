<?php

namespace App\Http\Controllers\Admin;

use App\Models\Cat;
use App\Models\City;
use App\Models\Item;
use App\Models\ItemImage;
use App\Models\Role;
use App\Models\SubCat;
use App\Models\WineClass;
use App\Models\WineCountry;
use App\Models\WineDetail;
use App\Models\WineManufacturer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->currentAdmin->role->general >= Role::ACCESS_READ) {
            return back()->withErrors('У вас нет доступа');
        }
        $cities = City::all();
        $cats = Cat::all();
        if (request('cat_id') && is_numeric(request('cat_id'))) {
            $subCats = SubCat::where('cat_id', request('cat_id'))->get();
        } else {
            $subCats = SubCat::all();
        }
        $query = Item::with('cat')->with('subCat');
        $query->when((request('city_id') && is_numeric(request('city_id'))), function ($query) {
            return $query->where('city_id', request('city_id'));
        });
        $query->when((request('cat_id') && is_numeric(request('cat_id'))), function ($query) {
            return $query->where('cat_id', request('cat_id'));
        });
        $query->when((request('sub_cat_id')&& is_numeric(request('sub_cat_id'))), function ($query) {
            return $query->where('sub_cat_id', request('sub_cat_id'));
        });
        $query->when($request['search'], function ($query) {
            return $query->where('title','LIKE', "%".request('search')."%");
        });
        $items = $query->paginate(15);
        $search = $request['search'];
        return view('admin.item.index', compact('items', 'cities', 'cats', 'subCats','search'));
    }

    /**
     * Show the form for creating a new resource.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (!$request->currentAdmin->role->general >= Role::ACCESS_CREATE) {
            return back()->withErrors('У вас нет доступа');
        }
        $cities = City::all();
        $cats = Cat::all();
        $subCats = SubCat::all();
        $wineClasses = WineClass::all();
        $wineManufacturers = WineManufacturer::all();
        $wineCountries = WineCountry::all();

        return view('admin.item.create', compact('cities', 'cats', 'subCats', 'wineClasses', 'wineManufacturers', 'wineCountries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->currentAdmin->role->general >= Role::ACCESS_CREATE) {
            return back()->withErrors('У вас нет доступа');
        }
        $rules = [
            'title' => 'required',
            'cat_id' => 'required',
//            'sub_cat_id' => 'required',
            'city_id' => 'required',
            'price' => 'required',
            'count' => 'required',
//            'isNew' => 'required',
//            'isDiscount' => 'required',
            'height' => 'integer',
            'diameter' => 'integer',
//            'type' => 'required',
//            'description' => 'required',
            'bonusPercentage' => 'required',
        ];
        $validator = $this->validator($request->all(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        }

        $item = Item::create([
            'title' => $request->get('title'),
            'cat_id' => $request->get('cat_id'),
            'sub_cat_id' => $request->get('sub_cat_id'),
            'city_id' => $request->get('city_id'),
            'price' => $request->get('price'),
            'count' => $request->get('count'),
            'isNew' => $request->get('isNew') ?? 0,
            'isDiscount' => $request->get('isDiscount') ?? 0,
            'height' => $request->get('height'),
            'diameter' => $request->get('diameter'),
            'type' => $request->get('wine') ? 'wine' : 'default',
            'description' => $request->get('description'),
            'bonusPercentage' => $request->get('bonusPercentage'),
        ]);
        $images = [];
        foreach ($request['images'] as $image) {
            array_push($images, (array) [
                'item_id' => $item->id,
                'image_id' => $image
            ]);
        }
        ItemImage::insert($images);
        return back()->with('message', 'Успешно добавлено');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        if (!$request->currentAdmin->role->general >= Role::ACCESS_READ) {
            return back()->withErrors('У вас нет доступа');
        }
        $item = Item::where('id', $id)
            ->with('city')
            ->with('cat')
            ->with('subCat')
            ->with('details')
            ->firstOrFail();
        return view('admin.item.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        if (!$request->currentAdmin->role->general >= Role::ACCESS_UPDATE) {
            return back()->withErrors('У вас нет доступа');
        }
        $item = Item::where('id', $id)
                    ->with('city')
                    ->with('cat')
                    ->with('subCat')
                    ->with('details')
                    ->with('images')
                    ->firstOrFail();
        $cities = City::all();
        $cats = Cat::all();
        $subCats = SubCat::all();
        $wineClasses = WineClass::all();
        $wineManufacturers = WineManufacturer::all();
        $wineCountries = WineCountry::all();
        return view('admin.item.edit',
            compact('item',
                'cities',
                'cats',
                'subCats',
                'wineClasses',
                'wineManufacturers',
                'wineCountries'
            )
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!$request->currentAdmin->role->general >= Role::ACCESS_UPDATE) {
            return back()->withErrors('У вас нет доступа');
        }
        $rules = [
            'title' => 'required',
            'cat_id' => 'required',
//            'sub_cat_id' => 'required',
            'city_id' => 'required',
            'price' => 'required',
            'count' => 'required',
//            'isNew' => 'required',
//            'isDiscount' => 'required',
            'height' => 'integer',
            'diameter' => 'integer',
//            'type' => 'required',
//            'description' => 'required',
            'bonusPercentage' => 'required',
        ];
        $validator = $this->validator($request->all(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        }

        Item::where('id', $id)->update([
            'title' => $request->get('title'),
            'cat_id' => $request->get('cat_id'),
            'sub_cat_id' => $request->get('sub_cat_id'),
            'city_id' => $request->get('city_id'),
            'price' => $request->get('price'),
            'count' => $request->get('count'),
            'isNew' => $request->get('isNew') ?? 0,
            'isDiscount' => $request->get('isDiscount') ?? 0,
            'height' => $request->get('height'),
            'diameter' => $request->get('diameter'),
            'type' => $request->get('wine') ? 'wine' : 'default',
            'description' => $request->get('description'),
            'bonusPercentage' => $request->get('bonusPercentage'),
        ]);
        $images = [];
        if ($request['images']) {

            foreach ($request['images'] as $image) {
                array_push($images, (array) [
                    'item_id' => $id,
                    'image_id' => $image
                ]);
            }
        }
        ItemImage::insert($images);
        return back()->with('message', 'Успешно редактировано');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if (!$request->currentAdmin->role->general >= Role::ACCESS_DELETE) {
            return back()->withErrors('У вас нет доступа');
        }
        $images = ItemImage::where('item_id', $id)->get();
        foreach ($images as $item) {
            $this->deleteFile($item->path);
            $item->delete();
        }
        WineDetail::where('item_id',$id)->delete();
        Item::findOrFail($id)->delete();
        return back()->withMessage('Успешно удалено');
    }
}
