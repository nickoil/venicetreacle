<?php
namespace App\Http\Controllers;

use App\Models\BlacklistedEmailAddress;
use App\Models\EmailStatus;
use App\Models\UserLog;
use Illuminate\Http\Request;

class BlacklistController extends Controller
{
    private $route = 'blacklist';
    private $view = 'pages.blacklist';

    protected function query(Request &$request)
    {
        if (!$request->sort_field) $request->sort_field = 'created_at';
        if (!$request->sort_direction) $request->sort_direction = 'desc';

        $sortField = $request->sort_field;
        $sortDirection = $request->sort_direction;

        $query = BlackListedEmailAddress::orderBy($sortField, $sortDirection);

        if ($request->email_address) {
            $query->where('email_address', 'LIKE', "%{$request->email_address}%");
        }

        if ($request->email_status_id) {
            $query->where('email_status_id', $request->email_status_id);
        }

        return $query;
    }

    public function index(Request $request)
    {
        $request = search_persister($request);

        $query = $this->query($request);
        $data['route'] = $this->route;
        $data['listItems'] = $query->paginate(config('app.pagination')); 
        $data['emailStatuses']  = [6 => 'Bounced', 7 => 'Complaint'];
        if ($request->ajax()) {
            return response()->json([
                'itemsHtml' => '' . view($this->view . '.list', $data),
                'paginationHtml' => '' . $data['listItems']->appends(request()->except('page'))->links(),
            ]);
        }

        UserLog::logMessage('viewed the blacklist');

        return view($this->view . '.index', $data);
    }

    /**
     * Export the list 
     *
     * @return void
     */
    public function export(Request $request) {

        $fields = [
            'BlacklistId' => 'id',
            'EmailAddress' => 'email_address',
            'BlacklistedTime' => 'excluded_time',
            'Reason' => 'email_status->title',
        ];

        $request = search_persister($request,  md5('/' . $this->route));
        $query = $this->query($request);

        UserLog::logMessage('exported the blacklist');

        exportCSV($this->route . '_export', $fields, $query);
    }

 
}