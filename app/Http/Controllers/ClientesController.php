<?php

namespace App\Http\Controllers;
use Auth;
use App\User;
use App\Cliente;
use App\Servicio;

use App\ClienteServicio;

use Illuminate\Http\Request;

class ClientesController extends Controller
{
    public function Add(Request $request){

      $control=$request->control;
      $nombre=$request->nombre;
      $direccion1=$request->direccion1;
      $direccion2=$request->direccion2;
      $cel1=$request->cel1;
      $cel2=$request->cel2;
      $fijo=$request->fijo;
      $documento=$request->documento;
      $direccion_fiscal=$request->direccion_fiscal;
      $condicion_fiscal=$request->condicion_fiscal;
      $empresa_id=auth()->user()->empresa_id;
      $razon_social=$request->razon_social;
       $servicios=json_decode($request->get('servicios'));
       $descuentos=json_decode($request->get('descuentos'));
       $vigencias=json_decode($request->get('vigencias'));

      if($control==0){
        $data =new Cliente();

          $data->nombre=$nombre;
           $data->direccion1=$direccion1;
           $data->direccion2=$direccion2;
           $data->cel1=$cel1;
           $data->cel2=$cel2;
           $data->fijo=$fijo;
           $data->documento=$documento;
           $data->direccion_fiscal=$direccion_fiscal;
           $data->condicion_fiscal=$condicion_fiscal;
           $data->empresa_id=$empresa_id;
           $data->razon_social=$razon_social;
           /*if($request->hasFile('profile_image')){
            $prof=$request->file('profile_image');
             $profname=$prof->getClientOriginalName();
             $destino=public_path('/dist/clientes');
             $prof->move($destino,$profname);
             $data->profile_image='/dist/clientes/'.$profname;
           }*/
           $data->inicio_facturacion=$request->inicio_facturacion;
           $data->save();

           if(count($servicios)>=0){
            $cont=0;
             while ( $cont < count($servicios)) {

                   $item=new ClienteServicio;
                   $item->cliente_id=$data->id;
                   $item->servicio_id=$servicios[$cont];
                   $item->descuento=$descuentos[$cont];
                   $item->vigencia=$vigencias[$cont];
                   $item->save();
                   $cont++;
                }
           }
              return response()->json(array('id_last'=>$data->id),200);

      }else{


      }

    }

    public function PerfilClientes(Request $request){

     $id=$request->id;
     $cliente=Cliente::findOrFail($id);
     $servicios=ClienteServicio::where('cliente_id',$id)->get();
     $empresa_id=auth()->user()->empresa_id;
     $servis=Servicio::where('empresa_id',$empresa_id)->get();


        $saldo = 0;
     $cuentas = [];
     foreach ($cliente->facturas as $factura){
              $item = new \stdClass();
              $item->id = $factura->id;
              $item->numero = str_pad($factura->PtoVta, 5, '0', STR_PAD_LEFT).'-'.str_pad($factura->CbteDesde, 8, '0', STR_PAD_LEFT);
              $item->tipo = 'Factura';
              $item->fecha  = $factura->created_at;
              $item->total = $factura->ImpTotal;
              $cuentas[] = $item;
              $saldo = $saldo + (float)$factura->ImpTotal;
     }
     foreach ($cliente->recibos as $recibo){
         $item = new \stdClass();
         $item->id = $recibo->id;
         $item->numero = str_pad($recibo->id, 5, '0', STR_PAD_LEFT);
         $item->tipo = 'Recibo';
         $item->fecha  = $recibo->created_at;
         $item->total = (float)$recibo->total*-1;
         $cuentas[] = $item;
         $saldo = $saldo + ((float)$recibo->total*-1);
     }
     foreach ($cliente->ventas as $venta){
         $item = new \stdClass();
         $item->id = $venta->id;
         $item->numero = str_pad($venta->id, 5, '0', STR_PAD_LEFT);
         $item->tipo = 'Recibo';
         $item->fecha  = $venta->created_at;
         $item->total = $venta->total;
         $cuentas[] = $item;
         $saldo = $saldo + (float)$venta->total;
     }
      $cuentas = collect($cuentas);
      $cuentas = $cuentas->sortBy('fecha');

      return view('Clientes.PerfilClientes',compact('cliente','servicios','servis','cuentas','saldo'));
    }
    public function upload_profile(Request $request){
       $id=$request->get('cliente_id');
      if($request->hasFile('profile_image')){
         $cliente=Cliente::findOrFail($id);
//         $cliente->profile_image=
         $prof= $request->file('profile_image');
         $profname=$prof->getClientOriginalName();
          /*
          $clave->move(storage_path().);
          $cert->move(storage_path().);*/

         $destino=public_path('/dist/clientes');
         $prof->move($destino,$profname);
         $cliente->profile_image=$profname;
         $cliente->save();
      }else{}

        $cliente=Cliente::findOrFail($id);
        $servicios=ClienteServicio::where('cliente_id',$id)->get();
        $empresa_id=auth()->user()->empresa_id;
        $servis=Servicio::where('empresa_id',$empresa_id)->get();
         return view('Clientes.PerfilClientes',compact('cliente','servicios','servis'));
    }

    public function getClientes(){
      $clientes=[];
      foreach (Cliente::where('empresa_id',auth()->user()->empresa_id)->get() as $cliente) {
        $obj =new \stdClass;
        $obj->id=$cliente->id;
        $obj->nombre=$cliente->nombre;
        $obj->direccion=$cliente->direccion1;
        $clientes[]=$obj;
      }
       return response()->json($clientes);
    }
}
