<?php
/**
 * Created by PhpStorm.
 * User: madiy
 * Date: 28.09.2019
 * Time: 14:08
 */

namespace App;


class Firebase
{
    public static function send($to, $message) {
        $fields = array(
            'to' => $to,
            'data' => $message,
            'notification' => $message,
        );
        return self::sendPushNotification($fields);
    }

// sending push message to multiple users by firebase registration ids
    public static function sendMultiple($registration_ids, $message) {
        $fields = array(
            'registration_ids' => $registration_ids,
            'data' => $message,
            'notification' => $message,
        );

        return self::sendPushNotification($fields);
    }

// function makes curl request to firebase servers
    private static function sendPushNotification($fields) {


        // Set POST variables
        $url = 'https://fcm.googleapis.com/fcm/send';

        $headers = array(
            'Authorization: key=AAAAJeO71JI:APA91bEdm6LvhDRIdu7v8Y6-XLmvcTQK48NZXR-3tlbkgadJpp5dmM-doRKtM1pzl8aZ5WVzoMuMLASSGu_dxGLroHPiTqAGWPdW8-xdGFeXdmtGmk-Iw7Dpmzl0FU8zXmxXEWLl4v8A',
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);
        // echo "Result".$result;
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        // Close connection
        curl_close($ch);

        return $result;
    }
}