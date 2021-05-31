<?php

namespace App\Services\SMSServices;

use App\Interfaces\SMSGateway;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

abstract class AbstractSMSService implements SMSGateway
{

    abstract function send($message, $to);
}
