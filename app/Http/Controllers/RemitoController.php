<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
use App\Remito;
use Auth;
use App\Libs\Afip\Classe\ElectronicBilling;
use App\Libs\Afip\Afip;

use Barryvdh\DomPDF\Facade as PDF;

class RemitoController extends Controller
{
  public function Remito(){

        $Clientes = Cliente::where('empresa_id',auth()->user()->empresa_id)->get();

        $Productos = Producto::where('empresa_id',auth()->user()->empresa_id)->get();

        $Remitos = Remito::where('empresa_id',auth()->user()->empresa_id)->get();

        $Servicios = Servicio::where('empresa_id',auth()->user()->empresa_id)->get();

        return view('ABMS.Remito',compact('Clientes','Productos','Remitos','Servicios'));

  }
  public function addRemito(Request $request){
    $idCompany = auth()->user()->empresa_id;
    $Company = DB::table('empresas')
      ->where('id',$idCompany)
      ->first();

      $dataT = array(
         "Cliente" => $request -> SelectCliente,
         "Items" => $request -> items,
         "total" => $request -> TotalRemito,
         "Usuario" => "1",
         "Iva21" => $request -> iva21,
         "Iva105" => $request -> iva105,
         "Iva27" => $request -> iva27
      );
    $items = $request -> items;

    $cliente = Cliente::where('id',$dataT["Cliente"])->first();

      $Remito=new Remito;
      $Remito->cliente_id=$dataT["Cliente"];
      $Remito->empresa_id=$Company-> id;
      $Remito->total=round($dataT["total"],2);
      $Remito->subtotal=round($request -> neto,2);
      $Remito->fecha=date('Y-m-d');
      $Remito->items=$items;
      $Remito->estado='Pendiente';
      $Remito->iva21 = round($request -> iva21,2);
      $Remito->iva105 = round($request -> iva105,2);
      $Remito->iva27 = round($request -> iva27,2);
      $Remito->Netoiva21 = round($request -> Netoiva21,2);
      $Remito->Netoiva105 = round($request -> Netoiva105,2);
      $Remito->Netoiva27 = round($request -> Netoiva27,2);
      $Remito->tipo = $request->tipo;
      $Remito->save();
      //dd($items);
      $cont = 0;
      $items = json_decode($Remito -> items);
      while ($cont<count($items)) {
               if($items[$cont][1] != '9999' && $items[$cont][0] == 'Producto'){

                   $producto = Producto::where('id',$items[$cont][1]) -> first();
                   $stock = $producto -> stock;
                   $stock = $stock - $items[$cont][9];
                   $producto -> stock = $stock;
                   $producto -> save();
               }
              if($items[$cont][1] != 9999 && $items[$cont][0] == 'Servicio'){
                   $Servicio = Servicio::where('id',$items[$cont][1]) -> first();

                     foreach ($Servicio -> productos as $producto) {
                       $stock = $producto -> stock;
                       $stock = $stock - ((float)$items[$cont][9]*(float)$producto->pivot->cantidad);
                       $producto -> stock = $stock;
                       $producto -> save();
                     }

                 }
               $cont++;
           }

     $pdf = PDF::loadView('Comprobantes.Remito', compact('dataT','Company','Remito'));
     return $pdf->stream('testfile')
             ->header('Content-Type','application/pdf');
     exit;

     //dd($Preventa);

      }

      public function getRemito(Request $request){

          $Remito = Remito::where('id',$request -> id) -> first();
          $idCompany = auth()->user()->empresa_id;
          $Company = DB::table('empresas')
            ->where('id',$idCompany)
            ->first();
             //dd($factura);
             $Items = $Remito -> items;
             $dataT = array(
                "Cliente" => $Remito -> SelectCliente,
                "Items" => $Items,
                "total" => $Remito -> TotalPreVenta,
                "Usuario" => "1",
                "Iva21" => $Remito -> iva21,
                "Iva105" => $Remito -> iva105,
                "Iva27" => $Remito -> iva27
             );


       $pdf = PDF::loadView('Comprobantes.Remito', compact('dataT','Company','Remito'));
        return $pdf->stream('testfile')
               ->header('Content-Type','application/pdf');
        exit;

      }


