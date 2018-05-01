<?php

namespace ME\Traits;

use Illuminate\Support\ServiceProvider;

class TraitServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        $this->publishes([
            __DIR__.'/config/me_trait.php' => config_path('me_trait.php'),
        ]);

        $this->loadMigrationsFrom(__DIR__.'/migrations');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        //
    }
}