<?php

namespace App\Listeners;

use App\Models\Email;
use App\Models\BlacklistedEmailAddress; // Add this line
use Illuminate\Mail\Events\MessageSending;

class LogSendingMessage
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
    public function handle(MessageSending $event): bool  
    {

        $doSend = true;
        $status = 1; //sending
        $notes = '';

        // TODO These probably do not exist for a mailable
        // do not catch as we want it to bum out!

        $laravelMessageId = $event->data['__laravel_notification_id'];
        $messageType = substr($event->data['__laravel_notification'], strrpos($event->data['__laravel_notification'], '\\') + 1); //only keep the class name not the whole path

        // get the recipients of the email
        $toAddresses = array();
        $recipientCount = 0;
        foreach ($event->message->getTo() as $recipient) {
           $toAddresses[] = $recipient->getAddress();
           $recipientCount++;
        }
        $toAddressString = implode(', ', $toAddresses);

        // get the sender of the email
        $fromAddresses = array();
        foreach ($event->message->getFrom() as $from) {
            $fromAddresses[] = $from->getAddress();
        }
        $fromAddressString = implode(', ', $fromAddresses);

        // do not send if no recipient is specified
        if($recipientCount < 1) {
            $status = 2; //illegal
            $notes = 'No recipient specified';
            $doSend = false;
        }

        // do not send if multiple recipients are specified
        if($doSend && $recipientCount > 1) {
            $status = 2; //illegal
            $notes = 'Cannot send to multiple recipients';
            $doSend = false;
        }

        // check if the recipient is an BlacklistedEmail and do not send if he is
        if($doSend) {
            $blacklistedEmail = BlacklistedEmailAddress::where('email_address', $toAddresses[0])->first();
            if($blacklistedEmail) {
                $status = 3; //blacklisted
                $notes = 'Recipient is blacklisted';
                $doSend = false;
            }
        }

        $emailData = [
            'laravel_message_id' => $laravelMessageId, 
            'email_address' => $toAddressString, 
            'subject' => $event->message->getSubject(),
            'body' => $event->message->getHtmlBody(),
            'sender' => $fromAddressString, 
            'email_status_id' => $status,
            'notes' => $notes,
            'message_type' => $messageType, 
        ];

        // create an email record
        $email = Email::create($emailData);

        return $doSend; //if false, the email will not be sent

    }
}
