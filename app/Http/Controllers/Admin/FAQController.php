<?php

namespace App\Http\Controllers\Admin;

use App\Models\FAQ;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FAQController extends Controller
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
        return view('admin.faq.index', ['faq' => FAQ::all()]);
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
        FAQ::create($request->except('_token'));
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
        return view('admin.faq.edit', ['faq' => FAQ::findOrFail($id)]);
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
        FAQ::findOrFail($id)->update($request->except('_token'));
        return back()->with('message', 'Успешно редактировано');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if (!$request->currentAdmin->role->general >= Role::ACCESS_DELETE) {
            return back()->withErrors('У вас нет доступа');
        }
        FAQ::findOrFail($id)->delete();
        return back()->with('message', 'Успешно удалено');
    }
}
