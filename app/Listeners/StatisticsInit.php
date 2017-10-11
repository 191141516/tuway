<?php

namespace App\Listeners;

use App\Entities\Statistics;
use App\Entities\User;
use App\Events\UserCreatedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class StatisticsInit
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
     * @param  object  $event
     * @return void
     */
    public function handle(UserCreatedEvent $event)
    {
        /** @var User $user */
        $user = $event->user;

        if ($user->wasRecentlyCreated) {
            $statistics = new Statistics();
            $statistics->user_id = $user->id;
            $statistics->save();
        }
    }
}
