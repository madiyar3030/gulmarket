<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\WineClass;
use App\Models\WineCountry;
use App\Models\WineManufacturer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->currentAdmin->role->lists >= Role::ACCESS_READ) {
            return back()->withErrors('У вас нет доступа');
        }
        switch (strtolower($request['type'])){
            case 'class':
                $title = 'Сорт';
                $items = WineClass::all();
                break;
            case 'country':
                $title = 'Страна';
                $items = WineCountry::all();
                break;
            case 'manufacturer':
                $title = 'Производители';
                $items = WineManufacturer::all();
                break;
            default:
                $request['type'] = '';
                $title = '';
                $items = [];
                break;
        }
        return view('admin.wine.index', ['items' => $items, 'type' => $request['type'], 'title' => $title]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->currentAdmin->role->lists >= Role::ACCESS_UPDATE) {
            return back()->withErrors('У вас нет доступа');
        }
        switch (strtolower($request['type'])){
            case 'class':
                WineClass::create($request->except('type', '_token'));
                break;
            case 'country':
                WineCountry::create($request->except('type', '_token'));
                break;
            case 'manufacturer':
                WineManufacturer::create($request->except('type', '_token'));
                break;
            default:
                return back();
                break;
        }
        return back()->with('message', 'Успешно добавлено');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        if (!$request->currentAdmin->role->lists >= Role::ACCESS_UPDATE) {
            return back()->withErrors('У вас нет доступа');
        }
        switch (strtolower($request['type'])){
            case 'class':
                $item = WineClass::findOrFail($id);
                $type = $request['type'];
                break;
            case 'country':
                $item = WineCountry::findOrFail($id);
                $type = $request['type'];
                break;
            case 'manufacturer':
                $item = WineManufacturer::findOrFail($id);
                $type = $request['type'];
                break;
            default:
                return back();
                break;
        }
        return view('admin.wine.edit', compact('item', 'type'));
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
        if (!$request->currentAdmin->role->lists >= Role::ACCESS_UPDATE) {
            return back()->withErrors('У вас нет доступа');
        }
        switch (strtolower($request['type'])){
            case 'class':
                WineClass::findOrFail($id)->update([
                    'title' => $request['title']
                ]);
                break;
            case 'country':
                WineCountry::findOrFail($id)->update([
                    'title' => $request['title']
                ]);
                break;
            case 'manufacturer':
                WineManufacturer::findOrFail($id)->update([
                    'title' => $request['title']
                ]);
                break;
            default:
                return back();
                break;
        }
        return back()->withMessage('Успешно редактировано');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if (!$request->currentAdmin->role->lists >= Role::ACCESS_DELETE) {
            return back()->withErrors('У вас нет доступа');
        }
        switch (strtolower($request['type'])){
            case 'class':
                WineClass::findOrFail($id)->delete();
                break;
            case 'country':
                WineCountry::findOrFail($id)->delete();
                break;
            case 'manufacturer':
                WineManufacturer::findOrFail($id)->delete();
                break;
            default:
                return back();
                break;
        }
        return back()->with('message', 'Успешно удалено');
    }
}
