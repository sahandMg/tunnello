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
use App\Repositories\Facades\Response;
use App\Repositories\Validators\ChannelCreateValidator;
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
//            if ($data->wasRecentlyCreated){ Maybe the channel had been created before
                NewChannelEvent::dispatch($data, auth()->user(), decode(request()->get('recipient_id')));
                return Response::createChannel(['solo' => $user_solo_channels, 'group' => $user_group_channels]);
//            }
        }
    }
}