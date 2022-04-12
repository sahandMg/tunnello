<?php

namespace App\Exceptions;

use App\Services\DataFormatter;
use App\Services\ResponseStates;
use Exception;
use Illuminate\Http\Response;


class SelfFriendException extends Exception
{
    public function render()
    {
        $data = DataFormatter::shapeJsonResponseData(Response::HTTP_NOT_FOUND, ResponseStates::SELF_FRIEND_ADD);
        response()->json($data, Response::HTTP_NOT_FOUND)->throwResponse();
    }
}
