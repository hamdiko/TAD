<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use App\Interfaces\SMSGateway;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendPhoneNumberOTP implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Text message the will be sent to the user's phone
     *
     * @var string
     */
    protected $message;

    /**
     * User phone number
     *
     * @var string|int
     */
    protected $to;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($message, $to)
    {
        $this->message = $message;

        $this->to = $to;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(SMSGateway $smsGateway)
    {
        $smsGateway->send($this->message, $this->to);
    }
}
