<?php

namespace App\Http\Controllers\Admin;

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
use Auth;
use App\Libs\Afip\Classe\ElectronicBilling;
use App\Libs\Afip\Afip;

use Barryvdh\DomPDF\Facade as PDF;

class PreventaController extends Controller
{
    public function Preventa(){

          $Clientes = Cliente::where('empresa_id',auth()->user()->empresa_id)->get();

          $Productos = Producto::where('empresa_id',auth()->user()->empresa_id)->get();

          $Preventas = Preventa::where('empresa_id',auth()->user()->empresa_id)->get();

          $Servicios = Servicio::where('empresa_id',auth()->user()->empresa_id)->get();

          return view('ABMS.Preventa',compact('Clientes','Productos','Preventas','Servicios'));

    }
    public function addPreventa(Request $request){
      $idCompany = auth()->user()->empresa_id;
      $Company = DB::table('empresas')
        ->where('id',$idCompany)
        ->first();

        $dataT = array(
           "Cliente" => $request -> SelectCliente,
           "Items" => $request -> items,
           "total" => $request -> TotalPreVenta,
           "Usuario" => "1",
           "Iva21" => $request -> iva21,
           "Iva105" => $request -> iva105,
           "Iva27" => $request -> iva27
        );
      $items = $request -> items;

      $cliente = Cliente::where('id',$dataT["Cliente"])->first();

        $Preventa=new Preventa;
        $Preventa->cliente_id=$dataT["Cliente"];
        $Preventa->empresa_id=$Company-> id;
        $Preventa->total=round($dataT["total"],2);
        $Preventa->subtotal=round($request -> neto,2);
        $Preventa->fecha=date('Y-m-d');
        $Preventa->items=$items;
        $Preventa->estado='Pendiente';
        $Preventa->iva21 = round($request -> iva21,2);
        $Preventa->iva105 = round($request -> iva105,2);
        $Preventa->iva27 = round($request -> iva27,2);
        $Preventa->Netoiva21 = round($request -> Netoiva21,2);
        $Preventa->Netoiva105 = round($request -> Netoiva105,2);
        $Preventa->Netoiva27 = round($request -> Netoiva27,2);
        $Preventa->save();

       $pdf = PDF::loadView('Comprobantes.PresupuestoR', compact('dataT','Company','Preventa'));
       return $pdf->stream('testfile')
               ->header('Content-Type','application/pdf');
       exit;

       //dd($Preventa);

        }
    public function getPreventa(Request $request){

        $Preventa = Preventa::where('id',$request -> id) -> first();
        $idCompany = auth()->user()->empresa_id;
        $Company = DB::table('empresas')
          ->where('id',$idCompany)
          ->first();
           //dd($factura);
           $Items = $Preventa -> items;
           $dataT = array(
              "Cliente" => $Preventa -> SelectCliente,
              "Items" => $Items,
              "total" => $Preventa -> TotalPreVenta,
              "Usuario" => "1",
              "Iva21" => $Preventa -> iva21,
              "Iva105" => $Preventa -> iva105,
              "Iva27" => $Preventa -> iva27
           );


     $pdf = PDF::loadView('Comprobantes.PresupuestoR', compact('dataT','Company','Preventa'));
      return $pdf->stream('testfile')
             ->header('Content-Type','application/pdf');
      exit;

    }
    public function deletePreventa(Request $request){
          $preventa = Preventa::where('id', $request -> preventa_id) -> first();

          $preventa -> delete();

          return 0;
    }
    public function venderPreventa(Request $request){

        $preventa = Preventa::where('id', $request -> id) -> first();
        $items = json_decode($preventa -> items);
        //$servicios = json_decode($request -> get('servicios'));
        //$cantidadProductos = json_decode($request -> get('cantidadProductos'));
        $total = $preventa -> subtotal;
        $fecha = date('Y-m-d');
        $empresa_id = auth() -> user() -> empresa_id;
        $cliente_id = $preventa -> cliente_id;
        $estado = 'Impaga';

        $venta = new Venta;
        $venta -> items = $preventa -> items;
        $venta -> total = $total;
        $venta -> fecha = $fecha;
        $venta -> subtotal = $preventa -> subtotal;
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
                   $total = $producto -> valor_venta;
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
        $preventa -> estado = "Vendido";
        $preventa -> save();
        $Company = Empresa::where('id',auth()->user()->empresa_id)->first();

        $items = ItemVenta::where('venta_id',$venta->id)->get();

        $pdf = PDF::loadView('Comprobantes/PresupuestoX', compact('venta','Company','items'));
        return $pdf->stream('testfile')
                ->header('Content-Type','application/pdf');
        exit;


    }
    public function facturarPreventa(Request $request){

            $preventa = Preventa::where('id',$request -> id) -> first();
            $idCompany = auth()->user()->empresa_id;
            $Company = DB::table('empresas')
              ->where('id',$idCompany)
              ->first();

              $dataT = array(
                 "Cliente" => $preventa -> cliente_id,
                 "Items" => $preventa -> items,
                 "total" => $preventa -> total,
                 "Usuario" => "1",
                 "Iva21" => $preventa-> iva21,
                 "Iva105" => $preventa -> iva105,
                 "Iva27" => $preventa -> iva27
              );
            $items = json_decode($preventa -> items);
        //  dd(  count($items));
            $cont = 0;
            while ($cont<count($items)) {
                     if($items[$cont][1] != 9999 && $items[$cont][0] == 'Producto'){
                       $producto = Producto::where('id',$items[$cont][1]) -> first();
                       $stock = $producto -> stock;
                       $stock = $stock - $items[$cont][9];
                       $producto -> stock = $stock;
                       $producto -> save();

                     }
                     if($items[$cont][1] == 9999){

                     }

                     if($items[$cont][1] != 9999 && $items[$cont][0] == 'Servicio'){
                       $Servicio = Servicio::where('id',$items[$cont][1]) -> first();

                       foreach ($Servicio -> productos as $producto) {
                         $stock = $producto -> stock;
                         $stock = $stock - $items[$cont][9];
                         $producto -> stock = $stock;
                         $producto -> save();
                       }


                     }

                     $cont++;
            }

            $items = json_encode($preventa -> items);

            $cliente = Cliente::where('id',$preventa -> cliente_id)->first();


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
               $totaliva = $dataT["total"] - $preventa -> subtotal;
               $ImpIVA = (float)$preventa -> iva21 + (float)$preventa -> iva27+(float)$preventa -> iva105;
               $ImpIVA = round($ImpIVA,2);

               $IVAS = array();
               $Netoiva21 = (float)$preventa -> Netoiva21 - (float)$preventa -> iva21;
               $Netoiva105 = (float)$preventa -> Netoiva105 - (float)$preventa -> iva105;
               $Netoiva27 = (float)$preventa -> Netoiva27 - (float)$preventa -> iva27;

               $cont = 0;
               if($preventa -> iva21 != 0.00){
                $arr21 = array(
                   'Id' 		=> 5, // Id del tipo de IVA (5 para 21%)(ver tipos disponibles)
                   'BaseImp' 	=> round($Netoiva21,2), // Base imponible
                   'Importe' 	=> round($preventa -> iva21,2) // Importe
                 );
                 $IVAS[$cont] = $arr21;
                 $cont++;
              }

              if($preventa -> iva105 != 0.00){
                $arr105 = array(
                   'Id' 		=> 4, // Id del tipo de IVA (5 para 21%)(ver tipos disponibles)
                   'BaseImp' 	=> round($Netoiva105,2), // Base imponible
                   'Importe' 	=> round($preventa -> iva105,2) // Importe
                 );
                 $IVAS[$cont] = $arr105;
                 $cont++;

              }

              if($preventa -> iva27 != 0.00){
                $arr27 = array(
                   'Id' 		=> 6, // Id del tipo de IVA (5 para 21%)(ver tipos disponibles)
                   'BaseImp' 	=> round($Netoiva27,2), // Base imponible
                   'Importe' 	=> round($preventa -> iva27,2) // Importe
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
                'ImpNeto' 	=> round($preventa -> subtotal,2), // Importe neto gravado
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
              $factura->ImpNeto=round($preventa -> subtotal,2);
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
              $factura->items = $preventa -> items;
              $factura->debe= round($dataT["total"],2);
              $factura->pagado = 0.00;
              $factura->estado = 'Impaga';
              $factura->iva21 = round($preventa -> iva21,2);
              $factura->iva105 = round($preventa -> iva105,2);
              $factura->iva27 = round($preventa -> iva27,2);
              $factura->save();

              $preventa -> estado = 'Facturado';
              $preventa -> save();
             $pdf = PDF::loadView('Factura', compact('dataT','Company','factura'));
             return $pdf->stream('testfile')
                     ->header('Content-Type','application/pdf');
             exit;


    }

}
