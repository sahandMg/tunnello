<?php
/**
 * Created by PhpStorm.
 * User: Sahand
 * Date: 3/14/22
 * Time: 9:23 PM
 */

namespace App\Repositories\DB;


use App\Events\NewChannelEvent;
use App\Models\SocketChannel;
use Imanghafoori\Helpers\Nullable;

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
        $channel = SocketChannel::query()
                ->where('user_id', $channel_owner_id)
                ->where('name', $channel_name)
                ->firstOrCreate([
                    'name' => $channel_name,
                    'user_id' => $channel_owner_id,
                    'type' => $type
                ]);
        return $channel;
    }

    public static function getChannelByName($name)
    {
        return new Nullable(SocketChannel::query()->where('name', $name)->first()) ?? new Nullable(null);
    }
}