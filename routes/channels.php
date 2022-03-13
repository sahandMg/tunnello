<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

//Broadcast::channel('users.{userId}', function ($user, $userId) {
//    return (int) $user->id === (int) $userId;
//});

Broadcast::channel('chatapp.{recipient_id}.{user_id}', function($user, $res_id, $user_id) {

    return \App\Models\User::find($res_id) !== null && \Illuminate\Support\Facades\Auth::id() == $user_id;
});

Broadcast::channel('chatapp.{user_id}.{recipient_id}', function($user, $user_id, $res_id) {

    return \App\Models\User::find($res_id) !== null && \Illuminate\Support\Facades\Auth::id() == $user_id;
});
