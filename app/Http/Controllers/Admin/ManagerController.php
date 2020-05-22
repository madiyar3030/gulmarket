<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ManagerController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->currentAdmin->role->admin >= Role::ACCESS_READ) {
            return back()->withErrors('У вас нет доступа');
        }
        $roles = Role::all();
        $admins = Admin::with('role')->paginate(10);
        return view('admin.manager.index', compact('admins', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->currentAdmin->role->admin >= Role::ACCESS_CREATE) {
            return back()->withErrors('У вас нет доступа');
        }
        $validator = $this->validator($request->all(),[
            'name' => 'required',
            'username' => 'required|unique:admins',
            'password' => 'required|min:6',
            'role_id' => 'required|exists:roles,id',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        }
        Admin::create($request->except('_token'));
        return back()->withMessage('Успешно добавлено');
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
        if (!$request->currentAdmin->role->admin >= Role::ACCESS_UPDATE) {
            return back()->withErrors('У вас нет доступа');
        }

        $roles = Role::all();
        $admin = Admin::findOrFail($id);
        return view('admin.manager.edit', compact('admin', 'roles'));
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
        if (!$request->currentAdmin->role->admin >= Role::ACCESS_UPDATE) {
            return back()->withErrors('У вас нет доступа');
        }
        $validator = $this->validator($request->all(),[
            'name' => 'required',
            'username' => 'required',
            'password' => 'required|min:6',
            'role_id' => 'required|exists:roles,id',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        }
        Admin::findOrFail($id)->update([
            'name' => $request['name'],
            'username' => $request['username'],
            'password' => $request['password'],
            'role_id' => $request['role_id'],
        ]);
        return back()->withMessage('Успешно редактировано');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request  $request)
    {
        if (!$request->currentAdmin->role->admin >= Role::ACCESS_DELETE) {
            return back()->withErrors('У вас нет доступа');
        }
        Admin::findOrFail($id)->delete();
        return back()->withMessage('Успешно удалено');
    }
}
