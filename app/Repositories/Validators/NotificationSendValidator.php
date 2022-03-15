<?php
/**
 * Created by PhpStorm.
 * User: Sahand
 * Date: 3/15/22
 * Time: 11:25 AM
 */

namespace App\Repositories\Validators;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class NotificationSendValidator
{
    public static function install()
    {
        $v = Validator::make(request()->all(), [
            'title' => 'required|string|max:100',
            'body' => 'required|string|min:1:max:100',
            'recipient' => 'required'
        ]);
        if ($v->fails()) {
            return response()->json($v->getMessageBag(), Response::HTTP_BAD_REQUEST)->throwResponse();
        }
    }
}