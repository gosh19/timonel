<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReciboVenta extends Model
{
    protected $table='recibos_ventas';

    public function recibo(){
        return $this->belongsTo(Recibo::class);
    }
    public function venta(){
        return $this->belongsTo(Venta::class);
    }
}
