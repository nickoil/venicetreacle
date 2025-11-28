<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CallbackLog extends Model
{
    protected $fillable = [
        'service',
        'state',
        'code',
        'error',
        'request_data',
        'response_data',
        'success',
        'status',
        'body',
        'ip_address',
        'user_agent',
        'message'
    ];

    protected $casts = [
        'request_data' => 'array',
        'response_data' => 'array',
        'success' => 'boolean',
    ];
}