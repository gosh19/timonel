<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ordenCompra extends Model
{
    protected $fillable = [
        'proveedor','Total','Items','user_empresa','user_name','user_id'
    ];    
}
