<?php

namespace App\Http\Controllers\RestControllers;

use App\Http\Controllers\PaymentController;
use App\Models\Basket;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderProduct;
use App\Models\PaymentLog;
use App\Models\Shipping;
use App\Models\User;
use App\Rules\maxBonus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function order(Request $request)
    {
        $rules = [
            'payType' => 'required|in:cash,card',
            'shippingTypeId' => 'required|exists:shipping,id',
            'orderDate' => 'required|date_format:"Y-m-d"',
            'bonus' => 'required|integer|min:0|max:'.$request->currentUser->bonus,
            'name' => 'required|string',
            'phone' => 'required|string',
            'email' => 'email',
            'cityId' => 'required|exists:cities,id',
            'street' => 'required|string',
            'house' => 'required|string',
        ];

        $validator = $this->validator($request->all(), $rules);

        if ($validator->fails()) {
            $data['statusCode'] = 400;
            $data['message'] = $validator->errors();
            $data['result'] = null;
            return response()->json($data, $data['statusCode']);
        }

        $shippingPrice = Shipping::findOrFail($request['shippingTypeId'])->price;
        $items = Basket::getTotalPriceBonus($request->currentUser->id);
        $totalPrice = $shippingPrice + $items['totalPrice'] - $request['bonus'];
        $order = Order::create([
            'user_id' => $request->currentUser->id,
            'status' => Order::WAITING,
            'total' => $totalPrice,
            'bonusUser' => $items['totalBonus'],
            'bonusPrice' => $request['bonus'],
            'payType' => $request['payType'],
            'shipping_id' => $request['shippingTypeId'],
            'orderDate' => $request['orderDate'],
        ]);
        $orderDetail = OrderDetail::create([
            'order_id' => $order->id,
            'name' => $request['name'],
            'surname' => $request['surname'],
            'phone' => $request['phone'],
            'email' => $request['email'],
            'city_id' => $request['cityId'],
            'street' => $request['street'],
            'house' => $request['house'],
            'entrance' => $request['entrance'],
            'floor' => $request['floor'],
            'building' => $request['building'],
            'flat_number' => $request['flat_number'],
            'code' => $request['code']
        ]);
        foreach ($items['items'] as $item) {
            $item->order_id = $order->id;
            $items['products'][] = (array) $item;
        }
        OrderProduct::insert($items['products']);
        $order->details = $orderDetail;
        if ($request['payType'] == 'cash') {
            $data['statusCode'] = 200;
            $data['message'] = 'Success';
            $data['result'] = $order;
        } else {
            $order->paymentLink = PaymentController::create(
                $order->total,
                'Оплата покупки',
                $order->id,
                $request->currentUser->id
            );
            $data['statusCode'] = 201;
            $data['message'] = 'Success';
            $data['result'] = $order;
        }

        return response()->json($data, $data['statusCode']);
    }

    public function getOrders(Request $request)
    {
        $orders = Order::where('user_id', $request->currentUser->id)->with('shipping')->get();
        $data['statusCode'] = 200;
        $data['message'] = 'Success';
        $data['result'] = $orders;
        return response()->json($data, $data['statusCode']);
    }

    public function getOrder($orderId)
    {
        $orders = Order::whereId($orderId)->with('shipping')->first();
        $products = OrderProduct::where('order_id', $orderId)->with('item')->get();
        $orders->items = $products;
        $data['statusCode'] = 200;
        $data['message'] = 'Success';
        $data['result'] = $orders;
        return response()->json($data, $data['statusCode']);
    }

    public function checkOrderPayment($orderId)
    {
        $request['orderId'] = $orderId;
        $rules = [
            'orderId' => 'required|exists:orders,id',
        ];

        $validator = $this->validator($request, $rules);

        if ($validator->fails()) {
            $data['statusCode'] = 400;
            $data['message'] = $validator->errors();
            $data['result'] = null;
            return response()->json($data, $data['statusCode']);
        }
        $order = Order::find($orderId);
        $payment = PaymentLog::where('local_order_id', $orderId)->first();
        if ($payment) {
            $order->paymentStatus = $payment->status;
        } else {
            $order->paymentStatus = 'WAITING';
        }
        $data['statusCode'] = 200;
        $data['message'] = 'Success';
        $data['result'] = $order;
        return response()->json($data, $data['statusCode']);
    }

}
