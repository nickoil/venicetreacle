<?php

namespace App\Listeners;

use App\Models\Email;
use Illuminate\Mail\Events\MessageSent;

class LogSentMessage
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MessageSent $event): void
    {
        // finalise logging

        $laravelMessageId = $event->data['__laravel_notification_id'];

        $serviceMessageId = null;
        try {
            $serviceMessageId = $event->message->getHeaders()->get('x-ses-message-id')->getValue();
        } catch (\Throwable $e) {
            // we'll see if this is happening as there will be no service message id
        }

        $email = Email::where('laravel_message_id', $laravelMessageId)->first();    
        if($email) {
            $email->sent_time = now();
            $email->service_message_id = $serviceMessageId;
            $email->email_status_id = 4; //sent
            $email->save();
        }
    }
}
