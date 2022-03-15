<?php
/**
 * Created by PhpStorm.
 * User: Sahand
 * Date: 3/15/22
 * Time: 1:21 AM
 */

namespace App\Repositories\Mids;

use App\Repositories\Validators\PublishValidator;
use Illuminate\Http\Response;

class PublishMiddleware
{
    public function handle($data, $next)
    {
        PublishValidator::install();
        $value = $next($data);
        return response()->json('Ok', Response::HTTP_OK);

    }
}