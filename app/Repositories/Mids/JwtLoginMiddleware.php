<?php
/**
 * Created by PhpStorm.
 * User: Sahand
 * Date: 3/15/22
 * Time: 1:21 AM
 */

namespace App\Repositories\Mids;

use App\Exceptions\GenericException;
use App\Exceptions\InvalidCredentialException;
use App\Models\User;
use App\Repositories\Facades\Response;
use App\Repositories\Validators\AuthLoginValidator;

class JwtLoginMiddleware
{
    /**
     * @param $data
     * @param $next
     * @return string token
     * @throws GenericException
     * @throws \Throwable
     */
    public function handle($data, $next)
    {
        AuthLoginValidator::install();
        $state = $next($data);
        if ($state instanceof \Exception) {
            throw new GenericException($state->getMessage());
        }
        throw_if($state == false, InvalidCredentialException::class);
        return Response::login($state);
    }
}