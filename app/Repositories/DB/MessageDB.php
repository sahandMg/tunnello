<?php
/**
 * Created by PhpStorm.
 * User: Sahand
 * Date: 3/14/22
 * Time: 9:17 PM
 */

namespace App\Repositories\DB;


use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class MessageDB
{

    public static function getAuthUserSoloMessages()
    {
        return Message::query()
            ->where('sender_id', Auth::id())
            ->orWhere('recipient_id', Auth::id())
            ->orderBy('id', 'desc')
            ->whereNull('group_id')
            ->take(10)
            ->with('sender')
            ->with('recipient')
            ->get();
    }

    public static function getAuthUserGroupMessages()
    {
        return auth()->user()->groups()->with('messages.group')->get()->pluck('messages');
    }

    public static function getAuthUserLastMessage()
    {
        return Message::query()
            ->where('sender_id', Auth::id())
            ->orWhere('recipient_id', Auth::id())
            ->orderBy('id', 'desc')
            ->with('sender')
            ->with('recipient')
            ->with('group')
            ->first();
    }

    public static function createNewMessage($body, int $recipientId, int $senderId)
    {
        return Message::query()->create(['body' => $body, 'recipient_id' => $recipientId, 'sender_id' => $senderId]);
    }

    public static function createNewGroupMessage($body, int $groupId, int $senderId)
    {
        return Message::query()->create(['body' => $body, 'group_id' => $groupId, 'sender_id' => $senderId]);
    }

    public static function getUserP2PMessages($recipient_id)
    {
        return Message::query()
            ->where('sender_id', Auth::id())
            ->orWhere('recipient_id', Auth::id())
            ->where('sender_id', $recipient_id)
            ->orWhere('recipient_id', $recipient_id)
            ->orderBy('id', 'desc')
            ->whereNull('group_id')
            ->take(20)
            ->with('sender')
            ->with('recipient')
            ->get();
    }
}