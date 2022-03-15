<?php
/**
 * Created by PhpStorm.
 * User: Sahand
 * Date: 3/14/22
 * Time: 9:23 PM
 */

namespace App\Repositories\DB;


use App\Models\SocketChannel;

class ChannelDB
{
    public static function getAuthUserSoloChannels()
    {
        return auth()->user()->channels->where('type', 'solo')->pluck('name')->unique()->values();
    }

    public static function getAuthUserGroupChannels()
    {
        return auth()->user()->channels->where('type', 'group')->pluck('name')->unique()->values();
    }

    public static function createNewChannel($channel_owner_id, $channel_name, $type = 'solo')
    {
        return SocketChannel::query()
                ->where('user_id', $channel_owner_id)
                ->where('name', $channel_name)
                ->firstOrCreate([
                    'name' => $channel_name,
                    'user_id' => $channel_owner_id,
                    'type' => $type
                ]);
    }
}