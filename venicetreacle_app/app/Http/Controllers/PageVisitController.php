<?php

namespace App\Http\Controllers;

use App\Models\PageVisit;
use App\Models\UserLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class PageVisitController extends Controller
{
    private $route = 'page-visits';
    private $view = 'pages.page-visits';

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

        $query = PageVisit::orderBy($sortField, $sortDirection);

        // search
        if($request->page_name) {
            $query->where('page', 'LIKE', "%$request->page_name%");
        }

        if($request->src) {
            $query->where('src', 'LIKE', "%$request->src%");
        }

        if($request->ref) {
            $query->where('ref', 'LIKE', "%$request->ref%");
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

        if ($request->ajax()) {

            return response()->json([
                'itemsHtml' => '' . view($this->view . '.list', $data),
                'paginationHtml' => '' . $data['listItems']->appends(request()->except('page'))->links(),
            ]);
        }

        UserLog::logMessage('viewed the list of page visits');

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
            'Page' => 'page',
            'Source' => 'src',
            'Reference' => 'ref',
            'IP_Address' => 'ip',
            'User_Agent' => 'user_agent',
            'CreatedAt' => 'created_at',
            'UpdatedAt' => 'updated_at',
        ];

        $request = search_persister($request,  md5('/' . $this->route));
        $query = $this->query($request);

        UserLog::logMessage('exported the list of page visits');

        exportCSV($this->route . '_export', $fields, $query);
    }

    
}