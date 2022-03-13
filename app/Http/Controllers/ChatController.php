<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    public function index()
    {
        $auth_user_messages = Message::query()
            ->where('sender_id', Auth::id())
            ->orWhere('recipient_id', Auth::id())
//            ->where( 'recipient_id', 2)
//            ->orWhere('sender_id', 2)
            ->orderBy('id', 'desc')
            ->take(10)
            ->with('user')
            ->get();
        $users = User::query()->where('id', '!=', Auth::id())->get();
        $user_channels = User::query()->where('id',  '!=', Auth::id())->get()->pluck('id')->unique()->values()->map(function ($id) {
            return channelId(Auth::id(), $id);
        });
        return view('chat', compact('auth_user_messages', 'users', 'user_channels'));
    }

    public function messageList(Request $request)
    {
        return Message::query()
            ->where('sender_id', Auth::id())
            ->orWhere('recipient_id', Auth::id())
//            ->where( 'recipient_id', $request->to)
//            ->orWhere('sender_id', $request->to)
            ->orderBy('id', 'desc')
            ->with('user')
            ->first();
    }

    public function send(Request $request)
    {
        $v = Validator::make($request->all(), ['msg' => 'required', 'from' => 'required', 'to' => 'required']);
        if ($v->fails()) {
            return response()->json('Message is required', Response::HTTP_BAD_REQUEST);
        }
        $msg = Message::factory()->create(['body' => $request->msg, 'recipient_id' => $request->to, 'sender_id' => $request->from]);
        \App\Events\NewMessageEvent::dispatch(Auth::user(), \App\Models\User::find($request->to), $msg);
        return response()->json('Ok', Response::HTTP_OK);
    }

    public function getChannelId(Request $request)
    {
        return channelId($request->from, $request->to);
    }
}