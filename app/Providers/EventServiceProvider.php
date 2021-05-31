<?php

namespace App\Providers;

use App\Listeners\LockUserAccount;
use Illuminate\Auth\Events\Lockout;
use App\Listeners\UnlockUserAccount;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        Lockout::class => [
            LockUserAccount::class,
        ],
        PasswordReset::class => [
            UnlockUserAccount::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
