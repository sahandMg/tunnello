<?php

namespace App\Http\Controllers\Api\Components\Channel;

use App\Http\Controllers\Api\Components\AbstractComponent;
use App\Repositories\DB\ChannelDB;
use Imanghafoori\Helpers\Nullable;

class PostChannelCreateAction extends AbstractComponent
{
    public static function execute($arguments = null)
    {
        // channels for group will be created as soon as user creates a group
        if ($arguments['type'] == 'solo') {
            $arguments['recipient_id'] = decode($arguments['recipient_id']);
            $channel = ChannelDB::createNewChannel(auth()->id(), channelId(auth()->id(), $arguments['recipient_id']));
            ChannelDB::createNewChannel($arguments['recipient_id'], channelId(auth()->id(), $arguments['recipient_id']));
            return new Nullable($channel);
        }
        return new Nullable(null);
    }
}