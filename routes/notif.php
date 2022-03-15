<?php

use App\Http\Controllers\Notif\WebNotifController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function() {

    Route::post('store-token', [WebNotifController::class, 'PostNotificationKeyStore'])->name('store.token');

    Route::post('send-web-notification', [WebNotifController::class, 'PostNotificationSendAction'])->name('send.web-notification');

});