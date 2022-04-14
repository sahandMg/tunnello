<?php

use App\Http\Controllers\Channel\ChannelController;
use App\Http\Controllers\Chat\ChatController;
use App\Http\Controllers\Friend\FriendController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\JwtAuth\JwtAuthController;
use App\Http\Controllers\Notif\WebNotifController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('register',[JwtAuthController::class, 'PostJwtRegisterAction'])->name('auth.register');
Route::post('login',[JwtAuthController::class, 'PostJwtLoginAction'])->name('auth.login');

Route::middleware('auth')->group(function() {

    Route::post('publish', [ChatController::class, 'PostMessagePublishAction'])->name('publish');

//    Route::view('chat', 'chat');
    Route::get('chats', [ChatController::class, 'GetHomeDataAction']);
    Route::post('chats', [ChatController::class, 'PostHomeDataAction']);
    Route::post('pair-messages', [ChatController::class, 'PostPairMessages'])->name('pairMessages');
    Route::prefix('group/')->name('group.')->group(function() {

        Route::post('create', [GroupController::class, 'PostGroupCreateAction'])->name('create');
    });

    Route::prefix('channel/')->name('channel.')->group(function() {

        Route::get('id', [ChannelController::class, 'GetChannelIdAction'])->name('id');
        Route::post('create', [ChannelController::class, 'PostChannelCreateAction'])->name('create');
        Route::get('read', [ChannelController::class, 'GetChannelListAction'])->name('read');
    });

    Route::prefix('friend/')->name('friend.')->group(function() {
        Route::post('add', [FriendController::class, 'PostFriendAddAction'])->name('add');
    });

    Route::post('store-token', [WebNotifController::class, 'PostNotificationKeyStore'])->name('store.token');

    Route::post('send-web-notification', [WebNotifController::class, 'PostNotificationSendAction'])->name('send.web-notification');

    Route::post('logout',[JwtAuthController::class, 'PostJwtLogoutAction'])->name('auth.logout');
});