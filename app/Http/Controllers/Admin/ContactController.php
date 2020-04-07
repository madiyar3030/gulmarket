<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use App\Models\Contact;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContactController extends Controller
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
        $contacts = Contact::query();
        $contacts->when(request('city_id'), function ($query) {
            return $query->where('city_id', request('city_id'));
        });
        $contacts = $contacts->with('city')->get();
        return view('admin.contacts.index', ['cities' => $cities, 'contacts' => $contacts]);
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
        Contact::create($request->except('_token'));
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
        if (!$request->currentAdmin->role->general >= Role::ACCESS_UPDATE) {
            return back()->withErrors('У вас нет доступа');
        }
        return view('admin.contacts.edit', ['contact' => Contact::findOrFail($id), 'cities' => City::all()]);
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
        Contact::findOrFail($id)->update($request->except('_token'));
        return back()->with('message', 'Успешно редактировано');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!$request->currentAdmin->role->general >= Role::ACCESS_DELETE) {
            return back()->withErrors('У вас нет доступа');
        }
        Contact::find($id)->delete();
        return back()->with('message', 'Успешно удалено');
    }
}
