<?php

namespace App\Http\Controllers\Chat;


use App\Http\Controllers\Api\Components\Home\GetHomeDataAction;
use App\Http\Controllers\Api\Components\Home\PostHomeDataAction;
use App\Http\Controllers\Api\Components\Message\PostMessagePublishAction;
use App\Http\Controllers\Controller;
use App\Repositories\DB\MessageDB;
use App\Repositories\Facades\Response;
use App\Repositories\Mids\HomeMiddleware;
use App\Repositories\Mids\PublishMiddleware;
use Illuminate\Http\Request;

class ChatController extends Controller
{

    public function GetHomeDataAction()
    {
        return ChatMediator::middlewared(HomeMiddleware::class)->proxy(GetHomeDataAction::class);
    }

    public function PostPairMessages(Request $request)
    {
        $data = MessageDB::getUserP2PMessages(decode($request->get('recipient_id')));
        return Response::pairMessage($data);
    }

    public function PostHomeDataAction()
    {
        return ChatMediator::middlewared(HomeMiddleware::class)->proxy(PostHomeDataAction::class);
    }

    public function PostMessagePublishAction()
    {
        return ChatMediator::middlewared(PublishMiddleware::class)->proxy(PostMessagePublishAction::class, \request()->all());
    }
}
