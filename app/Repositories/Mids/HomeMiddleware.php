<?php
/**
 * Created by PhpStorm.
 * User: Sahand
 * Date: 3/15/22
 * Time: 1:21 AM
 */

namespace App\Repositories\Mids;


use App\Repositories\DB\AgentDB;

class HomeMiddleware
{
    public function handle($data, $next)
    {
        AgentDB::createNewAgentRecord();
        $value = $next($data);
        return view('chat', $value);
    }
}