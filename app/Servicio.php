<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
  public function productos()
   {
       return $this->belongsToMany('App\Producto')->withPivot('id', 'cantidad');
   }
   public function clientes()
   {
       return $this->belongsToMany('App\Cliente');
   }
   public function empresa(){
        return $this->belongsTo('App\Empresa');
   }
}
