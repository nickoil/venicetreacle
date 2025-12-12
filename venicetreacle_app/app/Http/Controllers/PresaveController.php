<?php

namespace App\Http\Controllers;

use App\Models\Presave;
use App\Models\CallbackLog;
use App\Models\UserLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Password;

class PresaveController extends Controller
{
    private $route = 'presaves';
    private $view = 'pages.presaves';

    /**
     * @param Request $request
     * @return mixed
     */
    protected function query(Request &$request) {

        // sort
        $request->sort_field = $request->sort_field ?? 'created_at';
        $request->sort_direction = $request->sort_direction ?? 'desc';

        $sortField = $request->sort_field;
        $sortDirection = $request->sort_direction;

        $query = Presave::orderBy($sortField, $sortDirection);

        // search
        if($request->display_name) {
            $query->where('display_name', 'LIKE', "%$request->display_name%");
        }

        if($request->email) {
            $query->where('email', 'LIKE', "%$request->email%");
        }

        if($request->track_id) {
            $query->where('track_id', $request->track_id);
        }

        if($request->country ) {
            $query->where('country', $request->country);
        }

        if($request->date_from) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if($request->date_to) {
            $query->where('created_at', '<', Carbon::parse($request->date_to)->addDays(1));
        }

        return $query;
    }


    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request = search_persister($request);

        $query = $this->query($request);
        $data['route'] = $this->route;
        $data['listItems'] = $query->paginate(config('app.pagination'));
        $data['trackOptions']  = Presave::pluck('track_id', 'track_id')->unique()->sort();    
        $data['countryOptions']  = Presave::pluck('country', 'country')->unique()->sort();

        if ($request->ajax()) {

            return response()->json([
                'itemsHtml' => '' . view($this->view . '.list', $data),
                'paginationHtml' => '' . $data['listItems']->appends(request()->except('page'))->links(),
            ]);
        }

        UserLog::logMessage('viewed the list of presaves');

        return view($this->view . '.index', $data);
    }

    /**
     * Export the list of users.
     *
     * @return void
     */
    public function export(Request $request) {

        $fields = [
            'ID' => 'id',
            'SpotifyUserID' => 'spotify_user_id',
            'DisplayName' => 'display_name',
            'Email' => 'email',
            'Country' => 'country',
            'Product' => 'product',
            'TrackID' => 'track_id',
            'State' => 'state',
            'CreatedAt' => 'created_at',
            'UpdatedAt' => 'updated_at',
        ];

        $request = search_persister($request,  md5('/' . $this->route));
        $query = $this->query($request);

        UserLog::logMessage('exported the list of users');

        exportCSV($this->route . '_export', $fields, $query);
    }

    /**
     * Save a track to all users' Spotify libraries using their refresh tokens
     *
     * @param string $trackUri Spotify track URI (e.g., spotify:track:6rqhFgbbKwnb9MLmUQDhG6)
     * @return array Results summary
     */
    public function saveTrackToAllUsers(Request $request, string $trackUri)
    {
        $success = true;
        $message = '';
        $successTotal = 0;

        // Extract track ID from URI if needed
        // Split trackUri of form "trackId:spotifyTrackId"
        [$trackId, $spotifyTrackId] = explode(':', $trackUri, 2);

        $presaves = Presave::whereNotNull('refresh_token')
            ->where('track_id', $trackId)
            ->whereNull('saved_at')
            ->get();

            

        foreach ($presaves as $presave) {
            try {

                $success = true;
                $message = '';

                //dd($presave->refresh_token);

                // Exchange refresh token for new access token
                $tokenResponse = Http::asForm()->withBasicAuth(
                    config('services.spotify.client_id'),
                    config('services.spotify.client_secret')
                )->post('https://accounts.spotify.com/api/token', [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $presave->refresh_token,
                ]);

                $status = $tokenResponse->status();
                $body = $tokenResponse->body();

                if ($tokenResponse->failed()) {
                    $success = false;
                    $message = 'Failed to exchange code for token';                   
                }

                if($success) {
                    $tokens = $tokenResponse->json();
                    $accessToken = $tokens['access_token'];

                    // Save track to user's library
                    $saveResponse = Http::withToken($accessToken)
                        ->put('https://api.spotify.com/v1/me/tracks', [
                            'ids' => [$spotifyTrackId]
                        ]);

                    $status = $saveResponse->status();
                    $body = $saveResponse->body();

                    if ($saveResponse->failed()) {
                        $success = false;
                        $message = 'Failed to save track';
                    }
                }

                if ($success) {
                    $successTotal++;

                    $presave->update([
                        'saved_at' => now(),
                    ]);
                }
            } 
            catch (\Exception $e) {
                $success = false; 
                $message = "Failed with exception: " . $e->getMessage();    
            }

            CallbackLog::create([
                'service' => 'spotify-save-track',
                'request_data' => $request->all(),
                'success' => $success,
                'status' => $status,
                'body' => $body,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'message' => $message,
            ]);

        }

        $failureTotal = $presaves->count() - $successTotal;
        $logMessage = "Saved track {$trackUri} to {$successTotal} users' Spotify libraries. There were {$failureTotal} failures.";
        UserLog::logMessage($logMessage);

        return redirect()->route($this->route . '.index')
            ->with('success', $logMessage);
        
    }

    /**
     * Route handler to trigger saving track to all users
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveTrackToAll(Request $request)
    {
        $request->validate([
            'track_uri' => 'required|string'
        ]);

        $results = $this->saveTrackToAllUsers($request, $request->track_uri);
        return $results;
    }
    
}