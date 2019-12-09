<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    //relacion uno a muchos
    public function users()
    {
        return $this->hasMany('App\User');
    }
}
