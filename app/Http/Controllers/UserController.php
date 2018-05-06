<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Role;
use App\models\Technology;
use Session;
//use function Sodium\add;

class UserController extends Controller {

    public function __construct() {
        $this->middleware(['auth', /*'isAdmin'*/]); //isAdmin middleware lets only users with a //specific permission permission to access these resources
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        //Get all users and pass it to the view
        $users = User::all();
        $filter = $request['filter'];
        switch($filter)
        {
            case 'programmers':
            {
                $programmer_role = Role::where('name', 'Programmer')->firstOrFail();
                $users = $users->filter(function ($user) use ($programmer_role){

                    return $user->roles->contains($programmer_role);
                });
            }
            break;
            case 'clients':
            {
                $client_role = Role::where('name', 'Client')->firstOrFail();
                $users = $users->filter(function ($user) use ($client_role){

                    return $user->roles->contains($client_role);
                });
            }
            break;
            case 'employees':
            {
                $programmer_role = Role::where('name', 'Programmer')->firstOrFail();
                $team_leader_role = Role::where('name', 'TeamLeader')->firstOrFail();
                $pm_role = Role::where('name', 'ProjectMan')->firstOrFail();
                $users = $users->filter(function ($user) use ($programmer_role, $team_leader_role, $pm_role ){

                    return $user->roles->contains($programmer_role) || $user->roles->contains($team_leader_role) ||
                        $user->roles->contains($pm_role);
                });
            }
            break;
            default: $users = User::all();
        }
        return view('users.index')->with('users', $users);
    }

    public function indexApi()
    {
        $users = User::all();
        return response()->json($users, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //Get all roles and pass it to the view
        $roles = Role::get();
        $technologies = Technology::all();
        return view('users.create', compact('roles', 'technologies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //Validate name, email and password fields
        $this->validate($request, [
            'name'=>'required|max:120',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6|confirmed'
        ]);

        $user = User::create($request->only('email', 'name', 'password')); //Retrieving only the email and password data

        $roles = $request['roles']; //Retrieving the roles field
        $technologies = $request['technologies'];
        //Checking if a role was selected
        if (isset($roles)) {

            foreach ($roles as $role) {
                $role_r = Role::where('id', '=', $role)->firstOrFail();
                $user->attachRole($role_r); //Assigning role to user
            }
        }

        if (isset($technologies)) {
            $user->technologies()->sync($technologies);  //If one or more role is selected associate user to roles
        }
        //Redirect to the users.index view and display message
        flash('User '. $user->name. 'was added!');
        return redirect()->route('users.show', [$user->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $user = User::findOrFail($id);
        $roles = Role::all();
        $technologies = $user->technologies;
        $all_technologies = Technology::all();
        //getting only technologies, that are not currently attached to user,
        //passing it into view (in select)
        $technologies_available = $all_technologies->diff($technologies)->pluck('name', 'id');
        return view ('users.show', compact('user', 'roles', 'technologies_available'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $user = User::findOrFail($id); //Get user with specified id
        $roles = Role::get(); //Get all roles
        $technologies = Technology::get(); //Get all technologies

        return view('users.edit', compact('user', 'roles', 'technologies')); //pass user and roles data to view

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $user = User::findOrFail($id); //Get role specified by id

        //Validate name, email and password fields
        $this->validate($request, [
            'name'=>'required|max:120',
            'email'=>'required|email|unique:users,email,'.$id,
            'password'=>'required|min:6|confirmed'
        ]);
        $input = $request->only(['name', 'email', 'password']); //Retreive the name, email and password fields
        $roles = $request['roles']; //Retreive all roles
        $technologies = $request['technologies'];
        $user->fill($input)->save();

        if (isset($roles)) {
            $user->roles()->sync($roles);  //If one or more role is selected associate user to roles
        }
        else {
            $user->roles()->detach(); //If no role is selected remove exisiting role associated to a user
        }
        if (isset($technologies)) {
            $user->technologies()->sync($technologies);  //If one or more role is selected associate user to roles
        }
        else {
            $user->technologies()->detach(); //If no role is selected remove exisiting role associated to a user
        }

        flash('User '. $user->name. 'was updated!');
        return redirect()->route('users.show', [$id]);
    }

    public function updateRoles(Request $request, $id) {
        $user = User::findOrFail($id); //Get role specified by id

        $roles = $request['roles']; //Retreive all roles
        if (isset($roles)) {
            $user->roles()->sync($roles);  //If one or more role is selected associate user to roles
        }
        else {
            $user->roles()->detach(); //If no role is selected remove exisiting role associated to a user
        }

        flash('User '. $user->name. ' roles were updated!');
//        return redirect()->route('users.index', ['filter' => 'all']);
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //Find a user with a given id and delete
        $user = User::findOrFail($id);
        $user->delete();

        flash('User '. $user->name. ' was deleted!');
        return redirect()->route('users.index', ['filter' => 'all']);
    }

    public function inactive($id) {
        //Find a user with a given id and delete
        $user = User::findOrFail($id);
        if($user->status === 'inactive'){
            $user->status = 'active';
            flash('User '. $user->name. ' was active!');
        }
        else{
            $user->status = 'inactive';
            flash('User '. $user->name. ' was inactive!');
        }
        $user->save();
//        return redirect()->route('users.index', ['filter' => 'all']);
        return redirect()->back();
    }

    public function showProgrammers() {
        $usersRoles = User::all();
        $users = [];
        for($i = 0; $i < count($usersRoles); $i++){
            if($usersRoles[$i]->roles()->pluck('name')->implode(' ') === 'Programmer'){
                array_push($users, $usersRoles[$i]);
            }
        }

        return view('users.index')->with('users', $users);
    }

    public function showEmployees() {
        $usersRoles = User::all();
        $users = [];
        for($i = 0; $i < count($usersRoles); $i++){
            if($usersRoles[$i]->roles()->pluck('name')->implode(' ') === 'TeamLeader' ||
                $usersRoles[$i]->roles()->pluck('name')->implode(' ') === 'Programmer' ||
                $usersRoles[$i]->roles()->pluck('name')->implode(' ') === 'ProjectMan'){
                array_push($users, $usersRoles[$i]);
            }
        }

        return view('users.index')->with('users', $users);
    }

    public function showClients() {
        if(Auth::user()->hasRole('Admin') || Auth::user()->hasRole('ProjectMan') || Auth::user()->hasRole('TeamLeader')){
            $usersRoles = User::all();
            $users = [];
            for($i = 0; $i < count($usersRoles); $i++){
                if($usersRoles[$i]->roles()->pluck('name')->implode(' ') === 'Client'){
                    array_push($users, $usersRoles[$i]);
                }
            }
        }
        else{
            $users = User::showUsers('Client');
        }

        return view('users.index')->with('users', $users);
    }

    public function attach_technology(Request $request)
    {
        $user = User::findOrFail($request->id);
        $technology = Technology::findOrFail($request->technology);
        $user->technologies()->attach($technology);
        return redirect("users/$user->id");
    }

    public function detach_technology($user_id, $tech_id)
    {
        $user = User::findOrFail($user_id);
        $technology = Technology::findOrFail($tech_id);
        $user->technologies()->detach($technology);
        return redirect("users/$user->id");
    }





}