<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

use App\Models\UserLog;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.

        // Check the age of the token to determine which broker should handle it
        $tokenRecord = \DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$tokenRecord) {
            return back()->withInput($request->only('email'))
                        ->withErrors(['email' => __('passwords.token')]);
        }

        $tokenAgeMinutes = now()->diffInMinutes($tokenRecord->created_at);
    
        // If token is older than 60 minutes, only try invitation broker
        // If token is 60 minutes or less, try regular broker first
        if ($tokenAgeMinutes <= 60) {
            // Try regular password reset first (should expire after 60 minutes)
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user) use ($request) {
                    $user->forceFill([
                        'password' => Hash::make($request->password),
                        'remember_token' => Str::random(60),
                    ])->save();

                    UserLog::logMessage("Password reset completed for {$user->email} using regular broker");
                    event(new PasswordReset($user));
                }
            );
            
            // If regular broker fails (token invalid/expired), don't try invitation broker
            // This ensures regular password resets expire after 60 minutes
        } else {
            // Token is older than 60 minutes, only try invitation broker
            $status = Password::broker('users_invitation')->reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user) use ($request) {
                    $user->forceFill([
                        'password' => Hash::make($request->password),
                        'remember_token' => Str::random(60),
                    ])->save();

                    UserLog::logMessage("Password reset completed for {$user->email} using invitation broker");
                    event(new PasswordReset($user));
                }
            );
        }

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        if ($status == Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', __($status));
        } else {
            // Log the specific error that occurred
            UserLog::logMessage("Password reset failed for {$request->email}: " . __($status) . " (token age: {$tokenAgeMinutes} minutes)");
            
            return back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);
        }
    }
}
