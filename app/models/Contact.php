<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = 'contacts';
    protected $guarded = [];
    public $timestamps = false;
    public function type()
    {
        return $this->belongsTo('App\models\ContactType', 'type_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
