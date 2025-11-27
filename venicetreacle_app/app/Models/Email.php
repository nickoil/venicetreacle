<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    protected $fillable = [
        'laravel_message_id', 'email_address', 'subject', 'body', 'sender', 
        'queued_time', 'sent_time', 'complete_time', 
        'service_message_id', 'email_status_id', 'notes', 'message_type',
    ];

    public function email_status()
    {
        return $this->belongsTo(EmailStatus::class);
    }
}