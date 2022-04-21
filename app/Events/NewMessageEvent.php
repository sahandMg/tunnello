<?php

namespace App\Events;

use App\Models\Message;
use App\Models\SocketChannel;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
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
    public $chname = [];
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
//        $channel = SocketChannel::query()->where('name', channelId($this->user->id, $this->recipient->id))->first();
        return new Channel(channelId($this->user->id, $this->recipient->id));
    }

    public function broadcastWith () {
        return [
            'sender_name'     => $this->user->username,
            'recipient_name'  => $this->recipient->username,
            'sender_id'       => encode($this->user->id),
            'recipient_id'    => encode($this->recipient->id),
            'message'         => $this->message->body,
            'reply_message_id'=> $this->message->message_id,
            'channel'         => channelId($this->user->id, $this->recipient->id),
            'on'              => now()->toDateTimeString(),
        ];
    }
}
