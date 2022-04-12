<?php
/**
 * Created by PhpStorm.
 * User: Sahand
 * Date: 3/15/22
 * Time: 1:21 AM
 */

namespace App\Repositories\Mids;


use App\Exceptions\AuthRegisterException;
use App\Exceptions\GenericException;
use App\Models\User;
use App\Repositories\Facades\Response;
use App\Repositories\Validators\AuthRegisterValidator;

class JwtAuthMiddleware
{
    public function handle($data, $next)
    {
        AuthRegisterValidator::install();
        /**
         * @param User $user
         */
        $user = $next($data);
        if ($user instanceof \Exception) {
            throw new GenericException($user->getMessage());
        }
        throw_if($user->wasRecentlyCreated == false, AuthRegisterException::class);
        $token = auth()->login($user);
        return Response::register($token);
    }
}