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
use App\Repositories\Nulls\NullChannel;
use App\Repositories\Validators\ChannelCreateValidator;
use App\Repositories\Validators\PublishValidator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Imanghafoori\Helpers\Nullable;

class ChannelCreateMiddleware
{
    public function handle($data, $next)
    {
        ChannelCreateValidator::install();
        /**
         * @var $channel Nullable
         */
        $channel = $next($data);
        $user_solo_channels =  ChannelDB::getAuthUserSoloChannels();
        $user_group_channels = ChannelDB::getAuthUserGroupChannels();
        $data = $channel->getOr(false);
        if ($data) {
            if ($data->wasRecentlyCreated){

                NewChannelEvent::dispatch($channel, auth()->user(), request()->get('recipient_id'));
            }
        }
        return response()->json(['solo' => $user_solo_channels, 'group' => $user_group_channels], Response::HTTP_OK);

    }
}