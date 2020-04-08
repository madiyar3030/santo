<?php
/**
 * Created by PhpStorm.
 * User: Hp
 * Date: 04.07.2019
 * Time: 17:14
 */

namespace App;


use App\Models\Webview;

class FirebasePush
{
    const android = 'android';
    const ios = 'ios';

    public static function sendMessage($title, $body, $user) {
        if ($user && $user->device_token && $user->device_type && $user->push) {
            $data = array(
                'title' => $title,
                'body' => $body,
            );
            return self::sendMultiple([$user->device_token], $data, $user->device_type);
            /*if ($user->device_type == FirebasePush::android) {
                return self::sendAndroid([$user->device_token], $data);
            }
            else if ($user->device_type == FirebasePush::ios) {
                return self::send([$user->device_token], $data);
            }*/
        }
        return null;
    }
    public static function send($to, $message) {
        $fields_android = array(
            'to' => $to.'_a',
            'data' => $message,
        );
        $fields_ios = array(
            'to' => $to,
            'data' => $message,
            'notification' => $message,
        );

        return [
            'android' => self::sendPushNotification($fields_android),
            'ios' => self::sendPushNotification($fields_ios),
        ];
    }

    public static function sendAndroid($to, $message) {
        $fields = array(
            'to' => $to,
            'data' => $message,
        );
        dd($fields);
        return self::sendPushNotification($fields);
    }

// sending push message to multiple users by firebase registration ids
    public static function sendMultiple($registration_ids, $message, $device_type) {
        $fields = array(
            'registration_ids' => $registration_ids,
            'data' => $message,
        );
        if ($device_type == self::ios) {
            $fields['notification'] = $message;
        }
        return self::sendPushNotification($fields);
    }

// function makes curl request to firebase servers
    private static function sendPushNotification($fields) {

        // Set POST variables
        $url = 'https://fcm.googleapis.com/fcm/send';

//        $headers = array(
//            'Authorization: key=AAAAcc0MPkQ:APA91bEWchOMnJNlh1Wugxyh-bw-2V68lyg2DLMF3lpRCk31vpEeKpvQB0t76F1-yDChVI51tlkM79Q1Un1h_D6gdwOajgXanLTu-PUHQWQSgneElwB1kKopVMsPSXxBiRbpTuHdMA5l',
//            'Content-Type: application/json'
//        );
        $headers = array(
            'Authorization: key=AAAAt0T8QcU:APA91bFDwCgsDqJc-pp7Gv3uW_R8qxi9l4dpNP_1NHvMDIag8OJ9vrx4muWlMYO3ztiExTMwh5V-QXfBaeGZKz3OW9IU9slRoUYgOLcLGdaLvsle1_pSphpRGQ3CxIomSXtyQmIR-PPz',
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