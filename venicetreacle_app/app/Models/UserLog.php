<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserLog extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'url',
        'message',
    ];

    public static function logMessage($message)
    {
        $userId = -1;
        $userEmail = 'Unknown user';
        if(Auth::user() !== null) {
            $user = Auth::user();
            $userId = $user->id;
            $userEmail = $user->email;
        }

        UserLog::create([
            'user_id' => $userId,
            'url' => url()->current(),
            'message' => $userEmail . ' (' . $userId . ') ' . $message,
        ]);

    }

    public function user()
    {
        // there is no foreign key in the user_logs table as user_id logged as -1 will not exist
        return $this->belongsTo(User::class)->withDefault([
            'id' => -1,
            'name' => 'Unknown',
        ]);
    }
}
