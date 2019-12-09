<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proveedore extends Model
{
  public function empresa(){
     return $this->belongsTo('App\Empresa');
    }
    public function productos()
    {
      return $this->hasMany('App\Producto');
    }
    
}
