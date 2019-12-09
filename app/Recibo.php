<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recibo extends Model
{
    public function cliente(){
        return $this->belongsTo(Cliente::class);
    }
    public function empresa(){
        return $this->belongsTo(Empresa::class);
    }
    public function facturas(){
        return $this->belongsToMany(Factura::class);
    }
    public function ventas(){
        return $this->belongsToMany(Venta::class);
    }
}
