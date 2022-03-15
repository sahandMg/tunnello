<?php

namespace App\Http\Controllers\Api\Components\Message;

use App\Events\NewGroupMessageEvent;
use App\Events\NewMessageEvent;
use App\Http\Controllers\Api\Components\AbstractComponent;
use App\Models\User;
use App\Repositories\DB\ChannelDB;
use App\Repositories\DB\GroupDB;
use App\Repositories\DB\MessageDB;
use App\Repositories\DB\UserDB;

class PostMessagePublishAction extends AbstractComponent
{
    public static function execute($arguments)
    {
        if ($arguments['type'] == 'solo') {
            $msg = MessageDB::createNewMessage($arguments['msg'], $arguments['to'], $arguments['from']);
            $channel_name = channelId($arguments['from'], $arguments['to']);
            ChannelDB::createNewChannel($arguments['from'], $channel_name);
            ChannelDB::createNewChannel($arguments['to'], $channel_name);
            NewMessageEvent::dispatch(auth()->user(), UserDB::getUserById($arguments['to']), $msg);
        }
        elseif ($arguments['type'] == 'group') {
            $group = GroupDB::getGroupById($arguments['to']);
            $msg = MessageDB::createNewGroupMessage($arguments['msg'], $group->id, $arguments['from']);
            NewGroupMessageEvent::dispatch(auth()->user(), $msg, $group);
        }
    }
}