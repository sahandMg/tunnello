<?php


use App\Http\Controllers\Chat\ChatController;
use App\Http\Controllers\GroupController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function() {

    Route::post('publish', [ChatController::class, 'PostMessagePublishAction'])->name('publish');

    Route::post('channelid', [ChatController::class, 'getChannelId']);

    Route::post('message-list', [ChatController::class, 'messageList']);

    Route::get('chat', [ChatController::class, 'GetHomeDataAction']);

    Route::prefix('group/')->name('group.')->group(function() {

        Route::post('create', [GroupController::class, 'PostGroupCreateAction'])->name('create');
    });

    Route::prefix('channel/')->name('channel.')->group(function() {

        Route::post('create', [ChatController::class, 'PostChannelCreateAction'])->name('create');
    });
});