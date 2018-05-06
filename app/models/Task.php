<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Collective\Html\Eloquent\FormAccessible;
use \Carbon\Carbon;

class Task extends Model
{
    use FormAccessible;
    /**
     * Table, connected with model.
     *
     * @var string
     */
    protected $table = 'tasks';
    /**
     * Defines if timestamps updated_at and created_at have to be processed.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'project_id',
        'start',
        'end',
        'status'
    ];

    public function getStartAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y');
    }

    public function formStartAttribute($value)
    {

        return Carbon::parse($value)->format('Y-m-d');
    }
    public function getEndAttribute($value)
    {

        return Carbon::parse($value)->format('d/m/Y');
    }

    public function formEndAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    //Relations
    public function users()
    {
        return $this->belongsToMany('App\User');
    }


    public function project()
    {
        return $this->belongsTo('App\models\Project');
    }

    public function comments()
    {
        return $this->hasMany('App\models\CommentTask');
    }

    public function files()
    {
        return $this->hasMany('App\models\FileTask');
    }

    public function technologies()
    {
        return $this->belongsToMany('App\models\Technology');
    }

    public function attachUser($user)
    {
        if(is_object($user)) {
            $user = $user->getKey();
        }

        if(is_array($user)) {
            $user = $user['id'];
        }

        $this->users()->attach($user);
    }

    public static function showTaskForUsers($id){
        $users = DB::table('tasks')
            ->leftjoin('task_user', 'tasks.id', '=', 'task_user.id_task')
            ->leftjoin('users', 'users.id', '=', 'task_user.id_user')
            ->select('tasks.*')
            ->where('users.id', '=', $id)
            ->get();
        return $users;
    }
}
