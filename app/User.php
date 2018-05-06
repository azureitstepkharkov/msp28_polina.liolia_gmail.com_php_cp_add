<?php

namespace App;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable;
    use EntrustUserTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public function technologies()
    {
        return $this->belongsToMany('App\models\Technology');
    }

    public function tasks()
    {
        return $this->belongsToMany('App\models\Task');
    }

    public function comments_for_tasks()
    {
        return $this->hasMany('App\models\CommentTask', 'author_id');
    }

    public function comments_for_users()
    {
        return $this->hasMany('App\models\CommentUser', 'author_id');
    }

    public function comments()
    {
        return $this->hasMany('App\models\CommentUser');
    }

    public function contacts()
    {
        return $this->hasMany('App\models\Contact');
    }

    public function files()
    {
        return $this->hasMany('App\models\FileTask');
    }

    public function roles()
    {
        return $this->belongsToMany('App\Role');
    }

    public function clients_projects()
    {
        return $this->hasMany('App\models\Project', 'client_id');
    }

    public function clients_tasks()
    {
        return $this->hasManyThrough('App\models\Task', 'App\models\Project', 'client_id');
    }

    public function pm_projects()
    {
        return $this->hasMany('App\models\Project', 'project_manager_id');
    }

    public function pm_tasks()
    {
        return $this->hasManyThrough('App\models\Task', 'App\models\Project', 'project_manager_id');
    }

    public static function usersForTasks(){
        $users = DB::table('users')
            ->leftjoin('role_user', 'users.id', '=', 'role_user.user_id')
            ->leftjoin('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.id', 'users.name', 'users.email', 'users.created_at', 'users.status', 'roles.name as roleName')
            ->where('roles.name', '=', 'Programmer')
            ->orWhere('roles.name', '=', 'TeamLeader')
            ->get();
        return $users;
    }
//
//    public static function projectManagers()
//    {
//        $PMs = DB::table('users')
//            ->leftjoin('role_user', 'users.id', '=', 'role_user.user_id')
//            ->leftjoin('roles', 'roles.id', '=', 'role_user.role_id')
//            ->select('users.id', 'users.name', 'users.email', 'users.created_at', 'users.status', 'roles.name as roleName')
//            ->where('roles.name', '=', 'ProjectMan')
//            ->get();
//        return $PMs;
//    }
}
