<?php

namespace App\Http\Controllers;
use App\Correo;
use Illuminate\Http\Request;

class CorreoController extends Controller
{
    public function Add(Request $request){
               $correo=$request->correo;
               $cliente_id=$request->cliente_id;
      Correo::insert([
            "correo"=>$correo,
            "cliente_id"=>$cliente_id,
      ]);

        return "";
    }
}
