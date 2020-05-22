<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->currentAdmin->role->roles >= Role::ACCESS_CREATE) {
            return back()->withErrors('У вас нет доступа');
        }
        Role::create($request->except('_token'));
        return back()->withMessage('Успешно добавлено');
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
        if (!$request->currentAdmin->role->roles >= Role::ACCESS_UPDATE) {
            return back()->withErrors('У вас нет доступа');
        }
        $role = Role::findOrFail($id);
        return view('admin.role.edit', compact('role'));
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
        if (!$request->currentAdmin->role->roles >= Role::ACCESS_UPDATE) {
            return back()->withErrors('У вас нет доступа');
        }
        Role::findOrFail($id)->update($request->except('_token'));
        return back()->withMessage('Успешно редактировано');
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
        if (!$request->currentAdmin->role->roles >= Role::ACCESS_UPDATE) {
            return back()->withErrors('У вас нет доступа');
        }
        Role::findOrFail($id)->delete();
        return back()->withMessage('Успешно удалено');
    }
}
