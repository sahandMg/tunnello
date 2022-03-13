<?php

namespace App\Listeners;

use App\Events\NewAdminMessageEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNotifToAdmin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\NewAdminMessageEvent  $event
     * @return void
     */
    public function handle(NewAdminMessageEvent $event)
    {

    }
}
