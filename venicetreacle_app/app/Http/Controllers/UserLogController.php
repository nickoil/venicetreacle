<?php
namespace App\Http\Controllers;

use App\Models\UserLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UserLogController extends Controller
{
    private $route = 'user-logs';
    private $view = 'pages.user-logs';

    protected function query(Request &$request)
    {
        if (!$request->sort_field) $request->sort_field = 'created_at';
        if (!$request->sort_direction) $request->sort_direction = 'desc';

        $sortField = $request->sort_field;
        $sortDirection = $request->sort_direction;

        $query = UserLog::orderBy($sortField, $sortDirection);

        if ($request->user) {

            if (strtolower($request->user) == 'unknown') {
                $query->where('user_id', -1);
            } else {
                $query->whereHas('user', function ($query) use ($request) {
                    $query->where('email', 'LIKE', "%{$request->user}%");
                });
            }
        }

        if($request->date_from) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if($request->date_to) {
            $query->where('created_at', '<', Carbon::parse($request->date_to)->addDays(1));
        }

        if ($request->message) {
            $query->where('message', 'LIKE', "%{$request->message}%");
        }

        return $query;
    }

    public function index(Request $request)
    {
        $request = search_persister($request);

        $query = $this->query($request);
        $data['route'] = $this->route;
        $data['listItems'] = $query->paginate(config('app.pagination')); 
        if ($request->ajax()) {
            return response()->json([
                'itemsHtml' => '' . view($this->view . '.list', $data),
                'paginationHtml' => '' . $data['listItems']->appends(request()->except('page'))->links(),
            ]);
        }

        UserLog::logMessage('viewed the user log');

        return view($this->view . '.index', $data);
    }

    /**
     * Export the list 
     *
     * @return void
     */
    public function export(Request $request) {

        $fields = [
            'LogId' => 'id',
            'UserId' => 'user_id',
            'EmailAddress' => 'user->email_address',
            'LogTime' => 'created_at',
            'Message' => 'message',
        ];

        $request = search_persister($request,  md5('/' . $this->route));
        $query = $this->query($request);

        UserLog::logMessage('exported the user log');

        exportCSV($this->route . '_export', $fields, $query);
    }

 
}