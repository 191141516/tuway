<?php

namespace App\Listeners;

use App\Events\UserClearCacheEvent;
use App\Repositories\UserRepository;
use Prettus\Repository\Events\RepositoryEntityUpdated;

class ClearUserCache
{
    public $userRepository;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Handle the event.
     *
     * @param  UserClearCacheEvent  $event
     * @return void
     */
    public function handle(UserClearCacheEvent $event)
    {
        event(new RepositoryEntityUpdated($this->userRepository, $event->user));
    }
}
