<?php
namespace App\Http\Controllers;

use App\Models\UserLog;
use App\Models\CallbackLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CallbackLogController extends Controller
{
    private $route = 'callback-logs';
    private $view = 'pages.callback-logs';

    protected function query(Request &$request)
    {
        if (!$request->sort_field) $request->sort_field = 'created_at';
        if (!$request->sort_direction) $request->sort_direction = 'desc';

        $sortField = $request->sort_field;
        $sortDirection = $request->sort_direction;

        $query = CallbackLog::orderBy($sortField, $sortDirection);

        if ($request->service) {
            $query->where('service', 'LIKE', "%{$request->service}%");
        }

        if ($request->state) {
            $query->where('state', 'LIKE', "%{$request->state}%");
        }

        if($request->date_from) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if($request->date_to) {
            $query->where('created_at', '<', Carbon::parse($request->date_to)->addDays(1));
        }

        if($request->success || $request->success === '0') {
            $query->where('success', $request->success);
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
        $data['successOptions']  = ['0' => 'No', '1' => 'Yes'];
        if ($request->ajax()) {
            return response()->json([
                'itemsHtml' => '' . view($this->view . '.list', $data),
                'paginationHtml' => '' . $data['listItems']->appends(request()->except('page'))->links(),
            ]);
        }

        UserLog::logMessage('viewed the callback log');

        return view($this->view . '.index', $data);
    }

    /**
     * Export the list 
     *
     * @return void
     */
    public function export(Request $request) {

        $fields = [
            'ID' => 'id',
            'Service' => 'service',
            'State' => 'state',
            'Code' => 'code',
            'Error' => 'error',
            //'RequestData' => 'request_data',
            //'ResponseData' => 'response_data',
            'Success' => 'success',
            'Status' => 'status',
            'Body' => 'body',
            'IPAddress' => 'ip_address',
            'UserAgent' => 'user_agent',
            'Message' => 'message',
            'CreatedAt' => 'created_at',
            'UpdatedAt' => 'updated_at',
        ];

        $request = search_persister($request,  md5('/' . $this->route));
        $query = $this->query($request);

        exportCSV($this->route . '_export', $fields, $query);

        UserLog::logMessage('exported the callback log');
    }

 
}