<?php

namespace App\Http\Controllers\Api\Components\Home;

use App\Http\Controllers\Api\Components\AbstractComponent;
use App\Repositories\DB\AgentDB;
use App\Repositories\DB\ChannelDB;
use App\Repositories\DB\MessageDB;
use App\Repositories\DB\UserDB;

class PostHomeDataAction extends AbstractComponent
{
    public static function execute($arguments = null)
    {
//        $auth_user_messages = MessageDB::getAuthUserSoloMessages();
//        $auth_user_group_messages = MessageDB::getAuthUserGroupMessages();
//        dd($auth_user_group_messages);
//        foreach ($auth_user_group_messages as $grouped) {
//            $auth_user_messages = $auth_user_messages->concat($grouped->sortByDesc('id')->values()->take(5));
//        }
//        $auth_user_messages = $auth_user_messages->unique()->sortByDesc('created_at');
//        $friends = UserDB::getAuthUserFriends();
//        $user_solo_channels =  ChannelDB::getAuthUserSoloChannels();
//        $user_group_channels = ChannelDB::getAuthUserGroupChannels();
//        $user_groups = UserDB::getAuthUserGroups();
//        return compact('auth_user_messages', 'friends', 'user_solo_channels', 'user_groups', 'user_group_channels');
//        $recipient_id = $arguments['recipient_id'];
//        $auth_user_messages = MessageDB::getUserP2PMessages($recipient_id);
//        $auth_user_group_messages = MessageDB::getAuthUserGroupMessages();
//        dd($auth_user_messages);
//        foreach ($auth_user_group_messages as $grouped) {
//            $auth_user_messages = $auth_user_messages->concat($grouped->sortByDesc('id')->values()->take(5));
//        }
//        $auth_user_messages = $auth_user_messages->unique()->sortByDesc('created_at');
//        $friends = UserDB::getAuthUserFriends();
//        $user_solo_channels =  ChannelDB::getAuthUserSoloChannels();
//        $user_group_channels = ChannelDB::getAuthUserGroupChannels();
//        $user_groups = UserDB::getAuthUserGroups();
//        return compact('auth_user_messages', 'friends', 'user_solo_channels', 'user_groups', 'user_group_channels');
    }
}