<?php

namespace App\Http\Controllers;

use App\Proveedore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class proveedoresController extends Controller
{
    public function ini(){
      if(Auth::check()){

         $proveedores=Proveedore::where('empresa_id', auth()->user()->empresa_id)->get();
        return view('ABMS/Proveedores',compact('proveedores'));
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
      $Proveedor= Proveedore::where("id",$id)->first();
      return response()->json($Proveedor);
    }
    public function add(Request $request){
          $control=$request->control;
          $razon_social=$request->razon_social;
          $cuit=$request->cuit;
          $direccion=$request->direccion;
          $empresa_id=auth()->user()->empresa_id;
          $telefono=$request->telefono;
          $correo=$request->correo;
          if($control==0){
               $data=new Proveedore();
               $data->razon_social=$razon_social;
               $data->cuit=$cuit;
               $data->direccion=$direccion;
               $data->empresa_id=$empresa_id;
               $data->telefono=$telefono;
               $data->correo=$correo;
               $data->save();
               $new_id=$data->id;
             if($new_id!=null){
               $respuesta="En Buena Hora una Nuevo Proveedor a sido cargado!";
             }else{
               $respuesta="No se a podido Crear el Registro";
             }
          }else{
               $data=Proveedore::where("id",$control)->first();
               $data->razon_social=$razon_social;
               $data->cuit=$cuit;
               $data->direccion=$direccion;
               $data->empresa_id=$empresa_id;
               $data->telefono=$telefono;
               $data->correo=$correo;
               $data->save();
               $respuesta="Se han Guardado los Cambios!";
          }
          return $respuesta;
    }
    public function delete(Request $request){
          $id=$request->id;

          $proveedor= Proveedore::where("id",$id)->first();
          $proveedor->delete();
          return "";
    }
}
