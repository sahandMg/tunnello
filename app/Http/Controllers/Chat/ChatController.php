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
        $data =  ChatMediator::proxy(GetHomeDataAction::class);
        return Response::chats($data);
    }

    public function PostPairMessages(Request $request)
    {
        return MessageDB::getUserP2PMessages($request->get('recipient_id'));
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
