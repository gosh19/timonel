<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Correo;
use Telefono;

class Cliente extends Model
{
  public function servicios()
     {
         return $this->belongsToMany('App\servicio');
     }
   public function correos(){
      return $this->hasMany('App\Correo');
    }

     public function telefonos(){
        return $this->hasMany('App\Telefono');
      }

      public function empresa(){
         return $this->belongsTo('App\Empresa');
        }
    public function recibos(){
         return $this->hasMany(Recibo::class);
    }
    public function preventas(){
        return $this->hasMany(Preventa::class);
    }

    public function remitos(){
        return $this->hasMany(Remito::class);
    }
    public function ventas(){
      return $this->hasMany(Venta::class);
    }
    public function  facturas(){
      return $this->hasMany(Factura::class);
    }
}
