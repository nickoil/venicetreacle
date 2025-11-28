<?php

namespace App\Http\Controllers;


use App\Models\CallbackLog;
use App\Models\Presave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SmartLinkController extends Controller
{
    public function modernElixir()
    {
        return view('smartlinks.modern-elixir');
    }

    public function badAji()
    {
        return view('smartlinks.bad-aji');
    }


    public function spotifyCallback(Request $request)
    {
        $success = true;
        $message = 'Successfully presaved';
        $status = 'na';
        $body = 'na';

        $code = $request->get('code');
        $state = $request->get('state');

        if (!$code) {
            $success = false;
            $message = 'Missing code parameter';
        }

        if($success) {
            // =====================
            // Exchange code for token
            // =====================
            $tokenResponse = Http::asForm()->withBasicAuth(
                config('services.spotify.client_id'),
                config('services.spotify.client_secret')
            )->post('https://accounts.spotify.com/api/token', [
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => config('services.spotify.redirect_uri'),
            ]);

            $status = $tokenResponse->status();
            $body = $tokenResponse->body();

            if ($tokenResponse->failed()) {
                $success = false;
                $message = 'Failed to exchange code for token';
            }
        }

        if($success) {
            $tokens = $tokenResponse->json();
            $accessToken = $tokens['access_token'];
            $refreshToken = $tokens['refresh_token'];

            // =====================
            // Get user profile
            // =====================
            $userResponse = Http::withToken($accessToken)->get('https://api.spotify.com/v1/me');

            $status = $userResponse->status();
            $body = $userResponse->body();

            if ($userResponse->failed()) {
                $success = false;
                $message = 'Failed to get user profile';
            }
        }

        if($success) {
            $user = $userResponse->json();  
        

            // =====================
            // Store Pre-Save
            // =====================
            Presave::updateOrCreate(
            ['spotify_user_id' => $user['id'], 'track_id' => $state],
            [
                'display_name' => $user['display_name'] ?? null,
                'email' => $user['email'] ?? null,
                'profile_images' => $user['images'] ?? [],
                'country' => $user['country'] ?? null,
                'product' => $user['product'] ?? null,
                'refresh_token' => $refreshToken,
                'state' => $state,
                ]
            );
        }

        CallbackLog::create([
            'service' => 'spotify',
            'state' => $state,
            'code' => $code,
            'request_data' => $request->all(),
            'success' => $success,
            'status' => $status,
            'body' => $body,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'message' => $message,
        ]);

        if ($success) {
            
            return redirect()->route('smartlinks.bad-aji')
                ->with('success', 'Successfully pre-saved to Spotify! Thanks for your support! 🎵');
        }

        return redirect()->route('smartlinks.bad-aji')
            ->with('error', 'Something went wrong. Please try again.');
    }
}
