<?php

namespace App\Http\Controllers\RestControllers;

use App\Http\Resources\ItemResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;
use App\Models\Basket;
use Tymon\JWTAuth\Exceptions\JWTException;

class BasketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $baskets = Basket::with('item')->whereUserId($request->currentUser->id)->get();
        $bonus = 0;
        $price = 0;
        $arr = [];
        foreach ($baskets as $item) {
            $bonus += $item->item ? (($item->item->price * $item->item->bonusPercentage / 100) * $item->count) : 0;
            $price += $item->item ? ($item->item->price * $item->count) : 0;

            $t['id'] =$item->id;
            $t['count'] =$item->count;
            $t['item'] = new ItemResource($item->item);
            $arr[] = $t;
        }

        $data['result']['totalPrice'] = $price;
        $data['result']['totalBonus'] = $bonus;
        $data['result']['data'] = $arr;
        $data['message'] = 'success';
        $data['statusCode'] = 200;
        return response()->json($data, $data['statusCode']);
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'item_id' => 'required|exists:items,id',
            'count' => 'required|integer'
        ];

        $validator = $this->validator($request->all(), $rules);

        if ($validator->fails()) {
            $data['statusCode'] = 400;
            $data['message'] = $validator->errors();
            $data['result'] = null;
            return response()->json($data, $data['statusCode']);
        }

        Basket::create([
            'user_id' => $request->currentUser->id,
            'item_id' => $request['item_id'],
            'count' => $request['count']
        ]);

        $data['statusCode'] = 200;
        $data['message'] = 'success';
        $data['result'] = null;

        return response()->json($data, $data['statusCode']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return response();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $rules = [
            'basket' => 'required|array',
            'basket.*.item_id' => 'distinct|exists:items,id',
            'basket.*.count'  => 'integer'
        ];
        $validator = $this->validator($request->all(), $rules);

        if ($validator->fails()) {
            $data['statusCode'] = 400;
            $data['message'] = $validator->errors();
            $data['result'] = null;
            return response()->json($data, $data['statusCode']);
        }
        Basket::where('user_id',$request->currentUser->id)->delete();
        if  (count($request['basket']) > 0){
            foreach ($request['basket'] as $item) {
                Basket::create([
                    'user_id' => $request->currentUser->id,
                    'item_id' => $item['item_id'],
                    'count' => $item['count']
                ]);
            }
        }
        $data['statusCode'] = 200;
        $data['message'] = 'success';
        $data['result'] = null;
        return response()->json($data, $data['statusCode']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $request['basketId'] = $id;
        $rules = [
            'basketId' => 'required|exists:baskets,id'
        ];

        $validator = $this->validator($request->all(), $rules);

        if ($validator->fails()) {
            $data['message'] = $validator->errors();
            return response()->json($data, 400);
        }
        Basket::whereId($id)->whereUserId($request->currentUser->id)->delete();

        $data['message'] = 'success';
        $data['statusCode'] = 200;
        $data['result'] = null;

        return response()->json($data, $data['statusCode']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroyAll(Request $request)
    {
        Basket::whereUserId($request->currentUser->id)->delete();

        $data['message'] = 'success';
        $data['statusCode'] = 200;
        $data['result'] = null;

        return response()->json($data, $data['statusCode']);
    }
}
