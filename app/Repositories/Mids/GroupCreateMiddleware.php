<?php
/**
 * Created by PhpStorm.
 * User: Sahand
 * Date: 3/15/22
 * Time: 1:21 AM
 */

namespace App\Repositories\Mids;


use App\Events\NewGroupEvent;
use App\Exceptions\ChannelNotFoundException;
use App\Jobs\SendHttpReqJob;
use App\Repositories\DB\AgentDB;
use App\Repositories\DB\ChannelDB;
use App\Repositories\DB\GroupDB;
use App\Repositories\Validators\GroupCreateValidator;
use App\Services\Channel;
use App\Services\CurlRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Imanghafoori\Helpers\Nullable;

class GroupCreateMiddleware
{
    public function handle($data, $next)
    {
        GroupCreateValidator::install();
        $value = $next($data);
        if ($value instanceof \Exception) {
            return $value;
        }
        $group = $value;
        DB::beginTransaction();
        $recipients = GroupDB::getGroupUsers($group);
        $channel = Channel::createChannelForGroupMembers($recipients->pluck('id')->toArray());
        $target_channel = ChannelDB::getGroupOwnerChannelByName($channel->name)
            ->getOrThrow(new ChannelNotFoundException());
        GroupDB::updateGroupChannel($group, $target_channel);
        DB::commit();
        $recipients = $recipients->where('id', '!=' ,auth()->id());
        foreach ($recipients as $recipient) {
            NewGroupEvent::dispatch($recipient, $group);
        }
        return response()->json('Ok', Response::HTTP_OK);
    }
}