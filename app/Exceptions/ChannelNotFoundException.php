<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class ChannelNotFoundException extends Exception
{
    public function render()
    {
        response()->json('Channel not found', Response::HTTP_NOT_FOUND)->throwResponse();
    }
}
