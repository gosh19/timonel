<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrdenPago extends Model
{
    protected $fillable = [
        'FormaPago','FechaPago','estado','Total','ItemsFacturaPago'    
    ];
}
