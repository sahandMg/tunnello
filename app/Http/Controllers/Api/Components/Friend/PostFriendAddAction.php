<?php

namespace App\Http\Controllers\Api\Components\Friend;

use App\Exceptions\DuplicateFriendException;
use App\Exceptions\SelfFriendException;
use App\Exceptions\UserNotFoundException;
use App\Http\Controllers\Api\Components\AbstractComponent;
use App\Repositories\DB\UserDB;
use App\Repositories\Nulls\NullUser;

class PostFriendAddAction extends AbstractComponent
{
    public static function execute($arguments = null)
    {
        $friend_object = isset($arguments['phone']) ? UserDB::getUserByPhone($arguments['phone']) : UserDB::getUserByUsername($arguments['username']);
        $friend = $friend_object->getOrSend(function () {
            throw new UserNotFoundException();
        });
        if (auth()->id() == $friend->id) {
            throw new SelfFriendException();
        }
        if (!UserDB::checkIfFriendExists($friend->id)) {

            UserDB::attachFriend($friend->id);
            return $friend;
        } else {
            throw new DuplicateFriendException();
        }
    }
}