<?php

namespace App\Http\Controllers\Api\Components;

abstract class AbstractComponent
{
    abstract static function execute($arguments);
}