<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    public function preventas(){
        return $this->belongsToMany(Preventa::class);
    }
    public function ventas(){
        return $this->belongsToMany(Venta::class);
    }
}
