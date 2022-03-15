<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Vinkla\Hashids\Facades\Hashids;

class NewGroupMessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $message;
    public $group;
    public $connection = 'redis';
    public $queue = 'broker';

    public function __construct($user, $msg, $group)
    {
        $this->user = $user;
        $this->message = $msg;
        $this->group = $group;
    }

    public function broadcastOn()
    {
        $member_ids = $this->group->users->pluck('id')->sort()->toArray();
        return new Channel(groupChannelId($member_ids));
//        $channels = $this->user->channels->map(function ($channel) {
//            return new Channel($channel->name);
//        })->toArray();
//        return $channels;
    }

    public function broadcastWith () {
        return [
            'sender_name'     => $this->user->name,
            'group_name'      => $this->group->name,
            'sender_id'       => $this->user->id,
            'message'         => $this->message->body,
            'on'              => now()->toDateTimeString(),
        ];
    }
}
