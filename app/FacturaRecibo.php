<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FacturaRecibo extends Model
{
    protected $table = 'facturas_recibos';

    public function factura(){
        return $this->belongsTo(Factura::class);
    }

    public function recibo(){
        return $this->belongsTo(Recibo::class);
    }
}
