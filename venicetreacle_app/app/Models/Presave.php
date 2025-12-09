<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presave extends Model
{
    protected $fillable = [
        'spotify_user_id',
        'display_name',
        'email',
        'profile_images',
        'country',
        'product',
        'refresh_token',
        'track_id',
        'state',
        'saved_at',
    ];


    protected $casts = [
        'profile_images' => 'array',
        'saved_at' => 'datetime',
    ];
}
