<?php

namespace App\Listeners;

use App\Entities\User;
use App\Events\PublishActivityEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class StatisticsPublishIncr
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
     * @param  PublishActivityEvent  $event
     * @return void
     */
    public function handle(PublishActivityEvent $event)
    {
        /** @var User $user */
        $user = $event->user;
        $statistics = $user->statistics;
        $statistics->publish++;
        $statistics->save();
    }
}
