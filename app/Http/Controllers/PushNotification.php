<?php

use App\Models\ServiceProvider;
use App\Models\User;

class PushNotification{
    public static function sendNotification($notificationData)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $user_id = null;
        if($notificationData->user_id){
            $user_id = $notificationData->user_id;
        }
        else if($notificationData->provider_id){
            $provider = ServiceProvider::find($notificationData->provider_id);
            $user_id = $provider->user_id;
        }
        $user = User::where('user_id', $notificationData->user_id)->first();

        if($user && $user->device_token){
            $serverKey = 'BGi461zXgvpUs-scdHBlJMUPzEVx5M49wDOicGiGgPMpzuNprE0uHlTDw4QPg99pJGSzf0b88mO6t-KaW5mTt_g';

            $data = [
                "registration_ids" => $user->device_token,
                "notification" => $notificationData
            ];

            $encodedData = json_encode($data);
            $headers = [
                'Authorization:key=' .$serverKey,
                'Content-Type: application/json',
            ];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            // Disabling SSL Certificate support temporarly
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
            // Execute post
            $result = curl_exec($ch);
            if ($result === FALSE) {
                die('Curl failed: ' . curl_error($ch));
            }
            // Close connection
            curl_close($ch);
            // FCM response
            dd($result);
        }
    }
}
