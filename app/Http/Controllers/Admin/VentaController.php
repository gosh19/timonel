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
use Barryvdh\DomPDF\Facade as PDF;


class VentaController extends Controller
{

   public function Ventas(){
     if(Auth::check()){
       $Clientes = Cliente::where('empresa_id',auth()->user()->empresa_id)->get();

       $Productos = Producto::where('empresa_id',auth()->user()->empresa_id)->get();

       $Facturas = Factura::where('empresa_id',auth()->user()->empresa_id)->get();

       $Servicios = Servicio::where('empresa_id',auth()->user()->empresa_id)->get();

       $Ventas = Venta::where('empresa_id',auth()->user()->empresa_id)->get();

       return view('ABMS.Ventas',compact('Clientes','Productos','Ventas','Servicios'));

     }else{
       $user=User::where('ip_client',\Request::ip())->first();
       if($user!=null){
         $profile_image=$user->profile_image;
         $email=$user->email;
         $name=$user->name;
         return view('lockscreen',['name'=>$name,'profile'=>$profile_image,'email'=>$email]);
       }else{
         return view('/');
       }
     }

   }
    public function addVenta(Request $request){
        $items = json_decode($request -> get('items'));
        //$servicios = json_decode($request -> get('servicios'));
        //$cantidadProductos = json_decode($request -> get('cantidadProductos'));
        $total = $request -> TotalVenta;
        $fecha = date('Y-m-d');
        $empresa_id = auth() -> user() -> empresa_id;
        $cliente_id = $request -> SelectCliente;
        $estado = 'Impaga';

        $venta = new Venta;
        $venta -> items = $request -> get('items');
        $venta -> total = $total;
        $venta -> fecha = $fecha;
        $venta -> subtotal = $request -> TotalVenta;
        $venta -> empresa_id = $empresa_id;
        $venta -> cliente_id = $cliente_id;
        $venta -> estado = $estado;
        $venta -> debe = $total;
        $venta -> pagado = 0.00;
        $venta -> save();


        $cont = 0;
        while ($cont<count($items)) {
                 if($items[$cont][1] != 9999 && $items[$cont][0] == 'Producto'){
                   $producto = Producto::where('id',$items[$cont][1]) -> first();
                   $stock = $producto -> stock;
                   $stock = $stock - $items[$cont][9];
                   $producto -> stock = $stock;
                   $producto -> save();
                   $item = new Item;
                   $item->cliente_id = $cliente_id;
                   $item -> descripcion = $producto -> nombre;
                   $item -> cantidad = $items[$cont][9];
                   $item -> sub_total = (float)$producto -> valor_venta * (float)$items[$cont][9];
                   $iva = $producto -> tipo_iva;
                   $impuesto = $producto -> valor_venta * ($iva/100);
                   $total = (float)$producto -> valor_venta * (float)$items[$cont][9];
                   $item -> total = $total;
                   $item -> codigo = $producto -> codigo;
                   $item -> save();

                   $Itemventa = new ItemVenta;
                   $Itemventa -> venta_id = $venta -> id;
                   $Itemventa -> item_id = $item -> id;
                   $Itemventa -> save();

                   //$cont++;

                 }
                 if($items[$cont][1] == 9999){
                   $item = new Item;
                   $item->cliente_id = $cliente_id;
                   $item -> descripcion = $items[$cont][3];
                   $item -> cantidad = $items[$cont][9];
                   $item -> sub_total = $items[$cont][5];
                  // $iva = $producto -> tipo_iva;
                   //$impuesto = $producto -> valor_venta * ($iva/100);
                   $total = $items[$cont][7];
                   $item -> total = $total;
                   $item -> codigo = $items[$cont][1];
                   $item -> save();

                   $Itemventa = new ItemVenta;
                   $Itemventa -> venta_id = $venta -> id;
                   $Itemventa -> item_id = $item -> id;
                   $Itemventa -> save();
                 }

                   if($items[$cont][1] != 9999 && $items[$cont][0] == 'Servicio'){
                     $Servicio = Servicio::where('id',$items[$cont][1]) -> first();
                     $item = new Item;
                     $item->cliente_id = $cliente_id;
                     $item -> descripcion = $Servicio -> descripcion;
                     $item -> cantidad = $items[$cont][9];
                     $item -> sub_total = (float)$Servicio -> valor_publico * (float)$items[$cont][9];
                    // $iva = $producto -> tipo_iva;
                     //$impuesto = $producto -> valor_venta * ($iva/100);
                     $total = (float)$Servicio -> valor_publico * (float)$items[$cont][9];
                     $item -> total = $total;
                     $item -> codigo = $Servicio -> id;
                     $item -> save();

                     $Itemventa = new ItemVenta;
                     $Itemventa -> venta_id = $venta -> id;
                     $Itemventa -> item_id = $item -> id;
                     $Itemventa -> save();

                     foreach ($Servicio -> productos as $producto) {
                       $stock = $producto -> stock;
                       $stock = $stock - $items[$cont][9];
                       $producto -> stock = $stock;
                       $producto -> save();
                     }


                   }

                 $cont++;


        }
        $Company = Empresa::where('id',auth()->user()->empresa_id)->first();

        $items = ItemVenta::where('venta_id',$venta->id)->get();

        $pdf = PDF::loadView('Comprobantes/PresupuestoX', compact('venta','Company','items'));
        return $pdf->stream('testfile')
                ->header('Content-Type','application/pdf');
        exit;

    }
    public function getVenta(Request $request){


        $venta = Venta::where('id',$request -> venta_id) -> first();
        /*$venta='{
            "cliente"      : "'.$venta->cliente->nombre.'",
            "total"        : "'.$venta->total.'",
            "subtotal"     :  "'.$venta->subtotal.'",
            "fecha"        : "'.$venta->fecha.'",
            "items"        : "'.$venta->items.'"
          }';
          return $venta;*/

          $Company = Empresa::where('id',auth()->user()->empresa_id)->first();

          $items = ItemVenta::where('venta_id',$venta->id)->get();

          $pdf = PDF::loadView('Comprobantes/PresupuestoX', compact('venta','Company','items'));
          return $pdf->stream('testfile')
                  ->header('Content-Type','application/pdf');
          exit;

    }
    public function deleteVenta(){

        $venta = Venta::where('id', $request -> venta_id) -> first();

        $venta -> delete();

        return 0;

    }

    public function getVentas(Request $request){
        $cliente_id = $request->cliente_id;
        $ventas = Venta::where('cliente_id',$cliente_id)->where('estado','Impaga')->get();
        $tabla = '';
        foreach($ventas as $venta){
             $tabla.='{
               "#":"",
               "id" : "'.$venta->id.'",
               "tipo":"Venta",
               "numero":"'.str_pad($venta->id, 8, "0", STR_PAD_LEFT).'",
               "subtotal":"'.$venta->subtotal.'",
               "total": "'.$venta->total.'",
               "debe" : "'.$venta->debe.'",
               "pagado" : "'.$venta->pagado.'"
             },';
           }
           $tabla = substr($tabla,0, strlen($tabla) - 1);
           $tabla = '{"data":['.$tabla.']}';

           return $tabla;
    }

}
