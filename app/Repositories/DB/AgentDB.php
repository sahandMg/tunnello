<?php
/**
 * Created by PhpStorm.
 * User: Sahand
 * Date: 3/14/22
 * Time: 9:25 PM
 */

namespace App\Repositories\DB;
use Jenssegers\Agent\Agent;
use  \App\Models\Agent as AgentModel;

class AgentDB
{
    public static function createNewAgentRecord()
    {
        $agent = new Agent();
        AgentModel::query()
            ->where('os', $agent->device())
            ->where('browser', $agent->browser())
            ->where('user_id', auth()->id())
            ->firstOrCreate([
                'os' => $agent->device(),
                'browser' => $agent->browser(),
                'user_id' => auth()->id()
            ]);
    }

    public static function getAgentRecordById($id = null)
    {
        $id = $id == null ? auth()->id() : $id;
        return AgentModel::query()
            ->where( 'user_id', $id)
            ->whereNotNull('device_key')
            ->get();
    }

    public static function getSpecificAgentRecordById($id = null)
    {
        $ag = new Agent();
        $id = $id == null ? auth()->id() : $id;
        return AgentModel::query()
            ->where('os', $ag->device())
            ->where( 'browser', $ag->browser())
            ->where( 'user_id', $id)
            ->first();
    }

    public static function updateAgentDeviceKey($agent, $token)
    {
        $agent->update(['device_key' => $token]);
    }

}