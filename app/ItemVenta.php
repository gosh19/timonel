<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemVenta extends Model
{
    protected $table = 'items_ventas';

    public function venta(){
        return $this->belongsTo(Venta::class);
    }
    public function item(){
        return $this->belongsTo(Item::class);
    }
}
