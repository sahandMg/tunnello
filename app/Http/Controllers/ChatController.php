<?php

namespace App\Http\Controllers;

use App\Events\NewGroupMessageEvent;
use App\Models\Group;
use App\Models\Message;
use App\Models\SocketChannel;
use App\Models\User;
use Grpc\Channel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;
use  \App\Models\Agent as AgentModel;
use Vinkla\Hashids\Facades\Hashids;

class ChatController extends Controller
{
    public function index()
    {
        $auth_user_messages = Message::query()
            ->where('sender_id', Auth::id())
            ->orWhere('recipient_id', Auth::id())
            ->orderBy('id', 'desc')
            ->whereNull('group_id')
            ->take(10)
            ->with('sender')
            ->with('recipient')
//            ->with('group')
            ->get();
        $auth_user_group_messages = \user()->groups()->with('messages')->get()->pluck('messages');
        foreach ($auth_user_group_messages as $grouped) {
            $auth_user_messages = $auth_user_messages->concat($grouped->sortByDesc('id')->values()->take(5));
        }
        $auth_user_messages = $auth_user_messages->sortByDesc('created_at');
        $users = User::query()->where('id', '!=', Auth::id())->get();
        $user_solo_channels = \user()->channels->where('type', 'solo')->pluck('name')->unique()->values();
        $user_group_channels = \user()->channels->where('type', 'group')->pluck('name')->unique()->values();
        $agent = new Agent();
        AgentModel::query()
            ->where('os', $agent->device())
            ->where('browser', $agent->browser())
            ->where('user_id', Auth::id())
            ->firstOrCreate([
                'os' => $agent->device(),
                'browser' => $agent->browser(),
                'user_id' => Auth::id()
        ]);
        $user_groups = \user()->groups()->select('name', 'groups.id')->get();
        return view('chat', compact('auth_user_messages', 'users', 'user_solo_channels', 'user_groups', 'user_group_channels'));
    }

    public function messageList(Request $request)
    {
        if ($request->type == 'solo') {
            return Message::query()
                ->where('sender_id', Auth::id())
                ->orWhere('recipient_id', Auth::id())
//            ->where( 'recipient_id', $request->to)
//            ->orWhere('sender_id', $request->to)
                ->orderBy('id', 'desc')
                ->with('sender')
                ->with('recipient')
                ->with('group')
                ->first();
        }elseif ($request->type == 'group') {
            $auth_user_group_messages = \user()->groups()->with('messages')->get()->pluck('messages');
            return Message::query()
                ->where('sender_id', Auth::id())
                ->orWhere('recipient_id', Auth::id())
//            ->where( 'recipient_id', $request->to)
//            ->orWhere('sender_id', $request->to)
                ->orderBy('id', 'desc')
                ->with('sender')
                ->with('recipient')
                ->with('group')
                ->first();
        }

    }

    public function send(Request $request)
    {
        $v = Validator::make($request->all(), ['msg' => 'required', 'from' => 'required', 'to' => 'required', 'type' => 'required']);
        if ($v->fails()) {
            return response()->json('Message is required', Response::HTTP_BAD_REQUEST);
        }
        if ($request->type == 'solo') {
            $msg = Message::factory()->create(['body' => $request->msg, 'recipient_id' => $request->to, 'sender_id' => $request->from]);
            $channel_name = channelId($request->from, $request->to);
            $channel = SocketChannel::query()->where('user_id', $request->from)->where('name', $channel_name)->firstOrCreate([
                'name' => $channel_name,
                'user_id' => $request->from,
                'type' => 'solo'
            ]);
            $channel = SocketChannel::query()->where('user_id', $request->to)->where('name', $channel_name)->firstOrCreate([
                'name' => $channel_name,
                'user_id' => $request->to,
                'type' => 'solo'
            ]);
            \App\Events\NewMessageEvent::dispatch(Auth::user(), User::find($request->to), $msg);
        }
        elseif ($request->type == 'group') {
            $group = Group::query()->where('id', $request->to)->first();
//            $recipients = $group->users;
//            foreach ($recipients as $recipient) {
            $msg = Message::query()->create([
                'body' => $request->msg,
                'sender_id' => $request->from,
                'group_id' => $group->id
            ]);
//            }
            NewGroupMessageEvent::dispatch(Auth::user(), $msg, $group);
        }
        return response()->json('Ok', Response::HTTP_OK);
    }

    public function getChannelId(Request $request)
    {
        return channelId($request->from, $request->to);
    }
}
