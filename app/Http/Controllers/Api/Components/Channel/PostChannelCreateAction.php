<?php

namespace App\Http\Controllers\Api\Components\Channel;

use App\Http\Controllers\Api\Components\AbstractComponent;
use App\Repositories\DB\ChannelDB;

class PostChannelCreateAction extends AbstractComponent
{
    public static function execute($arguments = null)
    {
        if ($arguments['type'] == 'solo') {
            ChannelDB::createNewChannel(auth()->id(), channelId(auth()->id(), $arguments['recipient_id']));
            ChannelDB::createNewChannel($arguments['recipient_id'], channelId(auth()->id(), $arguments['recipient_id']));
        }
    }
}