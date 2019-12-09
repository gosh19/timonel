<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Preventa extends Model
{
    public function cliente(){
        return $this->belongsTo(Cliente::class);
    }
    public function empresa(){
        return $this->belongsTo(Empresa::class);
    }
    public function items(){
        return $this->belongsToMany(Item::class);
    }
}
