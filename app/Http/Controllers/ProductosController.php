<?php

namespace App\Http\Controllers;

use App\Producto;
use App\Proveedore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use AFIP\Afip;
//use App\Http\Controllers\Afip_res\Afip;
//use App\Http\Controllers\Afip_res\AfipWebService;
//use App\Http\Controllers\Afip_res\ClassT\ElectronicBilling;

class ProductosController extends Controller
{
    public function facturar(Request $request){
    include (app_path (). '/Afip/Afip.php');
      $afip = new Afip(array('CUIT' => 20955290454,'production'=> TRUE,'cert' => 'cert.crt', 'key' => '2487Elba', 'passphrase' =>'2487Elba'));
      $last_voucher = $afip->ElectronicBilling->GetLastVoucher(2,11);
      $comprobante=$last_voucher+1;

     /**/ $data = array(
        'CantReg' 	=> 1,  // Cantidad de comprobantes a registrar
        'PtoVta' 	=> 2,  // Punto de venta
        'CbteTipo' 	=> 11,  // Tipo de comprobante (ver tipos disponibles)
        'Concepto' 	=> 1,  // Concepto del Comprobante: (1)Productos, (2)Servicios, (3)Productos y Servicios
        'DocTipo' 	=> 99, // Tipo de documento del comprador (99 consumidor final, ver tipos disponibles)
        'DocNro' 	=> 0,  // Número de documento del comprador (0 consumidor final)
        'CbteDesde' 	=> $comprobante,  // Número de comprobante o numero del primer comprobante en caso de ser mas de uno
        'CbteHasta' 	=> $comprobante,  // Número de comprobante o numero del último comprobante en caso de ser mas de uno
        'CbteFch' 	=> intval(date('Ymd')), // (Opcional) Fecha del comprobante (yyyymmdd) o fecha actual si es nulo
        'ImpTotal' 	=> 50.00, // Importe total del comprobante
        'ImpTotConc' 	=> 0,   // Importe neto no gravado
        'ImpNeto' 	=> 50.00, // Importe neto gravado
        'ImpOpEx' 	=> 0,   // Importe exento de IVA
        'ImpIVA' 	=> 0,  //Importe total de IVA
        'ImpTrib' 	=> 0,   //Importe total de tributos
        'MonId' 	=> 'PES', //Tipo de moneda usada en el comprobante (ver tipos disponibles)('PES' para pesos argentinos)
        'MonCotiz' 	=> 1     // Cotización de la moneda usada (1 para pesos argentinos)

      );

      $res = $afip->ElectronicBilling->CreateNextVoucher($data);

     //echo $res['CAE']; //CAE asignado el comprobante
     //echo $res['CAEFchVto']; //Fecha de vencimiento del CAE (yyyy-mm-dd)
     //echo $res['voucher_number'];
     $mensaje="CAE: ".$res['CAE']." Vencimiento: ".$res['CAEFchVto']." Numero: ".$res['voucher_number'];
     return $mensaje;

    }
    public function ini(){
      if(Auth::check()){

         $productos=Producto::where('empresa_id', auth()->user()->empresa_id)->get();
         $proveedores=Proveedore::where('empresa_id', auth()->user()->empresa_id)->get();
        return view('ABMS/Productos',compact('productos','proveedores'));
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
    public function edit(Request $request){
      $id=$request->id;
      $Producto= Producto::where("id",$id)->first();
      return response()->json($Producto);
    }
    public function add(Request $request){
          $control=$request->control;
          $valor_venta=$request->valor_venta;
          $stock=$request->stock;
          $proveedore_id=$request->proveedore_id;
          $empresa_id=auth()->user()->empresa_id;
          $costo=$request->costo;
          $stock_sugerido=$request->stock_sugerido;
          $nombre=$request->nombre;
          $codigo=$request->codigo;
          $tipo_iva=$request->tipo_iva;
          
                           $divisas = auth()->user()->empresa->divisas;
                           foreach($divisas as $divisa){
                               $moneda = $divisa->id;
                           }
                           
          if($control==0){
               $data=new Producto();
               $data->valor_venta=$valor_venta;
               $data->stock=$stock;
               $data->proveedore_id=$proveedore_id;
               $data->empresa_id=$empresa_id;
               $data->costo=$costo;
               $data->stock_sugerido=$stock_sugerido;
               $data->nombre=$nombre;
               $data->codigo=$codigo;
               $data->tipo_iva=$tipo_iva;
               $data->divisa_id=$moneda;
               $data->valor_cambio=$request->valor_cambio;
               $data->save();
               $new_id=$data->id;
             if($new_id!=null){
               $respuesta="En Buena Hora una Nuevo Producto a sido cargado!";
             }else{
               $respuesta="No se a podido Crear el Registro";
             }
          }else{
               $data=Producto::where("id",$control)->first();
               $data->valor_venta=$valor_venta;
               $data->stock=$stock;
               $data->proveedore_id=$proveedore_id;
               $data->empresa_id=$empresa_id;
               $data->costo=$costo;
               $data->stock_sugerido=$stock_sugerido;
               $data->nombre=$nombre;
               $data->codigo=$codigo;
               $data->tipo_iva=$tipo_iva;
               $data->divisa_id=$moneda;
               $data->valor_cambio=$request->valor_cambio;
               $data->save();
               $respuesta="Se han Guardado los Cambios!";
          }
          return $respuesta;
    }
    public function delete(Request $request){
          $id=$request->id;

          $proveedor= Producto::where("id",$id)->first();
          $proveedor->delete();
          return "";
    }

    public function getProductos(){
          $productos = Producto::where('empresa_id',auth()->user()->empresa_id)->get();

          $tabla = '';
          foreach($productos as $producto){

            $costo = $producto->valor_venta;
            $iva = $producto->tipo_iva;

            $impuesto = $costo * ($iva/100);
            $total = $costo + $impuesto;

          

            $tabla.='{
              "id":"'.$producto->id.'",
              "Codigo":"'.$producto->codigo.'",
              "Nombre":"'.$producto->nombre.'",
              "Precio":"'.$producto->valor_venta.'",
              "Costo":"'.$producto->costo.'",
              "Iva":"'.$producto->tipo_iva.'",
              "Total":"'.round($total,2).'",
              "Stock":"'.$producto->stock.'"
            },';
          }
          $tabla = substr($tabla,0, strlen($tabla) - 1);
          return '{"data":['.$tabla.']}';
    }
    public function editProductos(Request $request){
         $data =  $request -> data;
          
         foreach($data as $key=>$value){
            $producto = Producto::where('id',$key)->first();
            $producto -> codigo = $value["Codigo"];
            $producto -> nombre = $value["Nombre"];
            $producto -> valor_venta = $value["Precio"];
            $producto -> costo = $value["Costo"];
            $producto -> save();
         }
         
         $productos = Producto::where('empresa_id',auth()->user()->empresa_id)->get();

         $tabla = '';
         foreach($productos as $producto){

           $costo = $producto->valor_venta;
           $iva = $producto->tipo_iva;

           $impuesto = $costo * ($iva/100);
           $total = $costo + $impuesto;


           $tabla.='{
             "id":"'.$producto->id.'",
             "Codigo":"'.$producto->codigo.'",
             "Nombre":"'.$producto->nombre.'",
             "Precio":"'.$producto->valor_venta.'",
             "Costo":"'.$producto->costo.'",
             "Iva":"'.$producto->tipo_iva.'",
             "Total":"'.round($total,2).'",
             "Stock":"'.$producto->stock.'"
           },';
         }
         $tabla = substr($tabla,0, strlen($tabla) - 1);
         return '{"data":['.$tabla.']}';
       
    }
}
