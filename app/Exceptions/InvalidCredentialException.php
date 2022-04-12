<?php

namespace App\Exceptions;

use App\Services\DataFormatter;
use App\Services\ResponseStates;
use Exception;
use Illuminate\Http\Response;

class InvalidCredentialException extends Exception
{
    public function render()
    {
        $data = DataFormatter::shapeJsonResponseData(Response::HTTP_UNAUTHORIZED, ResponseStates::INVALID_CREDS);
        \response()->json($data, Response::HTTP_UNAUTHORIZED)->throwResponse();
    }
}
