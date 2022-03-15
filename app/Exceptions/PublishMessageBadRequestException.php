<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class PublishMessageBadRequestException extends Exception
{
    public function render()
    {
        return response()->json($this->getMessage(), Response::HTTP_BAD_REQUEST);
    }
}
