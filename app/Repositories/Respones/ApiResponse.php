<?php
/**
 * Created by PhpStorm.
 * User: Sahand
 * Date: 3/17/22
 * Time: 9:01 PM
 */

namespace App\Repositories\Respones;


use App\Models\User;
use App\Services\DataFormatter;
use App\Services\ResponseStates;
use Illuminate\Http\Response;


class ApiResponse
{
    public function register($token)
    {
        $user = auth()->user();
        $payload = ['avatar' => $user->avatar,'username' => $user->username, 'phone' => $user->phone, 'fullname' => $user->fullname, 'id' => encode($user->id)];
        $data = DataFormatter::shapeJsonResponseData(Response::HTTP_OK, ResponseStates::REGISTER_SUCCESS, $token, $payload);
        return response()->json($data, Response::HTTP_OK);
    }

    public function login($token)
    {
        $user = auth()->user();
        $payload = ['avatar' => $user->avatar,'username' => $user->username, 'phone' => $user->phone, 'fullname' => $user->fullname, 'id' => encode($user->id)];
        $data = DataFormatter::shapeJsonResponseData(Response::HTTP_OK, ResponseStates::LOGIN_SUCCESS, $token, $payload);
        return response()->json($data, Response::HTTP_OK);
    }

    public function chats($body)
    {
        $data = DataFormatter::shapeJsonResponseData(Response::HTTP_OK, ResponseStates::OK, '', $body);
        return response()->json($data, Response::HTTP_OK);
    }

    public function logout()
    {
        $data = DataFormatter::shapeJsonResponseData(Response::HTTP_OK, ResponseStates::LOG_OUT);
        return response()->json($data, Response::HTTP_OK);
    }

    public function addFriend($friend)
    {
        $body = DataFormatter::modelShaper($friend, ['id','username', 'phone']);
        $body['id'] = encode($body['id']);
        $data = DataFormatter::shapeJsonResponseData(Response::HTTP_OK, ResponseStates::OK,'', $body);
        return response()->json($data, Response::HTTP_OK);
    }

    public function sendMessage()
    {
        $data = DataFormatter::shapeJsonResponseData(Response::HTTP_OK, ResponseStates::OK);
        return response()->json($data, Response::HTTP_OK);
    }

    public function storeToken()
    {
        $data = DataFormatter::shapeJsonResponseData(Response::HTTP_OK, ResponseStates::OK);
        return response()->json($data, Response::HTTP_OK);
    }

    public function createChannel($body)
    {
        $data = DataFormatter::shapeJsonResponseData(Response::HTTP_OK, ResponseStates::OK,'', $body);
        return response()->json($data, Response::HTTP_OK);
    }

    public function readChannels($body)
    {
        $data = DataFormatter::shapeJsonResponseData(Response::HTTP_OK, ResponseStates::OK,'', $body);
        return response()->json($data, Response::HTTP_OK);
    }

    public function pairMessage($body) {
        $data = DataFormatter::shapeJsonResponseData(Response::HTTP_OK, ResponseStates::OK,'', $body);
        return response()->json($data, Response::HTTP_OK);
    }
}