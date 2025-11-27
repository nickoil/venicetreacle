<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserLog;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class UserController extends Controller
{
    private $route = 'users';
    private $view = 'pages.users';

    /**
     * @param Request $request
     * @return mixed
     */
    protected function query(Request &$request) {

        // sort
        if(!$request->sort_field) $request->sort_field = 'name';
        if(!$request->sort_direction) $request->sort_direction = 'asc';

        $sortField = $request->sort_field;
        $sortDirection = $request->sort_direction;

        $query = User::orderBy($sortField, $sortDirection);

        // search
        if($request->name) {
            $query->where('name', 'LIKE', "%$request->name%");
        }

        if($request->email) {
            $query->where('email', 'LIKE', "%$request->email%");
        }

        if($request->role_id) {
            $query->where('role_id', $request->role_id);
        }

        if($request->suspended || $request->suspended === '0') {
            $query->where('suspended', $request->suspended);
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
        $data['roles']  = Role::pluck('title', 'id');
        $data['suspendedOptions']  = ['0' => 'No', '1' => 'Yes'];

        if ($request->ajax()) {

            return response()->json([
                'itemsHtml' => '' . view($this->view . '.list', $data),
                'paginationHtml' => '' . $data['listItems']->appends(request()->except('page'))->links(),
            ]);
        }

        UserLog::logMessage('viewed the list of users');

        return view($this->view . '.index', $data);
    }

    /**
     * Export the list of users.
     *
     * @return void
     */
    public function export(Request $request) {

        $fields = [
            'UserId' => 'id',
            'Name' => 'name',
            'Email' => 'email',
            'Role' => 'role->title',
            'Suspended' => 'suspended',
        ];

        $request = search_persister($request,  md5('/' . $this->route));
        $query = $this->query($request);

        UserLog::logMessage('exported the list of users');

        exportCSV($this->route . '_export', $fields, $query);
    }

    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['route'] = $this->route;
        $data['roles'] = Role::pluck('title', 'id')->prepend('Please select', -1);
        $data['suspendedOptions']  = ['0' => 'No', '1' => 'Yes'];
        return view($this->view . '.create', $data);
    }

    private function validateData(Request $request, User $user = null) {

        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email'. ($user ? ',' . $user->id : ''),
            'role_id' => 'required|exists:roles,id',
            'suspended' => 'required|in:0,1',
        ], [ // for custom messages
            'role_id.exists' => 'The selected role is invalid.',
        ]);

        return $validatedData;
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $this->validateData($request);
        $validatedData['password'] = str()->random(16); //otherwise get bcrypt error if logging in before setting password
        $user = User::create($validatedData);

        UserLog::logMessage('created user ' . $user->email);

        return redirect()->route($this->route . '.edit', $user->id)
            ->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $data['route'] = $this->route;
        $data['user'] = $user;
        $data['roles'] = Role::pluck('title', 'id')->prepend('Please select', -1);
        $data['suspendedOptions']  = ['0' => 'No', '1' => 'Yes'];

        return view($this->view . '.edit', $data);
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $validatedData = $this->validateData($request, $user);
        $user->update($validatedData);

        UserLog::logMessage('updated user ' . $user->email);

        return redirect()->route($this->route . '.edit', $user->id)
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        // Your code here
    }

    /**
     * Invite a new user to the application.
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function invite(User $user)
    {
        $broker = 'users_invitation';
        $token = Password::broker($broker)->createToken($user);
        $user->sendInvitation($token);

        UserLog::logMessage('invited user . ' . $user->email);

        return redirect()->route($this->route . '.index')
            ->with('success', 'User <b>' . $user->name . '</b> has been invited. Check logs to see if email was recieved.');
    }
}