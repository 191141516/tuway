<?php

namespace App\Listeners;

use App\Events\ActivityClearCacheEvent;
use App\Repositories\ActivityRepository;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Prettus\Repository\Events\RepositoryEntityUpdated;

class ClearActivityCache
{
    protected $activityRepository;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(ActivityRepository $activityRepository)
    {
        $this->activityRepository = $activityRepository;
    }

    /**
     * Handle the event.
     *
     * @param  ActivityClearCacheEvent  $event
     * @return void
     */
    public function handle(ActivityClearCacheEvent $event)
    {
        event(new RepositoryEntityUpdated($this->activityRepository, $event->activity));
    }
}
