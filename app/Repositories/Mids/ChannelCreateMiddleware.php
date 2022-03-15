<?php
/**
 * Created by PhpStorm.
 * User: Sahand
 * Date: 3/15/22
 * Time: 1:21 AM
 */

namespace App\Repositories\Mids;


use App\Http\Requests\PublishMessageDataRequest;
use App\Repositories\Validators\ChannelCreateValidator;
use App\Repositories\Validators\PublishValidator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ChannelCreateMiddleware
{
    public function handle($data, $next)
    {
        ChannelCreateValidator::install();
        $value = $next($data);
        return response()->json('Ok', Response::HTTP_OK);

    }
}