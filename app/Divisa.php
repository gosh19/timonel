<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Divisa extends Model
{
    public function productos(){
        return $this->hasMany(Producto::class);
    }
    public function empresa(){
        return $this->belongsTo(Empresa::class);
    }
}
