<?php

namespace App\Listeners;

use App\Models\UserLog;
use Illuminate\Auth\Events\Failed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogFailedLogin
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
     *
     * @param  \Illuminate\Auth\Events\Failed  $event
     * @return void
     */
    public function handle(Failed $event)
    {
        $user = $event->user;
        $credentials = $event->credentials;

        // If $user is not null, the user was found but the password did not match.
        // If $user is null, no user was found with that username/email.
        $message = $user
            ? "Failed login attempt for user ID {$user->id} - {$user->email}."
            : "Failed login attempt for unknown user with email {$credentials['email']}.";

        UserLog::logMessage($message);
    }
}
