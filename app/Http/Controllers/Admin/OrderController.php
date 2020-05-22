<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->currentAdmin->role->orders >= Role::ACCESS_READ) {
            return back()->withErrors('У вас нет доступа');
        }
        $orders = Order::with('shipping')
            ->with('user')
            ->orderBy('created_at', 'DESC')
            ->paginate(10);
        return view('admin.order.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (!$request->currentAdmin->role->orders >= Role::ACCESS_CREATE) {
            return back()->withErrors('У вас нет доступа');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->currentAdmin->role->orders >= Role::ACCESS_CREATE) {
            return back()->withErrors('У вас нет доступа');
        }
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
        if (!$request->currentAdmin->role->orders >= Role::ACCESS_READ) {
            return back()->withErrors('У вас нет доступа');
        }
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
        if (!$request->currentAdmin->role->orders >= Role::ACCESS_UPDATE) {
            return back()->withErrors('У вас нет доступа');
        }
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
        if (!$request->currentAdmin->role->orders >= Role::ACCESS_UPDATE) {
            return back()->withErrors('У вас нет доступа');
        }
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
        if (!$request->currentAdmin->role->orders >= Role::ACCESS_DELETE) {
            return back()->withErrors('У вас нет доступа');
        }
        $order = Order::findOrFail($id);
        OrderProduct::where('order_id', $order->id)->delete();
    }
}
