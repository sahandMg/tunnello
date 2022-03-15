<?php
/**
 * Created by PhpStorm.
 * User: Sahand
 * Date: 3/15/22
 * Time: 1:21 AM
 */

namespace App\Repositories\Mids;


use App\Repositories\Validators\NotificationKeyStoreValidator;
use App\Repositories\Validators\NotificationSendValidator;

class NotificationKeyMiddleware
{
    public function handle($data, $next)
    {
        NotificationKeyStoreValidator::install();
        $value = $next($data);
        if ($value instanceof \Exception) {
            return $value;
        }
        return response()->json(['Token successfully stored.']);
    }
}