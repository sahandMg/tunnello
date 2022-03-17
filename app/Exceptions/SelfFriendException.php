<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;


class SelfFriendException extends Exception
{
    public function render()
    {
        response()->json('Add your self as a friend ?!', Response::HTTP_BAD_REQUEST)->throwResponse();
    }
}
