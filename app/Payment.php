<?php

namespace App;

use Spatie\ArrayToXml\ArrayToXml;

class Payment
{
    const PAYMENT_URL = 'https://ecomtst.fortebank.com/Exec';
    const merchantID = 'FORTEST';
    private $username = 'FORTEST';
    private $password = 'FORTEST7898';

    public static function createOrder(float $amount, string $description, int $orderId, int $userId)
    {
        $data = [
            'Request' => [
                'Operation' => 'CreateOrder',
                'Language' => 'RU',
                'Order' => [
                    'Merchant' => self::merchantID,
                    'Amount' => $amount,
                    'Currency' => 398,
                    'Description' => $description,
                    'ApproveURL' => route('approvePayment'),
                    'CancelURL' => route('cancelPayment'),
                    'DeclineURL' => route('declinePayment'),
                    'OrderType' => 'Payment',
                    'Fee' => 0,
                    'AddParams' => [
                        'OrderID' => $orderId,
                        'UserID' => $userId,
                        'AcctType' => 4,
                        'OrderExpirationPeriod' => '30',
                    ]
                ],
            ]
        ];
        return ArrayToXml::convert($data, 'TKKPG');
    }

    public static function statusOrder($orderId, $sessionId)
    {
        $data = [
            'Request' => [
                'Operation' => 'GetOrderStatus',
                'Language' => 'RU',
                'Order' => [
                    'Merchant' => 'FORTEST',
                    'OrderID' => $orderId
                ],
                'SessionID' => $sessionId
            ]
        ];
        return ArrayToXml::convert($data, 'TKKPG');
    }

    public function getOrders()
    {
        $data = [
            'Request' => [
                'Operation' => 'GetOrders',
                'Language' => 'RU',
                'Merchant' => 'FORTEST'
            ]
        ];
        return ArrayToXml::convert($data, 'TKKPG');
    }
}