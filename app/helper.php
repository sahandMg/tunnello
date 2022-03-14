<?php

use Vinkla\Hashids\Facades\Hashids;

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

if (!function_exists('user')) {
    function user()
    {
        return \Illuminate\Support\Facades\Auth::check() ? \Illuminate\Support\Facades\Auth::user() : null;
    }
}

if (!function_exists('encode')) {
    function encode($var) {
        return Hashids::encode($var);
    }
}

if (!function_exists('decode')) {
    function decode($var) {
        return Hashids::decode($var);
    }
}