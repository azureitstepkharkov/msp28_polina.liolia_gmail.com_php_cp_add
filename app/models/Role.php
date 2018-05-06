<?php
namespace App;
use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    use \Traits\EntrustRole;
    protected $fillable = [
        'name',
    ];

    public function permissions()
    {
        return $this->belongsToMany('App\Permission');
    }
}