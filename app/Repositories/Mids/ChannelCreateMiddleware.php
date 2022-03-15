<?php
/**
 * Created by PhpStorm.
 * User: Sahand
 * Date: 3/15/22
 * Time: 1:21 AM
 */

namespace App\Repositories\Mids;


use App\Events\NewChannelEvent;
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
        $channel = $next($data);
        $user_solo_channels =  ChannelDB::getAuthUserSoloChannels();
        $user_group_channels = ChannelDB::getAuthUserGroupChannels();
        if ($channel->wasRecentlyCreated) {
            NewChannelEvent::dispatch($channel, auth()->user(), request()->get('recipient_id'));
        }
        return response()->json(['solo' => $user_solo_channels, 'group' => $user_group_channels], Response::HTTP_OK);

    }
}