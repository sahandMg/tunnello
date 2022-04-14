<?php

namespace App\Listeners;

use App\Repositories\DB\AgentDB;
use App\Services\CurlRequest;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NewFriendListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $url = config('firebase.base_url');
        $FcmToken = AgentDB::getAgentRecordById($event->friend->id)->pluck('device_key');
        if (!$FcmToken->isEmpty()) {
            $serverKey = env('FIRE_BASE_SERVER_KEY');
            $data = [
                "registration_ids" => $FcmToken,
                "notification" => [
                    "title" => 'New Message From Tunnello',
                    "body" =>  $event->user->username. ' added you as a friend!',
                    "icon" => public_path('images/tunnello.png')
                ]
            ];
            $encodedData = json_encode($data);
            $headers = [
                'Authorization:key=' . $serverKey,
                'Content-Type: application/json',
            ];
            CurlRequest::send($url, $headers, $encodedData);
        }
    }
}
