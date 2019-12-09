<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    //Controlador de Producto
    public function proveedore(){
       return $this->belongsTo('App\Proveedore');
      }
    public function empresa(){
         return $this->belongsTo('App\Empresa');
    }
    public function servicios()
   {
       return $this->belongsToMany('App\Servicio')->withPivot('id', 'cantidad');
   }
   public function divisa(){
       return $this->belongsTo(Divisa::class);
   }
}
