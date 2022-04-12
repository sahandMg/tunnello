<?php

namespace App\Http\Controllers\Api\Components\Jwt;

use App\Http\Controllers\Api\Components\AbstractComponent;
use App\Repositories\DB\AuthDB;
use App\Repositories\DB\UserDB;

class PostJwtRegisterAction extends AbstractComponent
{
    public static function execute($arguments = null)
    {
        return AuthDB::RegisterNewUser($arguments);
    }
}