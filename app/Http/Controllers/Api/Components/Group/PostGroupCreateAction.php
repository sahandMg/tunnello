<?php

namespace App\Http\Controllers\Api\Components\Group;

use App\Http\Controllers\Api\Components\AbstractComponent;
use App\Repositories\DB\ChannelDB;
use App\Repositories\DB\GroupDB;
use App\Repositories\DB\UserDB;

class PostGroupCreateAction extends AbstractComponent
{
    public static function execute($arguments = null)
    {
        $name = $arguments['name'];
        $members = $arguments['members'];
        $members[] = user()->id;
        $group = GroupDB::createGroup($name);
        GroupDB::attachUserToAGroup($group, $members);
        $recipients = GroupDB::getGroupUsers($group);
        $channel_name = groupChannelId($members);
        foreach ($recipients as $recipient) {
            ChannelDB::createNewChannel($recipient->id, $channel_name, 'group');
        }
        return [$recipients, $group];
    }
}