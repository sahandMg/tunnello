<?php

namespace App\Http\Controllers\Api\Components\Notification;

use App\Http\Controllers\Api\Components\AbstractComponent;
use App\Repositories\DB\AgentDB;
use App\Services\CurlRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PostNotificationSendAction extends AbstractComponent
{
    public static function execute($arguments = null)
    {
//        $url = config('firebase.base_url');
//        $FcmToken = AgentDB::getAgentRecordById($arguments['recipient'])->pluck('device_key');
//        if (!$FcmToken->isEmpty()) {
//            $serverKey = env('FIRE_BASE_SERVER_KEY');
//            $data = [
//                "registration_ids" => $FcmToken,
//                "notification" => [
//                    "title" => $arguments['title'],
//                    "body" => $arguments['body'],
//                ]
//            ];
//            $encodedData = json_encode($data);
//            $headers = [
//                'Authorization:key=' . $serverKey,
//                'Content-Type: application/json',
//            ];
//            CurlRequest::send($url, $headers, $encodedData);
//        }
    }
}