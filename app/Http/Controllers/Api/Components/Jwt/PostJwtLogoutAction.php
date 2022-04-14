<?php

namespace App\Http\Controllers\Api\Components\Jwt;

use App\Http\Controllers\Api\Components\AbstractComponent;
use App\Repositories\DB\AgentDB;
use App\Repositories\DB\AuthDB;

class PostJwtLogoutAction extends AbstractComponent
{
    public static function execute($arguments = null)
    {
        AgentDB::clearUserDeviceKey();
        AuthDB::LogoutUser();
    }
}