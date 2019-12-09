<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use App\Empresa;
use App\Cliente;
use App\Servicio;
use App\Producto;
use App\Message;
use App\Task;
use App\TaskUser;
use App\Notification;
use App\ordenCompra;
use App\OrdenPago;
use App\Remito;
use App\Factura;
use App\Preventa;
use App\Proveedore;
use App\Recibo;
use App\Venta;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;

class HomeController extends Controller
{
  var $pusher;
 var $user;
 var $chatChannel;

 const DEFAULT_CHAT_CHANNEL = 'chat';

 public function __construct()
 {

     $this->chatChannel = self::DEFAULT_CHAT_CHANNEL;
 }

public function notificaciones(){
  if(Auth::check()){
    $id=auth()->user()->empresa_id;
  // $user=User::where('id',$id);
$notificaciones=Notification::where('empresa_id',$id)->get();

       return view('Notificaciones',compact('notificaciones'));
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
public function listacalendario(){
  if(Auth::check()){
    $id=auth()->user()->id;
  // $user=User::where('id',$id);
       $tasks=TaskUser::where('user_id',$id)->get();
       $usuarios=User::where('empresa_id',auth()->user()->empresa_id)->get();
      // $usuariosJ=collect($usuarios);
      // $usuariosJ=$usuariosJ->toArray();
       return view('ListaCalendario',compact('tasks','usuarios'));
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
 public function calendario(){
   if(Auth::check()){
     $id=auth()->user()->id;
     $tasks=TaskUser::where('user_id',$id)->get();

            return view('Calendario',compact('tasks'));

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
  public function chat(){
    if(Auth::check()){

         $usuarios=User::All();
         $mensajes=Message::All();
         return view('Chat',['chatChannel' => $this->chatChannel],compact('usuarios','mensajes'));
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

  public function ClientesAgenda(){
     if(Auth::check()){

        $clientes=Cliente::where('empresa_id',auth()->user()->empresa_id)->get();
        $servicios=Servicio::where('empresa_id',auth()->user()->empresa_id)->get();
       return view('ABMS/clientes',compact('clientes','servicios'));
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
  public function Serviciosini(){

    if(Auth::check()){

       $productos=Producto::where('empresa_id', auth()->user()->empresa_id)->get();
       $servicios=Servicio::where('empresa_id', auth()->user()->empresa_id)->get();
      return view('ABMS/servicios',compact('productos','servicios'));
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
    public function usuariosEmpresa(){
        if (Auth::check()) {
            $users=User::all();
            $roles=Role::all();
            $empresas=Empresa::all();

            return view('ABMS/usuariosE',compact('users','roles','empresas'));

        }else{
            $User = User::where('ip_client', \Request::ip())->first();
            if($User!=null){
                $profile_image=$User->profile_image;
                $email=$User->email;
                $name=$User->name;
                return view('lockscreen',['name' => $name,'profile'=>$profile_image,'email'=>$email]);
            }else{
                return view('/');
            }
        }
    }

 public function inicio(){
    if (Auth::check()) {

                $ordenCompras = ordenCompra::where('empresa_id',auth()->user()->empresa_id)->get();
                $ComprasAutorizadas = ordenCompra::where('empresa_id',auth()->user()->empresa_id)
                                                 ->where('estado','1')->get();
                $OrdenPagos = OrdenPago::where('empresa_id',auth()->user()->empresa_id)->get();
                $presupuestos = Preventa::where('empresa_id',auth()->user()->empresa_id)->get();
                $remitos = Remito::where('empresa_id',auth()->user()->empresa_id)->get();
                $remitosPendientes = Remito::where('empresa_id',auth()->user()->empresa_id)
                                           ->where('estado','Pendiente')->get();
                $facturas = Factura::where('empresa_id',auth()->user()->empresa_id)->get();
                $hoy = date('Ymd');
                $facturaDiaria = Factura::where('CbteFch',$hoy)->get();

                $year=date('Y');
                $month=date('m');
                $from = date($year.'-'.$month.'-01');
                $to = date($year.'-'.$month.'-t');
                $facturaMes=Factura::whereBetween('created_at', [$from, $to])
                ->where('empresa_id',auth()->user()->empresa_id)->get();
                $ventaMes=Venta::whereBetween('created_at', [$from, $to])
                ->where('empresa_id',auth()->user()->empresa_id)->get();
                $clientes = Cliente::where('empresa_id',auth()->user()->empresa_id)->get();
                $proveedores = Proveedore::where('empresa_id',auth()->user()->empresa_id)->get();
                $recibos = Recibo::where('empresa_id',auth()->user()->empresa_id)->get();
                $productos = Producto::where('empresa_id',auth()->user()->empresa_id)->get();
                $tareas = Task::where('empresa_id',auth()->user()->empresa_id)->get();
                $tareaHoy = date('Y-m-d');

                $tareaDiaria = Task::where('fecha_inicio','<=',$tareaHoy)
                                   ->where('fecha_fin','>=',$tareaHoy)
                                   ->where('empresa_id',auth()->user()->empresa_id)->get();

                return view('Dashboard',compact('ordenCompras','OrdenPagos','presupuestos','remitos','facturas','facturaDiaria','clientes',
                                                'proveedores','recibos','productos','tareas','tareaDiaria','ComprasAutorizadas','remitosPendientes'
                                                ,'facturaMes','ventaMes'));
    }else{
        return view('Home');
    }

 }

 public function empresa(){
    if (Auth::check()) {
        $empresas=Empresa::all();

        return view('ABMS/Empresa',compact('empresas'));
    }else{
        return view('Home');
    }
 }

 public function initsesion(){
    if (Auth::check()) {

      $ordenCompras = ordenCompra::where('empresa_id',auth()->user()->empresa_id)->get();
      $ComprasAutorizadas = ordenCompra::where('empresa_id',auth()->user()->empresa_id)
      ->where('estado','1')->get();
      $OrdenPagos = OrdenPago::where('empresa_id',auth()->user()->empresa_id)->get();
      $presupuestos = Preventa::where('empresa_id',auth()->user()->empresa_id)->get();
      $remitos = Remito::where('empresa_id',auth()->user()->empresa_id)->get();
      $remitosPendientes = Remito::where('empresa_id',auth()->user()->empresa_id)
      ->where('estado','Pendiente')->get();
      $facturas = Factura::where('empresa_id',auth()->user()->empresa_id)->get();


      $hoy = date('Ymd');
      $facturaDiaria = Factura::where('CbteFch',$hoy)->get();

      $year=date('Y');
      $month=date('m');
      $from = date($year.'-'.$month.'-01');
      $to = date($year.'-'.$month.'-t');
      $facturaMes=Factura::whereBetween('created_at', [$from, $to])
      ->where('empresa_id',auth()->user()->empresa_id)->get();
      $ventaMes=Venta::whereBetween('created_at', [$from, $to])
      ->where('empresa_id',auth()->user()->empresa_id)->get();

      $clientes = Cliente::where('empresa_id',auth()->user()->empresa_id)->get();
      $proveedores = Proveedore::where('empresa_id',auth()->user()->empresa_id)->get();
      $recibos = Recibo::where('empresa_id',auth()->user()->empresa_id)->get();
      $productos = Producto::where('empresa_id',auth()->user()->empresa_id)->get();
      $tareas = Task::where('empresa_id',auth()->user()->empresa_id)->get();
      $tareaHoy = date('Y-m-d');
      $tareaDiaria = Task::where('fecha_inicio','<=',$tareaHoy)
      ->where('fecha_fin','>=',$tareaHoy)
      ->where('empresa_id',auth()->user()->empresa_id)->get();

      return view('Dashboard',compact('ordenCompras','OrdenPagos','presupuestos','remitos','facturas','facturaDiaria','clientes',
      'proveedores','recibos','productos','tareas','tareaDiaria','ComprasAutorizadas','remitosPendientes','facturaMes','ventaMes'));

    }else{
        $User = User::where('ip_client', \Request::ip())->first();
        if($User!=null){
            $profile_image=$User->profile_image;
            $email=$User->email;
            $name=$User->name;
            return view('lockscreen',['name' => $name,'profile'=>$profile_image,'email'=>$email]);
        }else{
            return view('Home');
        }
    }
 }

 public function Usuarios(){
    if (Auth::check()) {

        $roles=Role::all();


        $users = User::where('empresa_id', auth()->user()->empresa_id)->get();
        return view('ABMS/Usuarios',compact('users','roles'));

    }else{
        $User = User::where('ip_client', \Request::ip())->first();
        if($User!=null){
            $profile_image=$User->profile_image;
            $email=$User->email;
            $name=$User->name;
            return view('lockscreen',['name' => $name,'profile'=>$profile_image,'email'=>$email]);
        }else{
            return view('/');
        }
    }
 }
 public function lockscreen(){
         return view('lockscreen');
 }

}
