<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Api\Components\Channel\GetChannelListAction;
use App\Http\Controllers\Api\Components\Channel\PostChannelCreateAction;
use App\Http\Controllers\Api\Components\Home\GetHomeDataAction;
use App\Http\Controllers\Api\Components\Message\PostMessagePublishAction;
use App\Http\Controllers\Chat\ChatMediator;
use App\Http\Controllers\Controller;
use App\Repositories\Mids\ChannelCreateMiddleware;
use App\Repositories\Mids\HomeMiddleware;
use App\Repositories\Mids\PublishMiddleware;
use App\Repositories\Mids\NotificationKeyMiddleware;
use App\Repositories\Nulls\NullChannel;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function GetHomeDataAction()
    {
        return ChatMediator::middlewared(HomeMiddleware::class)->proxy(GetHomeDataAction::class);
    }

    public function PostMessagePublishAction()
    {
        return ChatMediator::middlewared(PublishMiddleware::class)->proxy(PostMessagePublishAction::class, \request()->all());
    }

    public function getChannelId(Request $request)
    {
        return channelId($request->from, $request->to);
    }

    public function PostChannelCreateAction()
    {
        return ChatMediator::middlewared(ChannelCreateMiddleware::class)->proxy(PostChannelCreateAction::class, \request()->all());
    }

    public function GetChannelListAction()
    {
        return ChatMediator::proxy(GetChannelListAction::class, \request()->all());
    }
}
