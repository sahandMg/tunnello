<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class PublishMessageBadRequestException extends Exception
{
    public function render()
    {
        response()->json($this->getMessage(), Response::HTTP_BAD_REQUEST)->throwResponse();
    }
}
