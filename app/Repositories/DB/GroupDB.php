<?php
/**
 * Created by PhpStorm.
 * User: Sahand
 * Date: 3/14/22
 * Time: 9:43 PM
 */

namespace App\Repositories\DB;


use App\Models\Group;

class GroupDB
{
    public static function getGroupById($id)
    {
        return Group::query()->where('id', $id)->first();
    }

    public static function createGroup($name)
    {
        return Group::query()
                ->where('name', $name)
                ->where('owner_id', user()->id)
                ->firstOrCreate(['name' => $name, 'owner_id' => user()->id]);
    }

    public static function attachUserToAGroup($group, array $members)
    {
        $group->users()->attach($members);
    }

    public static function getGroupUsers($group)
    {
        return $group->users;
    }
}