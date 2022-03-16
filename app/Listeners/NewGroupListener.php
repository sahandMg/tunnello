<?php

namespace App\Listeners;

use App\Repositories\DB\AgentDB;
use App\Services\CurlRequest;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;

class NewGroupListener
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
        $users = $event->group->users;
        foreach ($users as $user) {
            if ($user->id !== $event->user->id) {
                $url = config('firebase.base_url');
                $FcmToken = AgentDB::getAgentRecordById($user->id)->pluck('device_key');
                if (!$FcmToken->isEmpty()) {
                    $serverKey = env('FIRE_BASE_SERVER_KEY');
                    $data = [
                        "registration_ids" => $FcmToken,
                        "notification" => [
                            "title" => 'New Message From Tunnello',
                            "body" =>  $event->user->name. ' added you to '. $event->group->name,
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
}
