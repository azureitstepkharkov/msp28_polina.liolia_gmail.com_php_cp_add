<?php

namespace App\Http\Middleware;

use Closure;
use App\models\Project;
use Illuminate\Support\Facades\Auth;

class CheckProjectViewAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $accessibility = false; //indicates if user can view requested project
        if (Auth::check()) //if user is logged in
        {
            $project_id = $request->route()->parameters()['project'];
            $project = Project::findOrFail($project_id);
            $user = Auth::User();
            if ($user->hasRole('Admin') || $user->hasRole('TeamLeader')) //Admin and TeamLeader can view all projects
                $accessibility = true;
            else if ($user->hasRole('ProjectMan') &&
                $project->project_manager->id == $user->id)  //requested project belongs to current PM
                $accessibility = true;
            else if($user->hasRole('Client') &&
                $project->client->id == $user->id) //requested project belongs to current user
                $accessibility = true;
            else if($user->hasRole('Programmer'))
            {
                $programmers_projects = Project::projectsOfProgrammer($user->id)->get();
                //check if requested project includes tasks, bind to current programmer:
                $accessibility = $programmers_projects->contains(function ($value, $key) use($project_id) {
                    return $value->project_id == $project_id;
                });
            }
        }
        return $accessibility ? $next($request) : abort(403);
    }
}
