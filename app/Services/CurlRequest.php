<?php
/**
 * Created by PhpStorm.
 * User: Sahand
 * Date: 3/15/22
 * Time: 11:32 AM
 */

namespace App\Services;


use App\Jobs\SendHttpReqJob;

class CurlRequest
{
    public static function send($url, array $headers = [], $encodedData)
    {
        SendHttpReqJob::dispatch($url, $headers, $encodedData);
    }
}