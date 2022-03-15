<?php

namespace App\Http\Controllers\Notif;

use App\Http\Controllers\Api\Components\Notification\PostNotificationKeyStore;
use App\Http\Controllers\Api\Components\Notification\PostNotificationSendAction;
use App\Http\Controllers\Controller;
use App\Repositories\Mids\NotificationKeyMiddleware;
use App\Repositories\Mids\NotificationSendMiddleware;
use Illuminate\Http\Request;

class WebNotifController extends Controller
{
    public function PostNotificationKeyStore()
    {
        return WebNotifMedietor::middlewared(NotificationKeyMiddleware::class)
            ->proxy(PostNotificationKeyStore::class, \request()->all());
    }

    public function PostNotificationSendAction(Request $request)
    {
        return WebNotifMedietor::middlewared(NotificationSendMiddleware::class)
            ->proxy(PostNotificationSendAction::class, $request->all());
    }
}
