<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\SocketChannel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Vinkla\Hashids\Facades\Hashids;

class GroupController extends Controller
{
    public function create(Request $request)
    {
        $v = Validator::make($request->all(), ['name'=>'required', 'members' => 'required']);
        if ($v->fails()) {
            return response()->json('Bad Request', Response::HTTP_BAD_REQUEST);
        }
        $name = $request->name;
        $members = $request->members;
        $members[] = user()->id;
        $group = Group::query()
            ->where('name', $name)
            ->where('owner_id', user()->id)
            ->firstOrCreate(['name' => $name, 'owner_id' => user()->id]);
        $group->users()->attach($members);
        $recipients = $group->users;
        $channel_name = encode(...$recipients->pluck('id')->sort()->toArray());
        foreach ($recipients as $recipient) {
            $channel = SocketChannel::query()
                ->where('user_id', $recipient->id)
                ->where('name', $channel_name)->firstOrCreate([
                    'name' => $channel_name,
                    'user_id' => $recipient->id,
                    'type' => 'group'
                ]);
        }
        return response()->json('Ok', Response::HTTP_OK);
    }
}
