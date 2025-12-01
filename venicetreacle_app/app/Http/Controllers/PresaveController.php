<?php

namespace App\Http\Controllers;

use App\Models\Presave;
use App\Models\UserLog;
use Illuminate\Http\Request;
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

    
}