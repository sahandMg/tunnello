<?php
/**
 * Created by PhpStorm.
 * User: Sahand
 * Date: 3/15/22
 * Time: 1:21 AM
 */

namespace App\Repositories\Mids;


use App\Http\Requests\PublishMessageDataRequest;
use App\Repositories\DB\ChannelDB;
use App\Repositories\Validators\ChannelCreateValidator;
use App\Repositories\Validators\PublishValidator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ChannelCreateMiddleware
{
    public function handle($data, $next)
    {
        ChannelCreateValidator::install();
        $value = $next($data);
        $user_solo_channels =  ChannelDB::getAuthUserSoloChannels();
        $user_group_channels = ChannelDB::getAuthUserGroupChannels();
        return response()->json(['solo' => $user_solo_channels, 'group' => $user_group_channels], Response::HTTP_OK);

    }
}