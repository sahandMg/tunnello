<?php
/**
 * Created by PhpStorm.
 * User: Sahand
 * Date: 4/10/22
 * Time: 12:00 PM
 */

namespace App\Repositories\DB;


use App\Exceptions\InvalidCredentialException;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthDB
{
    public static function RegisterNewUser(array $data)
    {
        $user = User::query()->create([
            'username' => $data['username'],
            'password' => bcrypt($data['password']),
            'fullname' => $data['fullname'] ?? null,
            'phone' => $data['phone'],
        ]);
        return $user;
    }

    public static function LoginUser(array $data)
    {
        if (isset($data['phone'])) {
            return self::_checkAuth(['phone' => $data['phone'], 'password' => $data['password']]);

        } else {
            return self::_checkAuth(['username' => $data['username'], 'password' => $data['password']]);
        }
    }

    public static function LogoutUser()
    {
        auth()->logout();
    }

    private static function _checkAuth(array $creds)
    {
        if ($token = Auth::attempt($creds)) {
            return $token;
        } else {
            return false;
        }
    }
}