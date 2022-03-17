<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\Components\Group\PostGroupCreateAction;
use App\Repositories\Mids\GroupCreateMiddleware;

class GroupController extends Controller
{
    public function PostGroupCreateAction()
    {
        return GroupMediator::middlewared(GroupCreateMiddleware::class)->proxy(PostGroupCreateAction::class, request()->all());
    }
}
