<?php
/**
 * Created by PhpStorm.
 * User: Sahand
 * Date: 3/15/22
 * Time: 1:21 AM
 */

namespace App\Repositories\Mids;

use App\Events\NewFriendEvent;
use App\Repositories\Facades\Response;
use App\Repositories\Validators\FriendAddValidator;


class FriendAddMiddleware
{
    public function handle($data, $next)
    {
        FriendAddValidator::install();
        $value = $next($data);
        if ($value instanceof \Exception) {
            return $value;
        }
        $friend = $value;
        NewFriendEvent::dispatch(auth()->user(), $friend);
        return Response::addFriend($friend);

    }
}