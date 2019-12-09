<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductoServicio extends Model
{

    protected $table = "producto_servicio";

    protected $fillable = [
		'producto_id', 'servicio_id', 'cantidad'
    ];
    public function Producto(){

        return $this->belongsTo(Producto::class);
    }
    public function Servicio(){

        return $this->belongsTo(Servicio::class);
    }

  }

  ?>
