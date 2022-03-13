<?php

function channelId($num1, $num2) {
    if ($num1 > $num2) {
        return \Vinkla\Hashids\Facades\Hashids::encode("$num2$num1");
    }
    else {
        return \Vinkla\Hashids\Facades\Hashids::encode("$num1$num2");
    }
}

function getSpecificAgentRecord($id = null)
{
    $ag = new \Jenssegers\Agent\Agent();
    $id = $id == null ? \Illuminate\Support\Facades\Auth::id() : $id;
    return \App\Models\Agent::query()
        ->where('os', $ag->device())
        ->where( 'browser', $ag->browser())
        ->where( 'user_id', $id)
        ->first();
}

function getAgentRecords($id = null)
{
    $id = $id == null ? \Illuminate\Support\Facades\Auth::id() : $id;
    return \App\Models\Agent::query()
        ->where( 'user_id', $id)
        ->whereNotNull('device_key')
        ->get();
}