<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageVisit extends Model
{
    protected $fillable = [
        'page',
        'src',
        'ref',
        'ip',
        'user_agent',
    ];
}
