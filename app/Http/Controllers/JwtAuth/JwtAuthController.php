<?php

namespace App\Http\Controllers\JwtAuth;

use App\Http\Controllers\Api\Components\Jwt\PostJwtLoginAction;
use App\Http\Controllers\Api\Components\Jwt\PostJwtLogoutAction;
use App\Http\Controllers\Api\Components\Jwt\PostJwtRegisterAction;
use App\Http\Controllers\Controller;
use App\Repositories\Facades\Response;
use App\Repositories\Mids\JwtAuthMiddleware;
use App\Repositories\Mids\JwtLoginMiddleware;
use Illuminate\Http\Request;

class JwtAuthController extends Controller
{
    public function PostJwtRegisterAction()
    {
        return JwtAuthMedietor::middlewared(JwtAuthMiddleware::class)->proxy(PostJwtRegisterAction::class, \request()->all());
    }

    public function PostJwtLoginAction()
    {
        return JwtAuthMedietor::middlewared(JwtLoginMiddleware::class)->proxy(PostJwtLoginAction::class, \request()->all());
    }

    public function PostJwtLogoutAction()
    {
        JwtAuthMedietor::proxy(PostJwtLogoutAction::class);
        return Response::logout();
    }
}
