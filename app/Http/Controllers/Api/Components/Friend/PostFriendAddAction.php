<?php

namespace App\Http\Controllers\Api\Components\Friend;

use App\Exceptions\UserNotFoundException;
use App\Http\Controllers\Api\Components\AbstractComponent;
use App\Repositories\DB\UserDB;
use App\Repositories\Nulls\NullUser;

class PostFriendAddAction extends AbstractComponent
{
    public static function execute($arguments = null)
    {
        $email = $arguments['email'];
        $friend_object = UserDB::getUserByEmail($email);
        $friend = $friend_object->getOrSend(function(){
            throw new UserNotFoundException();
        });
        if (!UserDB::checkIfFriendExists($friend->id)) {

            UserDB::attachFriend($friend->id);
        }
    }
}