<?php

namespace App\Http\Controllers\Api\Components\Notification;

use App\Http\Controllers\Api\Components\AbstractComponent;
use App\Repositories\DB\AgentDB;

class PostNotificationKeyStore extends AbstractComponent
{
    public static function execute($arguments = null)
    {
        $record = AgentDB::getSpecificAgentRecordById();
        if ($record->device_key == null) {
            AgentDB::updateAgentDeviceKey($record, $arguments['token']);
        }
    }
}
