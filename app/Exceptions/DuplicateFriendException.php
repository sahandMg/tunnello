<?php

namespace App\Exceptions;

use App\Services\DataFormatter;
use App\Services\ResponseStates;
use Exception;
use Illuminate\Http\Response;

class DuplicateFriendException extends Exception
{
    public function render()
    {
        $data = DataFormatter::shapeJsonResponseData(Response::HTTP_BAD_REQUEST, ResponseStates::DUPLICATE_FRIEND);
        return response()->json($data, Response::HTTP_UNAUTHORIZED)->throwResponse();
    }
}
