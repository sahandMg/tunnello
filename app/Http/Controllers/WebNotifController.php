<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Jenssegers\Agent\Agent;
use function PHPUnit\Framework\isEmpty;

class WebNotifController extends Controller
{
    public function storeToken(Request $request)
    {
        $record = getSpecificAgentRecord();
        $record->update(['device_key'=>$request->token]);
        return response()->json(['Token successfully stored.']);
    }

    public function sendWebNotification(Request $request)
    {
        $v = Validator::make($request->all(), ['title' => 'required', 'body' => 'required', 'recipient' => 'required']);
        if ($v->fails()) {
            return response()->json('Bad Request', Response::HTTP_BAD_REQUEST);
        }
        $url = 'https://fcm.googleapis.com/fcm/send';
        $FcmToken = getAgentRecords($request->recipient)->pluck('device_key');
        if (!$FcmToken->isEmpty()) {
            $serverKey = env('FIRE_BASE_SERVER_KEY');
            $data = [
                "registration_ids" => $FcmToken,
                "notification" => [
                    "title" => $request->title,
                    "body" => $request->body,
                ]
            ];
            $encodedData = json_encode($data);
            $headers = [
                'Authorization:key=' . $serverKey,
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
            return response()->json('Ok', Response::HTTP_OK);
        }
    }
}
