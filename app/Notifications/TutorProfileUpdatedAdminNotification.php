<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TutorProfileUpdatedAdminNotification extends Notification implements ShouldQueue
{
    use Queueable;
	
    public $tutor;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($tutor)
    {
        $this->tutor = $tutor;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
	        ->subject(__('tutor_profile.tutor_profile_updated_mail_notify_subject'))
	        ->markdown('notifications.tutor_profile_updated',['tutor'=> $this->tutor]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
