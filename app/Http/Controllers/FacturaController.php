<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Factura;

class FacturaController extends Controller
{
     public function getFacturas(Request $request){
         $cliente_id = $request->cliente_id;
         $facturas = Factura::where('cliente_id',$cliente_id)->where('estado','Impaga')->get();
         $tabla = '';
         foreach($facturas as $factura){
              $tabla.='{
                "#":"",
                "id" : "'.$factura->id.'",
                "tipo":"Factura",
                "numero":"'.str_pad($factura->PtoVta,5,"0",STR_PAD_LEFT).' '.str_pad($factura->CbteDesde, 8, "0", STR_PAD_LEFT).'",
                "subtotal":"'.$factura->ImpTotal.'",
                "total": "'.$factura->ImpNeto.'",
                "debe" : "'.$factura->debe.'",
                "pagado" : "'.$factura->pagado.'"
              },';
            }
            $tabla = substr($tabla,0, strlen($tabla) - 1);
            $tabla = '{"data":['.$tabla.']}';

            return $tabla;
     }
}
