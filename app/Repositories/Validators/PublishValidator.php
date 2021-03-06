<?php
/**
 * Created by PhpStorm.
 * User: Sahand
 * Date: 3/15/22
 * Time: 1:44 AM
 */

namespace App\Repositories\Validators;


use App\Exceptions\PublishMessageBadRequestException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Services\DataFormatter;

class PublishValidator
{
    public static function install()
    {
        $v = Validator::make(request()->all(), [
            'msg' => 'required|max:1000',
            'from' => 'required',
            'to' => 'required',
            'type' => 'required'
        ]);
        if ($v->fails()) {
            $data = DataFormatter::shapeJsonResponseData(Response::HTTP_BAD_REQUEST, $v->errors()->first());
            return response()->json($data, Response::HTTP_BAD_REQUEST)->throwResponse();
        }
    }
}
