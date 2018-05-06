<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'projects';
    protected $guarded = []; //all attributes mass assignable
    public function client()
    {
        return $this->belongsTo('App\User', 'client_id');
    }

    public function project_manager()
    {
        return $this->belongsTo('App\User', 'project_manager_id');
    }

    public function tasks()
    {
        return $this->hasMany('App\models\Task');
    }

    public function technologies()
    {
        return $this->belongsToMany('App\models\Technology');
    }

    public function scopeProjectsOfProgrammer($query, $user_id)
    {
        return $query
            ->leftjoin('tasks', 'projects.id', '=', 'tasks.project_id')
            ->leftjoin('task_user', 'tasks.id', '=', 'task_user.task_id')
            ->leftjoin('users', 'task_user.user_id', '=', 'users.id')
            ->where('users.id', '=', $user_id);
    }
}
