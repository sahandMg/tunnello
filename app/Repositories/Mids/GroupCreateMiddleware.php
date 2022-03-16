<?php
/**
 * Created by PhpStorm.
 * User: Sahand
 * Date: 3/15/22
 * Time: 1:21 AM
 */

namespace App\Repositories\Mids;


use App\Events\NewGroupEvent;
use App\Jobs\SendHttpReqJob;
use App\Repositories\DB\AgentDB;
use App\Repositories\Validators\GroupCreateValidator;
use App\Services\CurlRequest;
use Illuminate\Http\Response;

class GroupCreateMiddleware
{
    public function handle($data, $next)
    {
        GroupCreateValidator::install();
        $value = $next($data);
        if ($value instanceof \Exception) {
            return $value;
        }
        list($recipients, $group) = $value;
        foreach ($recipients as $recipient) {
            NewGroupEvent::dispatch($recipient, $group);
            SendHttpReqJob::dispatch();
        }
        return response()->json('Ok', Response::HTTP_OK);
    }
}