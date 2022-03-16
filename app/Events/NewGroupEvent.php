<?php

namespace App\Events;

use App\Repositories\DB\ChannelDB;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewGroupEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $recipient;
    public $message;
    public $group;
    public $connection = 'redis';
    public $queue = 'broker';

    public function __construct($recipient, $group)
    {
        $this->recipient = $recipient;
        $this->group = $group;
    }

    public function broadcastOn()
    {
        return new Channel('channels.'.$this->recipient->id);
    }

    public function broadcastWith () {
        return [
            'group_name'      => $this->group->name,
            'group_id'      => $this->group->id,
            'on'              => now()->toDateTimeString(),
        ];
    }
}
