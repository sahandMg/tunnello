<?php

namespace App\Http\Controllers\Api\Components\Jwt;

use App\Http\Controllers\Api\Components\AbstractComponent;
use App\Repositories\DB\AuthDB;

class PostJwtLoginAction extends AbstractComponent
{
    public static function execute($arguments)
    {
        return AuthDB::LoginUser($arguments);
    }
}