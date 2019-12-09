<?php

namespace App;
use Cliente;


use Illuminate\Database\Eloquent\Model;

class Telefono extends Model
{
  public function cliente(){
    return $this->belongsTo('App\Cliente');
  }
}
