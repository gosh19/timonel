<?php

namespace App\Http\Controllers;

use App\Oportunidade;
use App\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class oportunidadController extends Controller
{
   public function edit(Request $request)
   {
     $id=$request->id;
     $user= Oportunidade::where("id",$id)->first();
     return response()->json($user);
   }

  public function ini(){
  if(Auth::check()){

     $oportunidades=Oportunidade::where('empresa_id', auth()->user()->empresa_id)->get();
    return view('ABMS/Opoturnidades',compact('oportunidades'));
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

   public function add(Request $request){

       $control=$request->control;
       $respuesta="";
       $nombre=$request->nombre;
       $telefono=$request->telefono;
       $direccion=$request->direccion;
       $correo=$request->correo;
       $empresa=$request->empresa;
       $cargo=$request->cargo;
       $tipo=$request->tipo;
       $empresa_id=auth()->user()->empresa_id;
       if($control==0){
           $data=new Oportunidade();
              $data->Nombre=$nombre;
               $data->Telefono=$telefono;
               $data->Direccion=$direccion;
               $data->Correo=$correo;
               $data->Empresa=$empresa;
               $data->Cargo=$cargo;
               $data->Tipo=$tipo;
               $data->empresa_id=$empresa_id;
               $data->save();
               $new_id=$data->id;
             if($new_id!=null){
               $respuesta="En Buena Hora una Nueva Oportunidad a sido cargada!";
             }else{
               $respuesta="No se a podido Crear el Registro";
             }
       }else{
             $oportunidad= Oportunidade::where("id",$control)->first();
             $oportunidad->Nombre=$nombre;
             $oportunidad->Direccion=$direccion;
             $oportunidad->Telefono=$telefono;
             $oportunidad->Correo=$correo;
             $oportunidad->Empresa=$empresa;
             $oportunidad->Cargo=$cargo;
             $oportunidad->Tipo=$tipo;
             $oportunidad->save();
             $respuesta="La oportunidad a Sido Modificada Con exito!";
       }
     return $respuesta;


   }

   public function delete(Request $request){
         $id=$request->id;

         $oportunidad= Oportunidade::where("id",$id)->first();
         $oportunidad->delete();
         return "";
   }
}
