<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Cliente;

class Factura extends Model
{
   public function cliente(){
     return $this->belongsTo(Cliente::class);
   }
   public function recibos(){
     return $this->belongsToMany(Recibo::class);
   }
}
