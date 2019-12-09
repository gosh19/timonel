<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
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

class ReciboController extends Controller
{
  public function addRecibo(Request $request){

    $idCompany = auth()->user()->empresa_id;
    $Company = DB::table('empresas')
      ->where('id',$idCompany)
      ->first();

      $dataT = array(
         "Cliente" => $request -> SelectCliente,
         "Items" => $request -> items,
         "total" => $request -> total,
         "Usuario" => "1"
      );

       $recibo = new Recibo;
       $recibo -> cliente_id = $request -> SelectCliente;
       $recibo -> empresa_id = auth()->user()->empresa_id;
       $recibo -> total = $request -> total;
       $recibo -> fecha = date('Y-m-d');
       $recibo -> metodo_pago = $request -> metodo_pago;
       $recibo -> items = $request -> items;
       $recibo -> save();

       $items = json_decode($request -> get('items'));

       //debe, pagado
       $totalRecibo = $recibo-> total;

       $cont = 0;
       while ($cont<count($items)) {
         if($items[$cont][1] == 'Factura'){
           $factura = Factura::where('id',$items[$cont][0]) -> first();
           $totalRecibo =  $factura -> ImpTotal - $totalRecibo;

           if($totalRecibo <= 0){
              $factura -> debe = 0;
              $factura -> pagado = $factura -> ImpTotal;
              $factura -> estado = 'Pagada';
           }
           if($totalRecibo > 0){
               $factura -> debe = $totalRecibo;
               $factura -> pagado = $factura -> ImpTotal - $totalRecibo;
           }

           $factura -> save();

           $facturarecibo = new FacturaRecibo;
           $facturarecibo -> factura_id = $items[$cont][0];
           $facturarecibo -> recibo_id = $recibo -> id;
           $facturarecibo -> save();
           $cont++;
         }else{
           $venta = Venta::where('id',$items[$cont][0]) -> first();
           $totalRecibo =  $venta -> total - $totalRecibo;

           if($totalRecibo <= 0){
              $venta -> debe = 0;
              $venta -> pagado = $venta -> total;
              $venta -> estado = 'Pagada';
           }
           if($totalRecibo > 0){
               $venta -> debe = $totalRecibo;
               $venta-> pagado = $venta -> total - $totalRecibo;
           }

           $venta -> save();

           $ventarecibo = new ReciboVenta;
           $ventarecibo -> venta_id = $items[$cont][0];
           $ventarecibo -> recibo_id = $recibo -> id;
           $ventarecibo -> save();
           $cont++;

         }
       }

       $pdf = PDF::loadView('Comprobantes.Recibo', compact('dataT','Company','recibo'));
        return $pdf->stream('testfile')
               ->header('Content-Type','application/pdf');
        exit;


      return 0;
    }
    public function getRecibo(Request $request){

        $recibo = Recibo::where('id',$request -> id) -> first();
        $idCompany = auth()->user()->empresa_id;
        $Company = DB::table('empresas')
          ->where('id',$idCompany)
          ->first();

          $dataT = array(
             "Cliente" => $recibo -> cliente_id,
             "Items" => $recibo -> items,
             "total" => $recibo -> total,
             "Usuario" => "1"
          );

          $pdf = PDF::loadView('Comprobantes.Recibo', compact('dataT','Company','recibo'));
           return $pdf->stream('testfile')
                  ->header('Content-Type','application/pdf');
           exit;

    }
    public function deleteRecibo(Request $request){
        $recibo = Recibo::where('id',$request -> recibo_id) -> first();

        $recibo -> delete();

        return 0;
    }

    public function Recibos(){
        if(Auth::check()){

            $recibos=Recibo::where('empresa_id',auth()->user()->empresa_id)->get();
            $facturas=Factura::where('empresa_id',auth()->user()->empresa_id)->get();
            $ventas=Venta::where('empresa_id',auth()->user()->empresa_id)->get();

           return view('ABMS/Recibos',compact('recibos','facturas','ventas'));
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
}
