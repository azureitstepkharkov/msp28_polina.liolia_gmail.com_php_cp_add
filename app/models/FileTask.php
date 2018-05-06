<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class FileTask extends Model
{
    protected $table = 'file_task';

    protected $fillable = [
        'task_id',
        'user_id',
        'path'
    ];

    public function task()
    {
        return $this->belongsTo('App\models\Task');
    }

    public function author()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
