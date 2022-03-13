<?php

use App\Events\NewAdminMessageEvent;
use App\Http\Controllers\WebNotifController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware('auth')->group(function() {

    Route::post('send', [\App\Http\Controllers\ChatController::class, 'send']);

    Route::post('channelid', [\App\Http\Controllers\ChatController::class, 'getChannelId']);

    Route::post('message-list', [\App\Http\Controllers\ChatController::class, 'messageList']);

    Route::get('chat', [\App\Http\Controllers\ChatController::class, 'index']);

    Route::post('store-token', [WebNotifController::class, 'storeToken'])->name('store.token');

    Route::post('send-web-notification', [WebNotifController::class, 'sendWebNotification'])->name('send.web-notification');

});


Route::fallback(function(){
    return "Not Found!";
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/', function (){
    return redirect('/login');
});