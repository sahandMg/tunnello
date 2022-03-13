<?php

namespace App\Events;

use App\Models\Message;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $user;
    public $recipient;
    public $message;
    public $connection = 'redis';
    public $queue = 'broker';

    public function __construct(User $user, User $recipient, Message $message)
    {
        $this->user = $user;
        $this->recipient = $recipient;
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel(channelId($this->user->id, $this->recipient->id));
    }

    public function broadcastWith () {
        return [
            'sender_name'       => $this->user->name,
            'sender_id'       => $this->user->id,
            'recipient_id'    => $this->recipient->id,
            'message'         => $this->message->body,
            'on'              => now()->toDateTimeString(),
        ];
    }
}
