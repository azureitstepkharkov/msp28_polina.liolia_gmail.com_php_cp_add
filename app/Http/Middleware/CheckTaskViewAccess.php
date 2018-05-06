<?php

namespace App\Http\Middleware;

use Closure;
use App\models\Task;
use Illuminate\Support\Facades\Auth;

class CheckTaskViewAccess
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
        $accessibility = false; //indicates if user can view requested task
        if (Auth::check()) //if user is logged in
        {
            $task_id = $request->route()->parameters()['task'];
            $task = Task::findOrFail($task_id);
            $user = Auth::User();
            if ($user->hasRole('Admin') || $user->hasRole('TeamLeader')) //Admin and TeamLeader can view all tasks
                $accessibility = true;
            else if ($user->hasRole('ProjectMan'))
            {
                $accessibility = $user->pm_tasks->contains(function ($value, $key) use($task_id) {
                    return $value->id == $task_id;
                });  //check if requested task belongs to current PM
            }
            else if($user->hasRole('Client'))
            {
                $accessibility = $user->clients_tasks->contains(function ($value, $key) use($task_id) {
                    return $value->id == $task_id;
                }); //requested project belongs to current client
            }
            else if($user->hasRole('Programmer'))
            {
                $accessibility = $user->tasks->contains(function ($value, $key) use($task_id) {
                    return $value->id == $task_id;
                }); //requested project belongs to current programmer
            }
        }
        return $accessibility ? $next($request) : abort(403);
    }
}
