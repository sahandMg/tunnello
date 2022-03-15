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

class ChannelCreateValidator
{
    public static function install()
    {
        $v = Validator::make(request()->all(), [
            'recipient_id' => 'required',
            'type' => 'required',
        ]);
        if ($v->fails()) {
            return response()->json($v->getMessageBag(), Response::HTTP_BAD_REQUEST)->throwResponse();
        }
    }
}