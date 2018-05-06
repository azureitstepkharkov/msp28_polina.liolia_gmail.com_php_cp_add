<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Technology extends Model
{
    /**
     * Table, connected with model.
     *
     * @var string
     */
    protected $table = 'technologies';

    protected $fillable = [
        'name',
        'description'
    ];

    public function programmers()
    {
        return $this->belongsToMany('App\User');
    }

    public function projects()
    {
        return $this->belongsToMany('App\models\Project');
    }

    public  function tasks()
    {
        return $this->belongsToMany('App\models\Task');
    }
}