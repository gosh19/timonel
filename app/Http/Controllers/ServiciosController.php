<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Producto;
use App\Proveedore;
use App\Servicio;
use App\ProductoServicio;
use Illuminate\Support\Facades\Auth;

class ServiciosController extends Controller
{

    public function deleteservicio(Request $request){
        $id=$request->id;
        $servicio=Servicio::findOrFail($id);
        $servicio->delete();
        
        return "Servicio Eliminado";
    }
    public function ver(Request $request){
          $servicio=Servicio::findOrFail($request->id);
          return response()->json($servicio);
    }

    public function getproductos(Request $request){
         $servicio=Servicio::with('productos')->findOrFail($request->id);
         $data='';

         foreach ($servicio->Productos as $producto) {
            $cantidad='<input type=\"number\" class=\"form-control\" style=\"width:75px;\" value=\"'.$producto->pivot->cantidad.'\">';
            $del='<button type=\"button\" class=\"btn btn-danger btnDelete\"><i class=\"fa fa-eraser\"></i></button>';

           $data.='{
             "id":"'.$producto->id.'",
             "nombre":"'.$producto->nombre.'",
             "costo":"'.$producto->costo.'",
             "cantidad":"'.$cantidad.'",
             "accion":"'.$del.'",
             "tipo_iva":"'.$producto->tipo_iva.'"
           },';
         }

         $data = substr($data,0, strlen($data) - 1);
         $productos ='['.$data.']';

         return response()->json($productos);
    }
    public function agregar(Request $request){
     $productos=json_decode($request->get('productos'));
     $cantidades=json_decode($request->get('cantidades'));
     $nombre=$request->nombre;
     $descripcion=$request->descripcion;
     $valor_publico=$request->valor_publico;
     $costo=$request->costo;
     $tipo=$request->tipo;
     $control=$request->control;
     $divisas = auth()->user()->empresa->divisas;
                           foreach($divisas as $divisa){
                               $moneda = $divisa->id;
                           }
     if($control==0){
     $servicio=new Servicio;
     $servicio->nombre=$nombre;
     $servicio->descripcion=$descripcion;
     $servicio->valor_publico=$valor_publico;
     $servicio->costo=$costo;
     $servicio->tipo=$tipo;
     $servicio->empresa_id=auth()->user()->empresa_id;
     $servicio->divisa_id = $moneda;
     $servicio->valor_cambio =$request->valor_cambio;
     $servicio->save();


      if(count($productos)>=0){
        $cont=0;
       while ( $cont < count($productos)) {

             $item=new ProductoServicio;
             $item->producto_id=$productos[$cont];
             $item->servicio_id=$servicio->id;
             $item->cantidad=$cantidades[$cont];
             $item->save();
             $cont++;
          }
       }else{}

         if($servicio->id>0){
           return "Servicio Agregado";
         }else{
           return "Error en el Controlador";
         }
      }else{
        $servicio=Servicio::findOrFail($control);
        $servicio->nombre=$nombre;
        $servicio->descripcion=$descripcion;
        $servicio->valor_publico=$valor_publico;
        $servicio->costo=$costo;
        $servicio->tipo=$tipo;
        $servicio->empresa_id=auth()->user()->empresa_id;
        $servicio->valor_cambio =$request->valor_cambio;
        $servicio->divisa_id = $moneda;
        
        $servicio->Productos()->detach();

        if(count($productos)>=0){
          $cont=0;
         while ( $cont < count($productos)) {

               $item=new ProductoServicio;
               $item->producto_id=$productos[$cont];
               $item->servicio_id=$servicio->id;
               $item->cantidad=$cantidades[$cont];
               $item->save();
               $cont++;
            }
         }else{}
       $servicio->save();
          return "El Servicio a sido Editado Correctamente";

      }
    }
}
