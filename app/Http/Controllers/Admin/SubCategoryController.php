<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\SubCat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubCategoryController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->currentAdmin->role->categories >= Role::ACCESS_CREATE) {
            return back()->withErrors('У вас нет доступа');
        }
        $request['hidden'] = isset($request['hidden']) ? 1 : 0;
        if (isset($request['image'])) {
            $request['thumb'] = $this->upload($request['image']);
        }
        SubCat::create($request->except('_token', 'image'));
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
        if (!$request->currentAdmin->role->categories >= Role::ACCESS_UPDATE) {
            return back()->withErrors('У вас нет доступа');
        }
        return view('admin.subcategory.edit', ['cat' => SubCat::findOrFail($id)]);
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
        if (!$request->currentAdmin->role->categories >= Role::ACCESS_UPDATE) {
            return back()->withErrors('У вас нет доступа');
        }
        $request['hidden'] = isset($request['hidden']) ? 1 : 0;
        if (isset($request['image'])) {
            $request['thumb'] = $this->upload($request['image']);
        }
        SubCat::findOrfail($id)->update($request->except('_token'));
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
        if (!$request->currentAdmin->role->categories >= Role::ACCESS_DELETE) {
            return back()->withErrors('У вас нет доступа');
        }
        $cat = SubCat::findOrFail($id);
        isset($cat->thumb) ? $this->deleteFile($cat->thumb) : null;
        $cat->delete();
        return back()->withMessage('Успешно удалено');
    }
}
