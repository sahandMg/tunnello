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
        $user =  User::query()->find($id);
        if ($user == null) {
            throw new UserNotFoundException();
        }
        return $user;
    }
}