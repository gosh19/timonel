<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class facturacompra extends Model
{
    protected $fillable = [
        'usuario','sucursalusuario','tipocomprobante','razon_social','cuit','fechadecarga','pv','numero','vencimiento','estado','metodopago','total_bruto','items'
    ];
}
