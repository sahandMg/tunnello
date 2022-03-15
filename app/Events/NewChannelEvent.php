<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewChannelEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $connection = 'redis';
    public $queue = 'broker';
    public $channel;
    public $user;
    public $recipient_id;

    public function __construct($channel,User $user, $recipient_id)
    {
        $this->channel = $channel;
        $this->user = $user;
        $this->recipient_id = $recipient_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return [
            new Channel('channels.'.$this->user->id),
            new Channel('channels.'.$this->recipient_id)
        ];
    }

    public function broadcastWith () {
        return [
            'channel_name'     => $this->channel->name,
            'on'              => now()->toDateTimeString(),
        ];
    }
}
