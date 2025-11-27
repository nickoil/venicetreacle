<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SnsNotification extends Model
{
    protected $fillable = [
        'received_time', 'headers', 'request',
    ];

}