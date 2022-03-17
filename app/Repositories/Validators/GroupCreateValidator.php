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

class GroupCreateValidator
{
    public static function install()
    {
        $v = Validator::make(request()->all(),[
            'name'=>'required|string|min:1|max:20',
            'members' => 'required'
        ]);
        if ($v->fails()) {
            return response()->json($v->getMessageBag(), Response::HTTP_BAD_REQUEST)->throwResponse();
        }elseif(auth()->user()->groupOwner->name == request('name')) {
            return response()->json('Group name has already been taken !', Response::HTTP_BAD_REQUEST)->throwResponse();
        }
    }
}