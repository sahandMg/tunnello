<?php
/**
 * Created by PhpStorm.
 * User: Sahand
 * Date: 3/14/22
 * Time: 9:17 PM
 */

namespace App\Repositories\DB;


use App\Exceptions\UserNotFoundException;
use App\Models\User;
use App\Repositories\Nulls\NullUser;
use Imanghafoori\Helpers\Nullable;

class UserDB
{
    public static function getAllUsersExceptAuthOne()
    {
        return User::query()->where('id', '!=', auth()->id())->get();
    }

    public static function getAuthUserGroups()
    {
        return auth()->user()->groups()->select('name', 'groups.id')->get();
    }

    public static function getUserById($id)
    {
        $user = User::query()->find($id);
        if ($user == null) {
            throw new UserNotFoundException();
        }
        return $user;
    }

    public static function getUserByEmail($email)
    {
        return new Nullable(User::query()->where('email', $email)->first()) ?? new Nullable(null);
    }

    public static function getAuthUserFriends()
    {
        return auth()->user()->friends;
    }

    public static function attachFriend($id)
    {
        auth()->user()->friends()->attach($id);
    }

    public static function checkIfFriendExists($id)
    {
        return auth()->user()->friends()->where('friend_id', $id)->exists() || $id == auth()->id();
    }
}