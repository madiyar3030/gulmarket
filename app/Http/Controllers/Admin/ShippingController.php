<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use App\Models\Role;
use App\Models\Shipping;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShippingController extends Controller
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
        $shipping = Shipping::query();
        $shipping->when(request('city_id'), function ($query) {
            return $query->where('city_id', request('city_id'));
        });
        $shipping = $shipping->with('city')->get();
        return view('admin.shipping.index', ['cities' => $cities, 'shipping' => $shipping]);
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
        Shipping::create($request->except('_token'));
        return back()->with('message', 'Успешно добавлено');
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
        return view('admin.shipping.edit', ['shipping' => Shipping::findOrFail($id), 'cities' => City::all()]);
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
        Shipping::findOrFail($id)->update($request->except('_token'));
        return back()->with('message', 'Успешно редактировано');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if (!$request->currentAdmin->role->general >= Role::ACCESS_DELETE) {
            return back()->withErrors('У вас нет доступа');
        }
        Shipping::find($id)->delete();
        return back()->with('message', 'Успешно удалено');
    }
}
