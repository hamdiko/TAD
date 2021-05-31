<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            'user' => 'App\Models\User',
            'course' => 'App\Models\Course',
            'subject' => 'App\Models\Subject',
            'session' => 'App\Models\Session',
            'subscription' => 'App\Models\Subscription',
            'setting' => 'App\Models\Setting',
        ]);
    }
}
