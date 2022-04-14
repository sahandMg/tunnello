<?php

namespace App\Http\Controllers\Api\Components\Home;

use App\Http\Controllers\Api\Components\AbstractComponent;
use App\Repositories\DB\ChannelDB;
use App\Repositories\DB\MessageDB;
use App\Repositories\DB\UserDB;
use Jenssegers\Agent\Agent;

class GetHomeDataAction extends AbstractComponent
{
    public static function execute($arguments = null)
    {
        $auth_user_messages = MessageDB::getAuthUserSoloMessages();
        $auth_user_group_messages = MessageDB::getAuthUserGroupMessages();
        foreach ($auth_user_group_messages as $grouped) {
            $auth_user_messages = $auth_user_messages->concat($grouped->sortByDesc('id')->values()->take(5));
        }
        $auth_user_messages = $auth_user_messages->unique()->sortByDesc('created_at');
        $friends = UserDB::getAuthUserFriends();
        $user_solo_channels =  ChannelDB::getAuthUserSoloChannels();
        $user_group_channels = ChannelDB::getAuthUserGroupChannels();
        $user_groups = UserDB::getAuthUserGroups();
        return compact('auth_user_messages', 'friends', 'user_solo_channels', 'user_groups', 'user_group_channels');
    }
}