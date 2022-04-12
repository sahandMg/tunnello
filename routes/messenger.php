<?php


use App\Http\Controllers\Channel\ChannelController;
use App\Http\Controllers\Chat\ChatController;
use App\Http\Controllers\Friend\FriendController;
use App\Http\Controllers\GroupController;
use Illuminate\Support\Facades\Route;

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
});