<?php
/**
 * Created by PhpStorm.
 * User: Sahand
 * Date: 3/17/22
 * Time: 2:09 PM
 */

namespace App\Services;


use App\Repositories\DB\ChannelDB;
use Imanghafoori\Helpers\Nullable;

class Channel
{
    public static function createChannelForGroupMembers(array $members_id)
    {
        $channel_name = generateChannelName();
        for ($i = 0; $i < count($members_id); $i++) {
            $channel = ChannelDB::createNewChannel($members_id[$i], $channel_name, 'group');
        }
        return $channel;
    }
}