<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\Components\Group\PostGroupCreateAction;
use App\Http\Controllers\Chat\ChatMediator;
use App\Http\Requests\GroupCreateRequest;
use App\Repositories\Mids\GroupCreateMiddleware;
use Illuminate\Http\Response;

class GroupController extends Controller
{
    public function PostGroupCreateAction()
    {
        return ChatMediator::middlewared(GroupCreateMiddleware::class)->proxy(PostGroupCreateAction::class, request()->all());
    }
}
