<?php
/**
 * Created by PhpStorm.
 * User: Sahand
 * Date: 3/15/22
 * Time: 1:21 AM
 */

namespace App\Repositories\Mids;

use App\Events\NewFriendEvent;
use App\Repositories\Validators\FriendAddValidator;
use App\Repositories\Validators\PublishValidator;
use Illuminate\Http\Response;

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
        return response()->json('Ok', Response::HTTP_OK);

    }
}