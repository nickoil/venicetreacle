<?php
namespace App\Http\Controllers;

use App\Models\Email;
use App\Models\EmailStatus;
use App\Models\UserLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EmailController extends Controller
{
    private $route = 'emails';
    private $view = 'pages.emails';

    protected function query(Request &$request)
    {
        if (!$request->sort_field) $request->sort_field = 'created_at';
        if (!$request->sort_direction) $request->sort_direction = 'desc';

        $sortField = $request->sort_field;
        $sortDirection = $request->sort_direction;

        $query = Email::orderBy($sortField, $sortDirection);

        if ($request->email_address) {
            $query->where('email_address', 'LIKE', "%{$request->email_address}%");
        }

        if ($request->body) {
            $query->where('body', 'LIKE', "%{$request->body}%");
        }

        if ($request->message_type) {
            $query->where('message_type', $request->message_type);
        }

        if($request->sent_date_from) {
            $query->where('sent_time', '>=', $request->sent_date_from);
        }

        if($request->sent_date_to) {
            $query->where('sent_time', '<', Carbon::parse($request->sent_date_to)->addDays(1));
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
        $data['emailStatuses']  = EmailStatus::pluck('title', 'id');
        $data['messageTypes']  = Email::select('message_type')->distinct()->pluck('message_type', 'message_type');
        if ($request->ajax()) {
            return response()->json([
                'itemsHtml' => '' . view($this->view . '.list', $data),
                'paginationHtml' => '' . $data['listItems']->appends(request()->except('page'))->links(),
            ]);
        }

        UserLog::logMessage('viewed the list of emails');

        return view($this->view . '.index', $data);
    }

    /**
     * Export the list 
     *
     * @return void
     */
    public function export(Request $request) {

        $fields = [
            'EmailId' => 'id',
            'EmailAddress' => 'email_address',
            'Subject' => 'subject',
            'Body' => 'body',
            'Sender' => 'sender',
            // 'QueuedTime' => 'queued_time',
            'SentTime' => 'sent_time',
            'CompleteTime' => 'complete_time',
            'Status' => 'email_status->title',
            'Notes' => 'notes',
            'Type' => 'message_type',
        ];

        $request = search_persister($request,  md5('/' . $this->route));
        $query = $this->query($request);

        UserLog::logMessage('exported the list of emails');

        exportCSV($this->route . '_export', $fields, $query);
    }

    public function show($id)
    {
        $item =Email::findOrFail($id);
        $data['route'] = $this->route;
        $data['email'] = $item;

        UserLog::logMessage('viewed email ' . $item->id);

        return view($this->view . '.show', $data);
    }

}