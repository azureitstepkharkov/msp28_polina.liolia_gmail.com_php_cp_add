<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\models\Project;
use App\models\Task;
use App\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::check())// The user is logged in
        {
            $user = Auth::user();
            if($user->hasRole('Admin'))
            {
                $projects = Project::all();
                $tasks = Task::all()->sortBy('end');
            }
            elseif($user->hasRole('ProjectMan'))
            {
                $projects = $user->pm_projects;
                $tasks = $user->pm_tasks->sortBy('end');
            }
            elseif ($user->hasRole('Client'))
            {
                $projects = $user->clients_projects;
                $tasks = $user->clients_tasks->sortBy('end');
            }
            elseif ($user->hasRole('Programmer'))
            {
                $tasks = $user->tasks->sortBy('end');
                $projects = [];
                foreach ($tasks as $task) {
                    $projects[] = $task->project;
                }
                $projects = collect(array_unique($projects));
            }
            else
            {
                $projects = Project::all();
                $tasks = Task::all()->sortBy('end');
            }
        }
        $date = date("d-m-Y");
        return view('home', compact('projects', 'tasks', 'date'));
    }

    public function getJsonData()
    {
        if (Auth::check())// The user is logged in
        {
            $user = Auth::user();
            return User::with(['clients_projects', 'pm_projects', 'clients_projects.project_manager', 'pm_projects.project_manager'])
                ->where('id', 'like', $user->id)
                ->get()->toJson(JSON_PRETTY_PRINT);
        }
        return 'Error: you are not authorized. Log in first.';

    }
}