      public function venderRemito(Request $request){

          $remito = Remito::where('id', $request -> id) -> first();
          $items = json_decode($remito -> items);
          //$servicios = json_decode($request -> get('servicios'));
          //$cantidadProductos = json_decode($request -> get('cantidadProductos'));
          $total = $remito -> subtotal;
          $fecha = date('Y-m-d');
          $empresa_id = auth() -> user() -> empresa_id;
          $cliente_id = $remito -> cliente_id;
          $estado = 'Impaga';

          $venta = new Venta;
          $venta -> items = $remito -> items;
          $venta -> total = $total;
          $venta -> fecha = $fecha;
          $venta -> subtotal = $remito -> subtotal;
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
                     if($remito->tipo == 'Alquiler de Productos'){
                       $stock = $producto -> stock;
                       $stock = $stock - $items[$cont][9];
                       $producto -> stock = $stock;
                       $producto -> save();
                     }
                     $item = new Item;
                     $item->cliente_id = $cliente_id;
                     $item -> descripcion = $producto -> nombre;
                     $item -> cantidad = $items[$cont][9];
                     $item -> sub_total = (float)$producto -> valor_venta * (float)$items[$cont][9];
                     $iva = $producto -> tipo_iva;
                     $impuesto = $producto -> valor_venta * ($iva/100);
                     $total = $producto -> valor_venta;
                     $item -> total = $total;
                     $item -> codigo = $producto -> codigo;
                     $item -> save();

                     $Itemventa = new ItemVenta;
                     $Itemventa -> venta_id = $venta -> id;
                     $Itemventa -> item_id = $item -> id;
                     $Itemventa -> save();

                   }
                   if($items[$cont][1] == 9999){
                     $item = new Item;
                     $item->cliente_id = $cliente_id;
                     $item -> descripcion = $items[$cont][3];
                     $item -> cantidad = $items[$cont][9];
                     $item -> sub_total = $items[$cont][5];
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
                       $total = (float)$Servicio -> valor_publico * (float)$items[$cont][9];
                       $item -> total = $total;
                       $item -> codigo = $Servicio -> id;
                       $item -> save();

                       $Itemventa = new ItemVenta;
                       $Itemventa -> venta_id = $venta -> id;
                       $Itemventa -> item_id = $item -> id;
                       $Itemventa -> save();

                       foreach ($Servicio -> productos as $producto) {
                         if($remito->tipo == 'Alquiler de Productos'){
                           $stock = $producto -> stock;
                           $stock = $stock + ((float)$items[$cont][9]*(float)$producto->pivot->cantidad);
                           $producto -> stock = $stock;
                           $producto -> save();
                         }
                       }
                     }
                   $cont++;
          }

          $remito -> estado = "Vendido";
          $remito -> save();
          $Company = Empresa::where('id',auth()->user()->empresa_id)->first();

          $items = ItemVenta::where('venta_id',$venta->id)->get();

          $pdf = PDF::loadView('Comprobantes/PresupuestoX', compact('venta','Company','items'));
          return $pdf->stream('testfile')
                  ->header('Content-Type','application/pdf');
          exit;


      }
      public function facturarRemito(Request $request){

              $remito = Remito::where('id',$request -> id) -> first();
              $idCompany = auth()->user()->empresa_id;
              $Company = DB::table('empresas')
                ->where('id',$idCompany)
                ->first();

                $dataT = array(
                   "Cliente" => $remito -> cliente_id,
                   "Items" => $remito -> items,
                   "total" => $remito -> total,
                   "Usuario" => "1",
                   "Iva21" => $remito-> iva21,
                   "Iva105" => $remito -> iva105,
                   "Iva27" => $remito -> iva27
                );
              $items = json_decode($remito -> items);
          //  dd(  count($items));

          if($remito->tipo == 'Alquiler de Productos'){
            $cont = 0;
            while ($cont<count($items)) {

              if($items[$cont][1] != '9999' && $items[$cont][0] == 'Producto'){

                $producto = Producto::where('id',$items[$cont][1]) -> first();
                $stock = $producto -> stock;
                $stock = $stock + $items[$cont][9];
                $producto -> stock = $stock;
                $producto -> save();
              }
              if($items[$cont][1] != 9999 && $items[$cont][0] == 'Servicio'){
                $Servicio = Servicio::where('id',$items[$cont][1]) -> first();

                foreach ($Servicio -> productos as $producto) {
                  $stock = $producto -> stock;
                  $stock = $stock + ((float)$items[$cont][9]*(float)$producto->pivot->cantidad);
                  $producto -> stock = $stock;
                  $producto -> save();
                }

              }
              $cont++;
            }
          }

              $items = json_encode($remito -> items);

              $cliente = Cliente::where('id',$remito -> cliente_id)->first();


                $doc=$cliente->cuit;
                if(strlen($doc)==11){
                  $codigodocumento=80;
                  $codigoComprobante = "1";
                }else{
                  $codigoComprobante = "6";
                  $codigodocumento=99;
                 $doc=0;
                }
                $afip = new Afip(array('CUIT' => '20200765193','production'=> FALSE,'cert' => $Company->certificado,'key' => $Company->clave));
                 $last_voucher = $afip->ElectronicBilling->GetLastVoucher(2,$codigoComprobante);
                 $comprobante=$last_voucher+1;
                 $totaliva = $dataT["total"] - $remito -> subtotal;
                 $ImpIVA = (float)$remito -> iva21 + (float)$remito -> iva27+(float)$remito -> iva105;
                 $ImpIVA = round($ImpIVA,2);

                 $IVAS = array();
                 $Netoiva21 = (float)$remito -> Netoiva21 - (float)$remito -> iva21;
                 $Netoiva105 = (float)$remito -> Netoiva105 - (float)$remito -> iva105;
                 $Netoiva27 = (float)$remito -> Netoiva27 - (float)$remito -> iva27;

                 $cont = 0;
                 if($remito -> iva21 != 0.00){
                  $arr21 = array(
                     'Id' 		=> 5, // Id del tipo de IVA (5 para 21%)(ver tipos disponibles)
                     'BaseImp' 	=> round($Netoiva21,2), // Base imponible
                     'Importe' 	=> round($remito -> iva21,2) // Importe
                   );
                   $IVAS[$cont] = $arr21;
                   $cont++;
                }

                if($remito -> iva105 != 0.00){
                  $arr105 = array(
                     'Id' 		=> 4, // Id del tipo de IVA (5 para 21%)(ver tipos disponibles)
                     'BaseImp' 	=> round($Netoiva105,2), // Base imponible
                     'Importe' 	=> round($remito -> iva105,2) // Importe
                   );
                   $IVAS[$cont] = $arr105;
                   $cont++;

                }

                if($remito -> iva27 != 0.00){
                  $arr27 = array(
                     'Id' 		=> 6, // Id del tipo de IVA (5 para 21%)(ver tipos disponibles)
                     'BaseImp' 	=> round($Netoiva27,2), // Base imponible
                     'Importe' 	=> round($remito -> iva27,2) // Importe
                   );
                   $IVAS[$cont] = $arr27;
                   $cont++;

                }
                date_default_timezone_set("America/Araguaina");
                 //dd($IVAS);
                $data = array(
                  'CantReg' 	=> 1,  // Cantidad de comprobantes a registrar
                  'PtoVta' 	=> 2,  // Punto de venta
                  'CbteTipo' 	=> (int)$codigoComprobante,  // Tipo de comprobante (ver tipos disponibles)
                  'Concepto' 	=> 1,  // Concepto del Comprobante: (1)Productos, (2)Servicios, (3)Productos y Servicios
                  'DocTipo' 	=> $codigodocumento, // Tipo de documento del comprador (99 consumidor final, ver tipos disponibles)
                  'DocNro' 	=> $doc,  // Número de documento del comprador (0 consumidor final)
                  'CbteDesde' 	=> $comprobante,  // Número de comprobante o numero del primer comprobante en caso de ser mas de uno
                  'CbteHasta' 	=> $comprobante,  // Número de comprobante o numero del último comprobante en caso de ser mas de uno
                  'CbteFch' 	=> intval(date('Ymd')), // (Opcional) Fecha del comprobante (yyyymmdd) o fecha actual si es nulo
                  'ImpTotal' 	=> round($dataT["total"],2), // Importe total del comprobante
                  'ImpTotConc' 	=> 0,   // Importe neto no gravado
                  'ImpNeto' 	=> round($remito -> subtotal,2), // Importe neto gravado
                  'ImpOpEx' 	=> 0,   // Importe exento de IVA
                  'ImpIVA' 	=> $ImpIVA,  //Importe total de IVA
                  'ImpTrib' 	=> 0,   //Importe total de tributos
                  'MonId' 	=> 'PES', //Tipo de moneda usada en el comprobante (ver tipos disponibles)('PES' para pesos argentinos)
                  'MonCotiz' 	=> 1,     // Cotización de la moneda usada (1 para pesos argentinos)
                  'Iva' 		=> $IVAS,
                );
                $res = $afip->ElectronicBilling->CreateNextVoucher($data);
                $CAE= $res['CAE']; //CAE asignado el comprobante
                $CAEFchVto= $res['CAEFchVto']; //Fecha de vencimiento del CAE (yyyy-mm-dd)
                $voucher_number=$res['voucher_number'];

                $factura=new Factura;
                $factura->CantReg=1;
                $factura->PtoVta=2;
                $factura->CbteTipo=$codigoComprobante;
                $factura->Concepto=3;
                $factura->DocTipo=$codigodocumento;
                $factura->DocNro=$doc;
                $factura->CbteDesde=$comprobante;
                $factura->CbteHasta=$comprobante;
                $factura->CbteFch=intval(date('Ymd'));
                $factura->ImpTotal=round($dataT["total"]);
                $factura->ImpTotConc=0;
                $factura->ImpNeto=round($remito -> subtotal,2);
                $factura->ImpOpEx=0;
                $factura->ImpIVA=$ImpIVA;
                $factura->ImpTrib=0;
                $factura->MonId='PES';
                $factura->MonCotiz=1;
                $factura->CAE=$CAE;
                $factura->CAEFchVto=$CAEFchVto;
                $factura->voucher_number=$voucher_number;
                $factura->cliente_id=$dataT["Cliente"];
                $factura->empresa_id = auth()->user()->empresa_id;
                $factura->items = $remito -> items;
                $factura->debe= round($dataT["total"],2);
                $factura->pagado = 0.00;
                $factura->estado = 'Impaga';
                $factura->iva21 = round($remito -> iva21,2);
                $factura->iva105 = round($remito -> iva105,2);
                $factura->iva27 = round($remito -> iva27,2);
                $factura->save();

                $remito -> estado = 'Facturado';
                $remito -> save();
               $pdf = PDF::loadView('Factura', compact('dataT','Company','factura'));
               return $pdf->stream('testfile')
                       ->header('Content-Type','application/pdf');
               exit;


      }

}
