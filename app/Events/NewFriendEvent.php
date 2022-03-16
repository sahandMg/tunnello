<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewFriendEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $message;
    public $friend;
    public $connection = 'redis';
    public $queue = 'broker';

    public function __construct($user, $friend)
    {
        $this->user = $user;
        $this->friend = $friend;
    }

    public function broadcastOn()
    {
        return new Channel('channels.'.$this->friend->id);
    }

    public function broadcastWith () {
        return [
            'friend_name'      => $this->friend->name,
            'friend_id'      => $this->friend->id,
            'on'              => now()->toDateTimeString(),
        ];
    }
}
