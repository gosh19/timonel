<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{

    public function tasks(){
      return $this->hasMany('App\Task');
    }
    public function users()
    {
        return $this->hasMany('App\User');
    }
    public function clientes()
    {
      return $this->hasMany('App\Cliente');
    }
    public function recibos(){
        return $this->hasMany(Recibo::class);
    }
    public function oportunidades()
    {
      return $this->hasMany('App\Oportunidade');
    }
    public function proveedores()
    {
      return $this->hasMany('App\Oportunidade');
    }
    public function notifications()
    {
      return $this->hasMany('App\Notification');
    }
    public function productos()
    {
      return $this->hasMany('App\Producto');
    }
    public function servicios()
    {
      return $this->hasMany('App\Servicio');
    }
    public function comentarios(){
      return $this->hasMany('App\Comentario');
    }
    public function remitos(){
      return $this->hasMany(Remito::class);
    }
    public function divisas(){
      return $this->hasMany(Divisa::class);
    }
    public function preventas(){
      return $this->hasMany(Preventa::class);
    }
    public function ventas(){
      return $this->hasMany(Venta::class);
    }
}
