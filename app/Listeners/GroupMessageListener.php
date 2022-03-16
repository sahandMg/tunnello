<?php

namespace App\Listeners;

use App\Repositories\DB\AgentDB;
use App\Services\CurlRequest;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class GroupMessageListener
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
        $sender = $event->user;
        $group = $event->group;
        $member_ids = $group->users->where('id', '!=', $sender->id)->pluck('id')->sort()->toArray();
        for($i = 0; $i < count($member_ids); $i++) {
            $url = config('firebase.base_url');
            $FcmToken = AgentDB::getAgentRecordById($member_ids[$i])->pluck('device_key');
            if (!$FcmToken->isEmpty()) {
                $serverKey = env('FIRE_BASE_SERVER_KEY');
                $data = [
                    "registration_ids" => $FcmToken,
                    "notification" => [
                        "title" => $sender->name.' Sent New Message To '.$group->name,
                        "body" =>  auth()->user()->name. ' added you to '. $group->name,
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
}
