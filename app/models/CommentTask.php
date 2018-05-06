<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class CommentTask extends Model
{
    protected $table = 'comment_task';
    protected $guarded = []; //all attributes mass assignable

    public function task()
    {
        return $this->belongsTo('App\models\Task');
    }

    public function author()
    {
        return $this->belongsTo('App\User', 'author_id');
    }
}
