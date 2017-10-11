<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(\App\Repositories\UserRepository::class, \App\Repositories\UserRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\AdminRepository::class, \App\Repositories\AdminRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\ActivityRepository::class, \App\Repositories\ActivityRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\OptionRepository::class, \App\Repositories\OptionRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\EntryRepository::class, \App\Repositories\EntryRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Admin\MenuRepository::class, \App\Repositories\Admin\MenuRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\StatisticsRepository::class, \App\Repositories\StatisticsRepositoryEloquent::class);
        //:end-bindings:
    }
}
