<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class UserNotFoundException extends Exception
{
    public function render()
    {
        return response()->json('User not found', Response::HTTP_NOT_FOUND)->throwResponse();
    }
}