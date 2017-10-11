<?php

namespace App\Listeners;

use App\Entities\User;
use App\Events\EntryCreatedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class StatisticsJoinIncr
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
     * @param  EntryCreatedEvent  $event
     * @return void
     */
    public function handle(EntryCreatedEvent $event)
    {
        /** @var User $user */
        $user = $event->user;
        $statistics = $user->statistics;
        $statistics->join++;
        $statistics->save();
    }
}
