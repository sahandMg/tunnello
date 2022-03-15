<?php
/**
 * Created by PhpStorm.
 * User: Sahand
 * Date: 3/15/22
 * Time: 1:21 AM
 */

namespace App\Repositories\Mids;


use App\Repositories\Validators\NotificationSendValidator;
use Illuminate\Http\Response;

class NotificationSendMiddleware
{
    public function handle($data, $next)
    {
        NotificationSendValidator::install();
        $value = $next($data);
        return response()->json('Ok', Response::HTTP_OK);
    }
}