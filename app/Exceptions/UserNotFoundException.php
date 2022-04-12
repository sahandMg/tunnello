<?php

namespace App\Exceptions;

use App\Services\DataFormatter;
use App\Services\ResponseStates;
use Exception;
use Illuminate\Http\Response;

class UserNotFoundException extends Exception
{
    public function render()
    {
        $data = DataFormatter::shapeJsonResponseData(Response::HTTP_NOT_FOUND, ResponseStates::USER_NOT_FOUND);
        response()->json($data, Response::HTTP_NOT_FOUND)->throwResponse();
    }
}
