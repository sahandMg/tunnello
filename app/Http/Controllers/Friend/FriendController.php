<?php

namespace App\Http\Controllers\Friend;

use App\Http\Controllers\Api\Components\Friend\PostFriendAddAction;
use App\Repositories\Mids\FriendAddMiddleware;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class FriendController extends Controller
{
    public function PostFriendAddAction()
    {
        return FriendMedietor::middlewared(FriendAddMiddleware::class)->proxy(PostFriendAddAction::class, \request()->all());
    }
}
