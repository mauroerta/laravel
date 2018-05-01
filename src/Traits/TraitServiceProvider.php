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

        $this->publishes([
            __DIR__.'/migrations/draftable_alter_tables.php' => database_path('migrations/'.date('Y_m_d_His').'_draftable_alter_tables.php'),
            __DIR__.'/migrations/slugable_alter_tables.php' => database_path('migrations/'.date('Y_m_d_His').'_slugable_alter_tables.php'),
        ]);
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
