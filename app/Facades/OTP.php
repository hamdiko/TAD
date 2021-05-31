<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;


/**
 * @see \App\Repositories\OTPRepository::class
 */
class OTP extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return 'otp';
    }
}
