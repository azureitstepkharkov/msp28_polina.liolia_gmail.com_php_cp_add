<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class CommentUser extends Model
{
    protected $table = 'comment_user';
    protected $guarded = []; //all attributes mass assignable

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function author()
    {
        return $this->belongsTo('App\User', 'author_id');
    }
}
