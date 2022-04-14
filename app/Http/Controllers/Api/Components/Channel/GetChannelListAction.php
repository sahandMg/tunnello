<?php

namespace App\Http\Controllers\Api\Components\Channel;

use App\Http\Controllers\Api\Components\AbstractComponent;
use App\Repositories\DB\ChannelDB;
use App\Repositories\Facades\Response;

class GetChannelListAction extends AbstractComponent
{
    public static function execute($arguments = null)
    {
        $user_solo_channels =  ChannelDB::getAuthUserSoloChannels();
        $user_group_channels = ChannelDB::getAuthUserGroupChannels();
        return Response::readChannels(['solo' => $user_solo_channels, 'group' => $user_group_channels]);
    }
}