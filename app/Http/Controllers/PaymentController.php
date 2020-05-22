<?php

namespace App\Http\Controllers;

use App\Models\PaymentLog;
use App\Payment;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as GuzzleRequest;

class PaymentController extends Controller
{
    public static function create(float $amount, string $description, int $orderId, int $userId)
    {
        $xml = Payment::createOrder(
            $amount * 100,
            $description,
            $orderId,
            $userId
            );
        $client = new Client();
        $request = new GuzzleRequest(
            'POST',
            Payment::PAYMENT_URL,
            ['Content-Type' => 'text/xml; charset=UTF8'],
            $xml
        );
        $response = $client->send($request);
        $responseBody = simplexml_load_string($response->getBody())->Response;
        if ($responseBody->Status == "00") {
            $url = $responseBody->Order->URL."?ORDERID=".$responseBody->Order->OrderID."&SESSIONID=".$responseBody->Order->SessionID;
            PaymentLog::create([
                'user_id' => $userId,
                'local_order_id' => $orderId,
                'order_id' => $responseBody->Order->OrderID,
                'session_id' => $responseBody->Order->SessionID,
                'amount' => $amount,
                'status' => 'CREATED',
                'description' => $description,
            ]);
        } else {
            $url = 'server error';
        }
        return $url;
    }

    public function approve(Request $request)
    {
        $responseBody = simplexml_load_string($request['xmlmsg']);
        PaymentLog::where('order_id', $responseBody->OrderID)->update([
            'status' => $responseBody->OrderStatus
        ]);
        return redirect()->route('welcome');
    }

    public function decline(Request $request)
    {
        $responseBody = simplexml_load_string($request['xmlmsg']);
        PaymentLog::where('order_id', $responseBody->OrderID)->update([
            'status' => $responseBody->OrderStatus
        ]);
        return redirect()->route('welcome');
    }
    public function decline2(Request $request)
    {
        dd($request->all());
    }

    public function cancel(Request $request)
    {
        $responseBody = simplexml_load_string($request['xmlmsg']);
        PaymentLog::where('order_id', $responseBody->OrderID)->update([
            'status' => $responseBody->OrderStatus
        ]);
        return redirect()->route('welcome');
    }

    public function checkStatus(Request $request)
    {
        $xml = Payment::statusOrder($request['id'], $request['session']);
        $client = new Client();
        $request = new GuzzleRequest(
            'POST',
            Payment::PAYMENT_URL,
            ['Content-Type' => 'text/xml; charset=UTF8'],
            $xml
        );
        $response = $client->send($request);
        return $response->getBody();//response()->json($xml, 200);
    }

    public function test()
    {
        return self::create(1000, 'Оплата покупки', 74, 1);
    }
}
