<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class ContactType extends Model
{
    protected $table = 'contact_types';
    public $timestamps = false;

    public function contacts()
    {
        $this->hasMany('App\models\Contact', 'type_id');
    }
}
