<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemPreventa extends Model
{
    protected $table ='Items_preventas';

    public function preventa(){
        return $this->belongsTo(Preventa::class);
    }
    public function Item(){
        return $this->belongsTo(Item::class);
    }
}
