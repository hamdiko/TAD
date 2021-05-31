<?php

namespace App\Providers;

use Exception;
use Twilio\Rest\Client;
use App\Services\SMSServices\Twilio;
use Illuminate\Support\ServiceProvider;

class TwilioServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/laratwilio.php', 'laratwilio');

        $this->app->bind('laratwilio', function () {
            $this->ensureConfigValuesAreSet();
            $client = new Client(config('laratwilio.account_sid'), config('laratwilio.auth_token'));
            return new Twilio($client);
        });
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishConfig();
        }
    }

    protected function ensureConfigValuesAreSet()
    {
        $mandatoryAttributes = config('laratwilio');

        foreach ($mandatoryAttributes as $key => $value) {
            if (empty($value)) {
                throw new Exception("Please provide a value for ${key}");
            }
        }
    }

}
