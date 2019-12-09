<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notification;
use App\Correos;
use App\Cliente;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailable;

class NotificationController extends Controller
{
    public function saveNotification(Request $request){
         $titulo=$request->titulo;
         $fecha=$request->fecha;
         $hora=$request->hora;
         $mensaje=$request->mensaje;
         $empresa_id=auth()->user()->empresa_id;
         $control=$request->control;
         $tipo=$request->tipo;
         if($control==0){
           $notification=new Notification;
           $notification->titulo=$titulo;
           $notification->fecha=$fecha;
           $notification->hora=$hora;
           $notification->mensaje=$mensaje;
           $notification->empresa_id=$empresa_id;
           $notification->tipo=$tipo;
           $notification->status='Pendiente';
           $notification->save();
           if($notification->id>0){
             return $notification->id;
           }else{
             return 0;
           }
         }else{
           $notification=Notification::where('id',$control)->first();
           $notification->titulo=$titulo;
           $notification->fecha=$fecha;
           $notification->hora=$hora;
           $notification->mensaje=$mensaje;
           $notification->empresa_id=$empresa_id;
           $notification->tipo=$tipo;
           $notification->save();
           if($notification->id>0){
             return $notification->id;
           }else{
             return 0;
           }
         }

    }
    public function getNotification(Request $request){
      $id=$request->id;

      $notification=Notification::where('id',$id)->first();


      return response()->json($notification);
    }
    public function deleteNotification(Request $request){
         $id=$request->id;
         $notification=Notification::where('id',$id)->first();
         $notification->delete();

         return 1;
    }
    public function sendNotification(Request $request){
        $id=$request->id;
        $notificacion=Notification::where('id',$id)->first();
        $empresa_id=auth()->user()->empresa_id;
        $clientes=Cliente::where('empresa_id',$empresa_id)->get();

        foreach ($clientes as $cliente) {
          foreach ($cliente->correos as $correo) {
                 $name=$cliente->nombre;
                 $title=$notificacion->titulo;
                 $messagepro=$notificacion->mensaje;
                  Mail::to($correo->correo)->send(new SendMailable($name,$title,$messagepro));
          }
        }
        $notificacion->status='Enviada';
        $notificacion->save();

        return 0;
    }
}
