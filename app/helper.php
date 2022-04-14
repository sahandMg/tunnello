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

function groupChannelId(array $ids) {
    sort($ids);
    return \Vinkla\Hashids\Facades\Hashids::encode($ids);
}

function generateChannelName() {
    return \Illuminate\Support\Str::random(10);
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
        return Hashids::decode($var)[0];
    }
}

if (!function_exists('getToken')) {
    function getToken() {
        return \Tymon\JWTAuth\Facades\JWTAuth::getToken();
    }
}

function randomPhone() {
    return '09113076'.rand(0,9).rand(0,9).rand(0,9);
}