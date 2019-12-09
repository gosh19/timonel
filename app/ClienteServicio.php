<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClienteServicio extends Model
{
  protected $table = "cliente_servicio";

  protected $fillable = [
  'cliente_id', 'servicio_id', 'descuento','vigencia'
  ];
  public function Cliente(){

      return $this->belongsTo(Cliente::class);
  }
  public function Servicio(){

      return $this->belongsTo(Servicio::class);
  }
}
?>
