<?php

namespace App\Http\Controllers;
use Auth;
use App\User;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;

class UsuariosController extends Controller
{
    public function agregar_usuario(Request $request){
        $mensaje="";
         $empresa=auth()->user()->empresa_id;
         $limite=auth()->user()->empresa->usuariosPermitidos;
         $count = User::where("empresa_id",$empresa)->count();
         $name=$request->name;
         $email=$request->email;
         $password=$request->password;
         $categoria=$request->role_id;
         $control=$request->control;
         $descripcion_puesto=$request->descripcion_puesto;
         if($control==0){
             if($count<=$limite){
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
                 $mensaje="Ha llegado al Limite de Usuarios Disponibles";
             }

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
           $user->save();
         }

         return $mensaje;
    }

    public function delete(Request $request){
             $id=$request->id;
             $user= User::where("id",$id)->first();
             $user->delete();
             return "";
    }

    public function edit(Request $request){
        $id=$request->id;
        $user= User::where("id",$id)->first();
        return response()->json($user);
    }
    public function poner_foto(Request $request){
        $id=auth()->user()->id;
        if($request->hasFile('foto_perfil')){
           $usuario=User::findOrFail($id);
  //         $cliente->profile_image=
           $prof= $request->file('foto_perfil');
           $profname=$prof->getClientOriginalName();
            /*
            $clave->move(storage_path().);
            $cert->move(storage_path().);*/

           $destino=public_path('/dist/users');
           $prof->move($destino,$profname);
           $usuario->profile_image='../../public/dist/users/'.$profname;
           $usuario->save();
        }else{}

           return redirect()->to('/');
    }
    public function cambio_contrasena(Request $request){
         $id=auth()->user()->id;
         $usuario=User::findOrFail($id);
         $contrasena_actual = $request->contrasena_actual;
       //  $contrasena_actual = bcrypt($contrasena_actual);
         $contrasena_nueva = $request -> contrasena_nueva;
         //
         if(Hash::check($contrasena_actual, $usuario->password)){
            $contrasena_nueva = bcrypt($contrasena_nueva);
            $usuario -> password = $contrasena_nueva;
            $usuario -> save();
            return 0;
         }else{
            return 1;
         }
    }


}
