<?php

namespace App\Http\Controllers;

use App\models\Technology;
use Illuminate\Http\Request;
use App\models\Task;
use App\models\Project;
use Illuminate\Support\Facades\Auth;
use App\User;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
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
                $tasks = Task::all()->sortBy('end');
            }
            elseif($user->hasRole('ProjectMan'))
            {
                $tasks = $user->pm_tasks->sortBy('end');
            }
            elseif ($user->hasRole('Client'))
            {
                $tasks = $user->clients_tasks->sortBy('end');
            }
            elseif ($user->hasRole('Programmer'))
            {
                $tasks = $user->tasks->sortBy('end');
            }
            else
            {
                $tasks = Task::all()->sortBy('end');
            }
        }
        $date = date("d-m-Y");
        return view('tasks.index', compact('tasks', 'date'));
    }

    public  function  indexApi()
    {
        $tasks = Task::all()->sortBy('end');
        return response()->json($tasks, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $users = User::usersForTasks();
        $projects = null;
        $project_id = null;
        if (isset($request->project_id))
        {
           $project_id = $request->project_id;
        }
        else
        {
            $projects = Project::all()->pluck('name', 'id');//Get all projects
        }
        $statuses = ['new' => 'new',
            'in_progress' => 'in_progress',
            'completed' => 'completed',
            'canceled' => 'canceled'];
        return view('tasks.create', compact('project_id', 'projects', 'statuses', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validate name and project_id field
        $this->validate($request, [
                'name'=>'required|max:150',
                'project_id' =>'required',
            ]
        );

        $task = Task::create($request->only('name', 'project_id', 'description', 'start', 'end', 'status'));
            $task->save();
            flash('Task '. $task->name.' was added!');
            return redirect()->route('tasks.show', [$task->id]);
//        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::findOrFail($id);
        $statuses = ['new' => 'new',
            'in_progress' => 'in_progress',
            'completed' => 'completed',
            'canceled' => 'canceled'];
        $technologies = $task->technologies;
        $project_technologies = $task->project->technologies;
        //getting only technologies, that are not currently presented in this task,
        //passing it into view (in select)
        $project_technologies = $project_technologies->diff($technologies)->pluck('name', 'id');
        $stuff = $task->users;
        $stuff_available =$this->stuff_available($task);
        //getting only users, that are not currently involved in this task,
        //passing them into view (in select)
        $stuff_available = $stuff_available->diff($stuff->pluck('name', 'id'));
        $comments = $task->comments;
        $files = $task->files;

        return view('tasks.show', compact('task', 'statuses', 'technologies', 'project_technologies', 'stuff', 'stuff_available', 'comments', 'files'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        $task = Task::findOrFail($id);
        $users = User::usersForTasks();
        $projects = null;
        $project_id = null;
        if (isset($request->project_id))
        {
            $project_id = $request->project_id;
        }
        else
        {
            $projects = Project::all()->pluck('name', 'id');//Get all projects
        }
        $statuses = ['new' => 'new',
            'in_progress' => 'in_progress',
            'completed' => 'completed',
            'canceled' => 'canceled'];
        return view('tasks.edit', compact('task', 'project_id', 'projects', 'statuses', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $this->validate($request, [
                'name'=>'required|max:150',
                'project_id' =>'required',
            ]
        );

       // $task->fill($request)->save();

        $task->name = $request['name'];
        $task->project_id = $request['project_id'];
        $task->description =  $request['description'];
        $task->start =  $request['start'];
        $task->end = $request['end'];
        $task->status = $request['status'];
        $users = $request['users'];
        $task->save();
        if (isset($users)) {
            $task->users()->sync($users);  //If one or more role is selected associate user to roles
        }
        else {
            $task->users()->detach(); //If no role is selected remove exisiting role associated to a user
        }

//        if(Auth::user()->hasRole('Client')){
//            flash('Task '. $task->name.' was updated!');
//            return redirect()->route('tasks.showTask', Auth::user()->id);
//        }
//        else{
            flash('Task '. $task->name.' was updated!');
            return redirect()->route('tasks.show', [$task->id]);
//        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        flash('Task ' . $task->name . ' was deleted!');
        return redirect()->route('tasks.index');
    }

    public function attach_technology(Request $request)
    {
        $task = Task::findOrFail($request->id);
        $technology = Technology::findOrFail($request->technology);
        $task->technologies()->attach($technology);
        return redirect("tasks/$task->id");
    }

    public function detach_technology($task_id, $tech_id)
    {
        $task = Task::findOrFail($task_id);
        $technology = Technology::findOrFail($tech_id);
        $task->technologies()->detach($technology);
        return redirect("tasks/$task_id");
    }

    public function attach_user(Request $request)
    {
        $task = Task::findOrFail($request->id);
        $this->validate($request, [
                'stuff'=>'required'
            ]
        );
        $user = User::findOrFail($request->stuff);
        $task->users()->attach($user);
        return redirect("tasks/$request->id");
    }

    public function detach_user($task_id, $user_id)
    {
        $task = Task::findOrFail($task_id);
        $user = User::findOrFail($user_id);
        $task->users()->detach($user);
        return redirect("tasks/$task_id");
    }

    //returns collection with ids and names of all programmers, that have skills, needed in this task
    private function stuff_available($task)
    {
        $technologies = $task->technologies;
        $programmers = collect();
        foreach($technologies as $tech)
            $programmers = $programmers->union($tech->programmers);
        return $programmers->pluck('name', 'id');
    }

    public function change_status(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $this->validate($request, [
                'status'=>'required'
            ]
        );
        $task->status = $request['status'];
        $task->save();
        flash('Task '. $task->name.' status was updated!');
        return back();
    }
}
