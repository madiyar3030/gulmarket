<?php

namespace App\Http\Controllers\Admin;

use App\Models\Cat;
use App\Models\Role;
use App\Models\SubCat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->currentAdmin->role->categories >= Role::ACCESS_READ) {
            return back()->withErrors('У вас нет доступа');
        }
        return view('admin.category.index', ['cats' => Cat::orderBy('hidden', 'DESC')->withCount('subCats')->paginate(10)]);
    }


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
        Cat::create($request->except('_token', 'image'));
        return back()->withMessage('Успешно добавлено');
    }

    /**
     * Display the specified resource.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        if (!$request->currentAdmin->role->categories >= Role::ACCESS_READ) {
            return back()->withErrors('У вас нет доступа');
        }
        return view('admin.subcategory.index', ['cats' => SubCat::whereCatId($id)->paginate(10), 'cat_id'=>$id]);
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
        if (!$request->currentAdmin->role->categories >= Role::ACCESS_UPDATE) {
            return back()->withErrors('У вас нет доступа');
        }
        return view('admin.category.edit', ['cat' => Cat::findOrFail($id)]);
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
        Cat::findOrfail($id)->update($request->except('_token'));
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
        $cat = Cat::findOrFail($id);
        isset($cat->thumb) ? $this->deleteFile($cat->thumb) : null;
        $cat->delete();
        return back()->withMessage('Успешно удалено');
    }
}
