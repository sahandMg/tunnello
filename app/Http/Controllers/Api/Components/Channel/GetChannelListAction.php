<?php

namespace App\Http\Controllers\Api\Components\Channel;

use App\Http\Controllers\Api\Components\AbstractComponent;
use App\Repositories\DB\ChannelDB;
use Illuminate\Http\Response;

class GetChannelListAction extends AbstractComponent
{
    public static function execute($arguments = null)
    {
        $user_solo_channels =  ChannelDB::getAuthUserSoloChannels();
        $user_group_channels = ChannelDB::getAuthUserGroupChannels();
        return response()->json(['solo' => $user_solo_channels, 'group' => $user_group_channels], Response::HTTP_OK);
    }
}