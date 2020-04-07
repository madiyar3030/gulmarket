<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClientController extends Controller
{

    public function getUsers(Request $request)
    {
        $users = User::with('address');
        if ($request['search']){
            $users = $users->where('name','LIKE',"%$request->search%")
                ->orWhere('phone','LIKE',"%$request->search%")
                ->orWhere('email','LIKE',"%$request->search%");
        }
        return view('admin.client.index', ['users'=>$users->paginate(30),'search'=>$request['search']]);
    }
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->currentAdmin->role->users >= Role::ACCESS_READ) {
            return back()->withErrors('У вас нет доступа');
        }
        $users = User::with('address')->paginate(20);
        return view('admin.client.index', compact('users'));
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
        if (!$request->currentAdmin->role->users >= Role::ACCESS_UPDATE) {
            return back()->withErrors('У вас нет доступа');
        }
        $user = User::findOrFail($id);
        $user->blocked = $user->blocked == 1 ? 0 : 1;
        $user->save();
        return back()->withMessage($user->blocked == 1 ? 'Успешно заблокировано' : 'Успешно разблокировано');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        if (!$request->currentAdmin->role->users >= Role::ACCESS_READ) {
            return back()->withErrors('У вас нет доступа');
        }
        $user = User::where('id', $id)->with('address')->firstOrFail();
        $orders = Order::where('user_id', $id)->with('shipping')->paginate(10);
        return view('admin.client.show', compact('user', 'orders'));
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
        if (!$request->currentAdmin->role->users >= Role::ACCESS_DELETE) {
            return back()->withErrors('У вас нет доступа');
        }
        User::where('id', $id)->with('address')->deleteOrFail();
        return back()->withMessage('Успешно удалено');
    }
}
