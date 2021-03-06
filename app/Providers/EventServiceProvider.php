<?php

namespace App\Providers;

use App\Events\NewFriendEvent;
use App\Events\NewGroupEvent;
use App\Events\NewGroupMessageEvent;
use App\Events\NewMessageEvent;
use App\Listeners\GroupMessageListener;
use App\Listeners\NewFriendListener;
use App\Listeners\NewGroupListener;
use App\Listeners\SoloMessageListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        NewMessageEvent::class => [ SoloMessageListener::class ],
        NewGroupMessageEvent::class => [ GroupMessageListener::class ],
        NewGroupEvent::class => [ NewGroupListener::class ],
        NewFriendEvent::class => [ NewFriendListener::class ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
