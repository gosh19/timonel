<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TaskUser;
use App\Task;
use App\User;
use App\Comentario;
class tareasController extends Controller
{
    public function check_tarea(Request $request){
      $id=$request->id;
      $task=Task::where('id',$id)->first();
      $task->status='Completada';
      $task->save();

      return 0;
    }
    public function delete_tarea(Request $request){
      $id=$request->id;
      $taskuser=TaskUser::where('task_id',$id)->get();
      $task=Task::where('id',$id)->first();
      foreach ($taskuser as $t) {
        $t->delete();
      }
      //$taskuser->delete();
      $task->delete();
       return 0;
    }
    public function save_tarea(Request $request){
      $status='pendiente';
      $prioridad=$request->prioridad;
      $descripcion=$request->descripcion;
      $tipo=$request->tipo;
      $titulo=$request->titulo;
      $asignador_id=auth()->user()->id;
      $fecha_inicio=$request->fecha_inicio;
      $fecha_fin=$request->fecha_fin;
      $hora_inicio=$request->hora_inicio;
      $hora_fin=$request->hora_fin;
      $color=$request->color;
      $empresa_id=auth()->user()->empresa_id;
      $usuarios=json_decode($request->get('usuarios'));
      $task=new Task;
      $task->color=$color;
      $task->status=$status;
      $task->prioridad=$prioridad;
      $task->descripcion=$descripcion;
      $task->tipo=$tipo;
      $task->titulo=$titulo;
      $task->asignador_id=$asignador_id;
      $task->fecha_inicio=$fecha_inicio;
      $task->fecha_fin=$fecha_fin;
      $task->hora_inicio=$hora_inicio;
      $task->hora_fin=$hora_fin;
      $task->empresa_id=$empresa_id;
      $task->save();
      if(count($usuarios)>=0){
        $cont=0;
         while ( $cont < count($usuarios)) {
               $item=new TaskUser;
               $item->task_id=$task->id;
               $item->user_id=$usuarios[$cont];
               $item->save();
               $cont++;
            }
      }
      $item=new TaskUser;
      $item->task_id=$task->id;
      $item->user_id=$asignador_id;
      $item->save();
      return 0;
    }
    public function get_tarea(Request $request){
          $task=Task::where('id',$request->id)->first();
          $usuario=User::where('id',$task->asignador_id)->first();
          $tarea='{
            "usuario":"'.$usuario->name.'",
            "titulo"        : "'.$task->titulo.'",
            "descripcion"   : "'.$task->descripcion.'",
            "comentarios"   :"'.$task->comentarios.'"
          }';

          return $task;
    }
    public function get_tareas(){
      $id=auth()->user()->id;

      $tasks=TaskUser::where('user_id',$id)->get();
      $events="";
      foreach ($tasks as $task) {
        $fecha_in = $task->task->fecha_inicio;
        $fecha_fin =$task->task->fecha_fin;

        $fecha_inY = date("Y", strtotime($fecha_in));
        $fecha_inM = date("m", strtotime($fecha_in));
        $fecha_inD = date("d", strtotime($fecha_in));

        $fecha_finY = date("Y", strtotime($fecha_fin));
        $fecha_finM = date("m", strtotime($fecha_fin));
        $fecha_finD = date("d", strtotime($fecha_fin));


           $events.='{
             title          : "'.$task->task->titulo.'",
             start          : "'.$fecha_inY.'/'.$fecha_inD.'/'.$fecha_inM.'",
             end            : "'.$fecha_finY.'/'.$fecha_finD.'/'.$fecha_finM.'",
             backgroundColor: "#f39c12",
             borderColor    : "#f39c12"
           },';
      }
      $events1 = substr($events,0, strlen($events) - 1);
      $events ='['.$events1.']';
      return response()->json($events);
    }

    public function actualizarAvance(Request $request){
        $task_id=$request->task_id;
        $task=Task::where('id',$task_id)->first();
        $task->avance=$request->avance;
        $task->save();

        $user_id=auth()->user()->id;
        //$task_id=$request->task_id;
        $empresa_id=auth()->user()->empresa_id;
        $comentario="Actualizo el avance de la tarea a ".$request->avance."%";

        $Comentario=new Comentario;
         $Comentario->user_id=$user_id;
         $Comentario->task_id=$task_id;
         $Comentario->comentario=$comentario;
         $Comentario->save();

        return 0;
    }
}
