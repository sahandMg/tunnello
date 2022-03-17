<?php
/**
 * Created by PhpStorm.
 * User: Sahand
 * Date: 3/14/22
 * Time: 9:43 PM
 */

namespace App\Repositories\DB;


use App\Exceptions\ChannelNotFoundException;
use App\Models\Group;
use App\Models\SocketChannel;
use Imanghafoori\Helpers\Nullable;

class GroupDB
{
    public static function getGroupById($id)
    {
        return Group::query()->where('id', $id)->first();
    }

    public static function createGroup($name)
    {
        return Group::query()
                ->where('name', $name)
                ->where('owner_id', user()->id)
                ->firstOrCreate(['name' => $name, 'owner_id' => user()->id]);
    }

    public static function attachUserToAGroup($group, array $members)
    {
        $group->users()->attach($members);
    }

    public static function getGroupUsers($group)
    {
        return $group->users;
    }

    public static function getGroupOwner($group)
    {
        return $group->owner;
    }

    public static function updateGroupChannel(Group $group, $channel)
    {
        // channel that belongs to group owner
        $group->update(['channel_id' => $channel->id]);
    }
}