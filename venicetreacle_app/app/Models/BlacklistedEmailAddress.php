<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlacklistedEmailAddress extends Model
{
    protected $fillable = [
        'email_address',
        'excluded_time',
        'service_message_id',
        'email_status_id',
    ];

    public function email_status()
    {
        return $this->belongsTo(EmailStatus::class);
    }

    public function email()
    {
        return $this->belongsTo(Email::class);
    }

}