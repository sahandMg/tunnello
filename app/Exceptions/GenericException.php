<?php

namespace App\Exceptions;

use App\Services\DataFormatter;
use Exception;
use Illuminate\Http\Response;

class GenericException extends Exception
{
    public function render()
    {
        $data = DataFormatter::shapeJsonResponseData(Response::HTTP_INTERNAL_SERVER_ERROR, $this->getMessage());
        \response()->json($data, Response::HTTP_INTERNAL_SERVER_ERROR)->throwResponse();

    }
}
