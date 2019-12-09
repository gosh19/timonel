<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class usuarioEmpresa extends Controller
{
public function agregar_usuario(Request $request){

  $mensaje="";
   $empresa=$request->empresa;
   $name=$request->name;
   $email=$request->email;
   $password=$request->password;
   $categoria=$request->role_id;
   $control=$request->control;
   $descripcion_puesto=$request->descripcion_puesto;
   if($control==0){
          $mensaje="Usuario Creado Exitosamente";
          User::insert([
          "name"=>$name,
          "email"=>$email,
          "password"=>bcrypt($password),
          "descripcion_puesto"=>$descripcion_puesto,
          "categoria"=>$categoria,
          "profile_image"=>"../../public/dist/users/default.png",

          "empresa_id"=>$empresa,
          "ip_client"=>"0",
           ]);
   }else{
       $mensaje="Se han Guardado los Cambios";
     $user=User::where("id",$control)->first();
     $user->name=$name;
     $user->email=$email;
     if($password==""){

     }else{
      $user->password=bcrypt($password);
     }
     $user->descripcion_puesto=$descripcion_puesto;
     $user->categoria=$categoria;
     $user->empresa_id=$empresa;
     $user->save();
   }

   return $mensaje;

   }
}
