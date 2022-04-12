<?php
/**
 * Created by PhpStorm.
 * User: Sahand
 * Date: 3/15/22
 * Time: 1:44 AM
 */

namespace App\Repositories\Validators;


use App\Exceptions\PublishMessageBadRequestException;
use App\Services\DataFormatter;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class AuthRegisterValidator
{
    public static function install()
    {
        $v = Validator::make(request()->all(), [
            'username'              => 'required|max:72|unique:users',
            'fullname'              => 'sometimes|required|string|max:72',
            'password'              => 'required|confirmed|min:6',
            'password_confirmation' => 'required|min:6',
            'phone'                 => 'required|digits:11|unique:users'
        ]);
        if ($v->fails()) {
            $data = DataFormatter::shapeJsonResponseData(Response::HTTP_BAD_REQUEST, $v->errors()->first());
            return response()->json($data, Response::HTTP_BAD_REQUEST)->throwResponse();
        }
    }
}