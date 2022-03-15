<?php
/**
 * Created by PhpStorm.
 * User: Sahand
 * Date: 3/15/22
 * Time: 1:21 AM
 */

namespace App\Repositories\Mids;


use App\Repositories\DB\AgentDB;
use App\Repositories\Validators\GroupCreateValidator;
use Illuminate\Http\Response;

class GroupCreateMiddleware
{
    public function handle($data, $next)
    {
        GroupCreateValidator::install();
        $value = $next($data);
        return response()->json('Ok', Response::HTTP_OK);
    }
}