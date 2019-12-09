<?php

namespace App\Http\Controllers;
use App\Telefono;
use Illuminate\Http\Request;

class TelefonoController extends Controller
{
    public function Add(Request $request){
         $telefono=$request->telefono;
         $referencia=$request->referencia;
         $cliente_id=$request->cliente_id;

         Telefono::insert([
              "telefono"=>$telefono,
              "referencia"=>$referencia,
              "cliente_id"=>$cliente_id,
         ]);
         return "";
    }
}
