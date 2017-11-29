<?php

namespace App\Providers;

use App\Events\ActivityClearCacheEvent;
use App\Events\EntryCreatedEvent;
use App\Events\PublishActivityEvent;
use App\Events\UserClearCacheEvent;
use App\Events\UserCreatedEvent;
use App\Listeners\ClearActivityCache;
use App\Listeners\ClearUserCache;
use App\Listeners\StatisticsInit;
use App\Listeners\StatisticsJoinIncr;
use App\Listeners\StatisticsPublishIncr;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        UserCreatedEvent::class => [
            StatisticsInit::class,
//            ClearUserCache::class,
        ],
        PublishActivityEvent::class => [
            StatisticsPublishIncr::class,
//            ClearUserCache::class,
        ],
        EntryCreatedEvent::class => [
            StatisticsJoinIncr::class,
//            ClearUserCache::class,
        ],
//        ActivityClearCacheEvent::class => [
//            ClearActivityCache::class,
//            ClearUserCache::class,
//        ],
//        UserClearCacheEvent::class => [
//            ClearUserCache::class
//        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
