<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Divisa;
use App\FacturaRecibo;
use App\Preventa;
use App\ItemPreventa;
use App\Venta;
use App\ItemVenta;
use App\ReciboVenta;
use App\Cliente;
use App\Producto;
use App\Item;
use App\Empresa;
use App\Factura;
use App\Recibo;
use App\Servicio;
use Auth;

class DivisaController extends Controller
{
    public function addDivisa(Request $request){
            
    }
    public function getDivisa(Request $request){
    
        $divisa = Divisa::where('empresa_id',auth()->user()->empresa_id)->first();
        
        if($divisa){

            return response()->json($divisa); 

        }else{
             $divisa = new Divisa;
             $divisa -> valor_venta = 0.00;
             $divisa -> valor_compra = 0.00;
             $divisa -> moneda = 'Dolar';
             $divisa -> empresa_id = auth()->user()->empresa_id;
             $divisa -> save();

             return response()->json($divisa);
        }

    }
    public function updateDivisa(Request $request){

        $divisa = Divisa::where('empresa_id',auth()->user()->empresa_id)->first();
        if($divisa){
        $divisa -> valor_venta = $request -> valor_venta;
        $divisa -> valor_compra = $request -> valor_compra;
        $divisa -> save();
        }else{
            $divisa = new Divisa;
            $divisa -> valor_venta = 0.00;
            $divisa -> valor_compra = 0.00;
            $divisa -> moneda = 'Dolar';
            $divisa -> empresa_id = auth()->user()->empresa_id;
            $divisa -> save();

            
       }
        return 0;
    }
}
