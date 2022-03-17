<?php

namespace App\Http\Controllers\Channel;

use App\Http\Controllers\Api\Components\Channel\GetChannelListAction;
use App\Http\Controllers\Api\Components\Channel\PostChannelCreateAction;
use App\Http\Controllers\Controller;
use App\Repositories\Mids\ChannelCreateMiddleware;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
    public function GetChannelIdAction(Request $request)
    {
        return channelId($request->from, $request->to);
    }

    public function PostChannelCreateAction()
    {
        return ChannelMediator::middlewared(ChannelCreateMiddleware::class)->proxy(PostChannelCreateAction::class, \request()->all());
    }

    public function GetChannelListAction()
    {
        return ChannelMediator::proxy(GetChannelListAction::class, \request()->all());
    }
}
