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
        $arguments['to'] = decode($arguments['to']);
        $arguments['from'] = decode($arguments['from']);
        if ($arguments['type'] == 'solo') {
            $msg = MessageDB::createNewMessage($arguments['msg'], $arguments['to'], $arguments['from']);
            NewMessageEvent::dispatch(auth()->user(), UserDB::getUserById($arguments['to']), $msg);
            return $msg;
        }
        elseif ($arguments['type'] == 'group') {
            $group = GroupDB::getGroupById($arguments['to']);
            $msg = MessageDB::createNewGroupMessage($arguments['msg'], $group->id, $arguments['from']);
            NewGroupMessageEvent::dispatch(auth()->user(), $msg, $group);
        }
    }
}