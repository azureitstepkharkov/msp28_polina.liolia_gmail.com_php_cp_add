<?php
namespace App;
use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
    protected $fillable = [
        'name',
    ];

    public function roles()
    {
        return $this->belongsToMany('App\Role');
    }
}