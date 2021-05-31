<?php

namespace App\Interfaces;

use Illuminate\Http\Client\Response;

interface SMSGateway
{
    /**
     * Send SMS Message
     *
     * @param string message
     * @param string to
     *
     * @return Response
     *
     */
    public function send($message, $to);
}
