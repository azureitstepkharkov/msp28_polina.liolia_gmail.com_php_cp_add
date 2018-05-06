<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\models\Project;
use App\User;
use App\models\Technology;

class ProjectController extends Controller
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
                $projects = Project::all();
            }
            elseif($user->hasRole('ProjectMan'))
            {
                $projects = $user->pm_projects;
            }
            elseif ($user->hasRole('Client'))
            {
                $projects = $user->clients_projects;
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
            }
        }
        return view('projects.index', compact('projects'));
    }

    public  function  indexApi()
    {
        $projects = Project::all();
        return response()->json($projects, 200);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = collect();
        foreach (User::all() as $user)
        {
            if($user->hasRole('Client'))
                $clients->push($user);
        }
        $clients = $clients->pluck('name', 'id');
        $PMs = collect();
        foreach (User::all() as $user)
        {
            if($user->hasRole('ProjectMan'))
                $PMs->push($user);
        }
        $PMs = $PMs->pluck('name', 'id');
        $statuses = [
            'in_work' => 'in_work',
            'completed' => 'completed',
            'canceled' => 'canceled'];
        $technologies = Technology::all();
        return view('projects.create', compact('clients', 'statuses', 'technologies', 'PMs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validate fields
        $this->validate($request, [
                'name'=>'required',
                'client_id' =>'required',
                'status' =>'required',
            ]
        );

        $proj = Project::create($request->only('name', 'client_id', 'description', 'status', 'project_manager_id'));
        $proj->save();
        $technology_ids = $request['technologies'];

        foreach ($technology_ids as $technology_id)
        {
            $tech = Technology::findOrFail($technology_id);
           // $task->technologies()->attach($technology);
           $proj->technologies()->attach($tech);
        }
        flash('Project '. $proj->name.' was added!');
        return redirect()->route('projects.show', [$proj->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
{
    $project = Project::findOrFail($id);
    $statuses = [
        'in_work' => 'in_work',
        'completed' => 'completed',
        'canceled' => 'canceled'];
    $clients = collect();
    foreach (User::all() as $user)
    {
        if($user->hasRole('Client'))
            $clients->push($user);
    }
    $clients = $clients->pluck('name', 'id');
    $technologies = $project->technologies;
    $other_technologies = Technology::all();
    $other_technologies = $other_technologies->diff($technologies)->pluck('name', 'id');
    $tasks = $project->tasks;
    return view('projects.show', compact('project', 'statuses', 'clients', 'technologies', 'other_technologies', 'tasks'));
}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $project = Project::findOrFail($id);
        $clients = collect();
        foreach (User::all() as $user)
        {
            if($user->hasRole('Client'))
                $clients->push($user);
        }
        $PMs = collect();
        foreach (User::all() as $user)
        {
            if($user->hasRole('ProjectMan'))
                $PMs->push($user);
        }
        $PMs = $PMs->pluck('name', 'id');
        $clients = $clients->pluck('name', 'id');
        $statuses = [
            'in_work' => 'in_work',
            'completed' => 'completed',
            'canceled' => 'canceled'];
        $technologies = Technology::all();
        return view('projects.edit', compact('project','clients', 'statuses', 'technologies', 'PMs'));
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
        $project = Project::findOrFail($id);
        $this->validate($request, [
                'name'=>'required',
                'client_id' =>'required',
                'project_manager_id' =>'required',
                'status' =>'required',
            ]
        );
        $project->name = $request['name'];
        $project->description = $request['description'];
        $project->client_id = $request['client_id'];
        $project->status = $request['status'];
        $project->project_manager_id = $request['project_manager_id'];
        $all_technologies = Technology::all();
        foreach ($all_technologies as $technology)
        {
            $project->technologies()->detach($technology);
        }

        $technology_ids = $request['technologies'];
        foreach ($technology_ids as $technology_id)
        {
            $tech = Technology::findOrFail($technology_id);
            $project->technologies()->attach($tech);
        }
        $project->save();
        flash('Project '. $project->name.' was updated!');
        return redirect()->route('projects.show', [$id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();
        flash('Project ' .$project->name . ' was deleted!');
        return redirect()->route('projects.index');
    }

    public function attach_technology(Request $request)
    {
        $project = Project::findOrFail($request->id);
        $tech = Technology::findOrFail($request->technology);
        $project->technologies()->attach($tech);
        return back();
    }

    public function detach_technology($proj_id, $tech_id)
    {
        $project = Project::findOrFail($proj_id);
        $tech = Technology::findOrFail($tech_id);
        $project->technologies()->detach($tech);
        return back();
    }

    public function change_status(Request $request, $id)
    {
        $project = Project::findOrFail($id);
        $this->validate($request, [
                'status'=>'required'
            ]
        );
        $project->status = $request['status'];
        $project->save();
        flash('Project '. $project->name.' status was updated!');
        return back();
    }
}
