<?php


use App\Http\Controllers\Chat\ChatController;
use App\Http\Controllers\Friend\FriendController;
use App\Http\Controllers\GroupController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function() {

    Route::post('publish', [ChatController::class, 'PostMessagePublishAction'])->name('publish');

    Route::post('channelid', [ChatController::class, 'getChannelId']);

    Route::get('chat', [ChatController::class, 'GetHomeDataAction']);

    Route::prefix('group/')->name('group.')->group(function() {

        Route::post('create', [GroupController::class, 'PostGroupCreateAction'])->name('create');
    });

    Route::prefix('channel/')->name('channel.')->group(function() {

        Route::post('create', [ChatController::class, 'PostChannelCreateAction'])->name('create');
        Route::get('read', [ChatController::class, 'GetChannelListAction'])->name('read');
    });

    Route::prefix('friend/')->name('friend.')->group(function() {
        Route::post('add', [FriendController::class, 'PostFriendAddAction'])->name('add');
    });
});