<?php

namespace App\Http\Controllers;

use App\Comentario;
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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;

class ComentarioController extends Controller
{
    public function createComentario(Request $request){
      $user_id=auth()->user()->id;
      $task_id=$request->task_id;
      $empresa_id=auth()->user()->empresa_id;
      $comentario=$request->comentario;

      $Comentario=new Comentario;
       $Comentario->user_id=$user_id;
       $Comentario->task_id=$task_id;
       $Comentario->comentario=$comentario;
       $Comentario->save();
       return $comentario;
    }
}
