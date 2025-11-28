<?php

namespace App\Listeners;

use App\Models\UserLog;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogPasswordReset
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
     * @param  \Illuminate\Auth\Events\PasswordReset  $event
     * @return void
     */
    public function handle(PasswordReset $event)
    {
        // now done in NewPasswordController
        // $user = $event->user;
        // UserLog::logMessage("Password reset for {$user->email} ({$user->id})");
    }
}
