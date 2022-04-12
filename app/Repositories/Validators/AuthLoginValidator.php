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
use Illuminate\Validation\Rule;

class AuthLoginValidator
{
    public static function install()
    {
        $v = Validator::make(request()->all(), [
            'username'              => Rule::when(!request()->has('phone'),['required', 'max:72']),
            'password'              => 'required|min:6',
            'phone'                 => Rule::when(!request()->has('username'),['required', 'digits:11'])
        ]);
        if ($v->fails()) {
            return response()->json($v->getMessageBag(), Response::HTTP_BAD_REQUEST)->throwResponse();
        }
    }
}