@extends( auth()->user()->id_categoria == 1 ? 'Layout/_LayoutSU' : 'Layout/_Layout')

@section('ProfiImage')
{{ auth()->user()->profile_image }}
@endsection

@section('empresaUth')
{{auth()->user()->empresa->razon_social}}
@endsection

@section('descripcion_puesto')
   {{auth()->user()->descripcion_puesto}}
@endsection

@section('namesidebar')
   {{ auth()->user()->name }}
@endsection

@section('wrapper')
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Latest compiled and minified CSS -->

<script type="text/javascript">
function checkTarea(id){
   var data= "id="+id;
   $.ajaxSetup({
     headers: {
       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
     });
 $.ajax({
    method: "post",
    data: data,
    url: "<?php echo route('check_tarea')?>",
    success: function(response){ // What to do if we succeed
        //alert(response.id_last);
     swal("Buen Trabajo!", "Tarea Finalizada Con Exito!", "success")
     .then((value) => {
        location.reload();
           });
     }
  });
}
function deleteTarea(id){
  var data="id="+id;
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
     }
    });
$.ajax({
   method: "post",
   data: data,
   url: "<?php echo route('delete_tarea')?>",
   success: function(response){ // What to do if we succeed
       //alert(response.id_last);
    swal("Buen Trabajo!", "Tarea eliminada Con Exito!", "success")
    .then((value) => {
       location.reload();
          });
    }
 });
}
function verDescripcion(id){
  $.ajaxSetup({
     headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
       }
   });
   var data="id="+id;
  $.ajax({
      method: "post",
      data: data,
      url: "<?php echo route('get_tarea')?>",
      success: function(response){
           $('#task_id').val(response.id);
           $('#tituloV').val(response.titulo);
           $('#descripcionV').val(response.descripcion);
           $('#avance').val(response.avance);
           $('#modal-ver').modal('show');
           var coments='';
           @foreach($usuarios as $usuario)
                  var u={{$usuario->id}};
                  var ul=response.asignador_id;
                  if(u==ul){
                     $('#asignador_idV').val('{{$usuario->name}}');
                  }
           @endforeach
         var comentarios=response.comentarios;
           for(var item in response.comentarios) {
              var user_id=response.comentarios[item]['user_id'];
              @foreach($usuarios as $usuario)
                    var u={{$usuario->id}};
                    var hora=response.comentarios[item]['created_at'];
                    if(u==user_id){
                      coments+='<div class="item">'+
                          '<img src="{{$usuario->profile_image}}" alt="user image" class="offline">'+
                          '<p class="message">'+
                            '<a href="#" class="name">'+
                              '<small class="text-muted pull-right"><i class="fa fa-clock-o"></i>'+hora+'</small>'+
                              '{{$usuario->name}}'+
                            '</a>'+response.comentarios[item]['comentario']+
                          '</p>'+
                        '</div>';
                    }
               @endforeach
          }
           $('#chat-box').html(coments);
       }
   });
}
  $(document).ready(function(){

        $('#usuarios').select2();
        $('#saveTarea').click(function(){
           var tipo=$('#tipo').val();
           var prioridad=$('#prioridad').val();
           var descripcion=$('#descripcion').val();
           var titulo=$('#titulo').val();
           var fecha_inicio=$('#fecha_inicio').val();
           var fecha_fin=$('#fecha_fin').val();
           var hora_inicio=$('#hora_inicio').val();
           var hora_fin=$('#hora_fin').val();
           var usuarios=$('#usuarios').val();
           var color=$('#listColor').val();

           var usuariosAr=JSON.stringify(usuarios);
           var data="tipo="+tipo+"&prioridad="+prioridad+"&descripcion="+descripcion+"&titulo="+titulo+"&fecha_inicio="+
           fecha_inicio+"&fecha_fin="+fecha_fin+"&hora_inicio="+hora_inicio+"&hora_fin="+hora_fin+"&usuarios="+usuariosAr+"&color="+color;

           $.ajaxSetup({
             headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
             });
         $.ajax({
            method: "post",
            data: data,
            url: "<?php echo route('save_tarea')?>",
            success: function(response){ // What to do if we succeed
                //alert(response.id_last);
             swal("Buen Trabajo!", "Tarea Cargada Con Exito!", "success")
             .then((value) => {
                location.reload();
                   });
             }
          });
        });

        $('#cambiar_avance').click(function(){
          var avance=$('#avance').val();
          var task_id=$('#task_id').val();

          var data="avance="+avance+"&task_id="+task_id;
          $.ajax({
             method: "post",
             data: data,
             url: "<?php echo route('actualizarAvance')?>",
             success: function(response){ // What to do if we succeed
                 //alert(response.id_last);
              swal("Buen Trabajo!", "Avance Actualizado!", "success")
              .then((value) => {
                 location.reload();
                    });
              }
           });
        });

        $('#add_comentario').click(function(){
          var comentario=$('#comentario').val();
          var task_id=$('#task_id').val();

          var data="comentario="+comentario+"&task_id="+task_id;
          $.ajax({
             method: "post",
             data: data,
             url: "<?php echo route('createComentario')?>",
             success: function(response){
             var hora='Ahora';
              var coment='<div class="item">'+
                   '<img src="{{auth()->user()->profile_image}}" alt="user image" class="offline">'+
                   '<p class="message">'+
                     '<a href="#" class="name">'+
                       '<small class="text-muted pull-right"><i class="fa fa-clock-o"></i>'+hora+'</small>'+
                       '{{auth()->user()->name}}'+
                     '</a>'+response+
                   '</p>'+
                 '</div>';

                 $('#chat-box').append(coment);
                 $('#comentario').val('');
              }
           });
        });
  });
</script>
<style media="screen">
  .select2-container--default .select2-selection--multiple .select2-selection__choice {
    color:black;
  }
</style>
<section class="content-header">
      <h1>
        Tareas
        <small>Lista de Tareas</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#">Tareas</a></li>
        <li class="active">Lista de Tareas</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
                <br>
              <h3 class="box-title"> Lista de Tareas</h3>
            </div>

            <!-- /.box-header -->
            <div class="box-body">
            <button type="button" onClick="" class="btn btn-default" data-toggle="modal" data-target="#modal-default">
                Agregar Tarea
              </button>
              <!--More contentMDF-->
              <div class="box-header">
                  <br>
                <h3 class="box-title"><i class="fas fa-thumbtack"></i>  Tareas Personales</h3>
                <ol class="breadcrumb">
                  <?php
                  $pendientes=0;
                   foreach ($tasks as $task) {
                     if($task->task->status=='pendiente' && $task->task->tipo=='personal'){
                          $pendientes++;
                     }
                   }
                  ?>
                  <li class="active">Pendientes: {{$pendientes}}</li>
                </ol>

                <hr>
              </div>
              <table class="table table-bordered table-striped">
                <tbody>
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Usuario</th>
                      <th>Titulo</th>
                      <th>Prioridad</th>
                      <th>Fecha</th>
                      <th>Hora</th>
                      <th>Avance</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>

               @foreach($tasks as $task)
                     @if($task->task->asignador_id==auth()->user()->id && $task->task->status=='pendiente' && $task->task->tipo=='personal')
                     <tr>
                       <th><input type="checkbox"></th>
                       @foreach($usuarios as $usuario)
                           @if($usuario->id==$task->task->asignador_id)
                             <th><img src="{{$usuario->profile_image}}" class="img-circle" alt="User Image" style="width:40px;">{{$usuario->name}} </th>
                           @endif
                       @endforeach
                       <th>{{$task->task->titulo}}</th>
                       <th>
                            @if($task->task->prioridad=='alta')
                               <span class="badge badge-pill badge-red bg-red">Alta</span>
                            @endif
                            @if($task->task->prioridad=='baja')
                               <span class="badge badge-pill badge-blue bg-blue">Baja</span>
                            @endif
                            @if($task->task->prioridad=='media')
                                <span class="badge badge-pill badge-yellow bg-yellow">Media</span>
                            @endif
                       </th>
                       <th>{{$task->task->fecha_inicio}} - {{$task->task->fecha_fin}}</th>
                       <th>{{$task->task->hora_inicio}} - {{$task->task->hora_fin}}</th>
                       <th>{{$task->task->avance}}%</th>
                       <th>
                         <a href="javascript:verDescripcion({{$task->task->id}});"><button class="btn btn-primary"><i class="far fa-eye"></i></button></a>
                         <a href="javascript:checkTarea({{$task->task->id}});"><button class="btn btn-success"><i class="fas fa-check-square"></i></button></a>
                         <a href="javascript:deleteTarea({{$task->task->id}});"><button class="btn btn-danger"><i class="fa fa-eraser"></i></button></a>
                       </th>
                     </tr>
                     @endif

               @endforeach

               </tbody>
             </table>
              <hr>
              <br>
              <div class="box-header">
                  <br>
                <h3 class="box-title"><i class="fas fa-thumbtack"></i>  Tareas Asignadas</h3>
                <ol class="breadcrumb">
                  <?php
                  $pendientesG=0;
                   foreach ($tasks as $task) {
                     if($task->task->status=='pendiente' && $task->task->tipo=='grupal'){
                          $pendientesG++;
                     }
                   }
                  ?>
                  <li class="active">Pendientes: {{$pendientesG}}</li>
                </ol>

                <hr>
              </div>
              <table class="table table-bordered table-striped">
                <tbody>
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Usuario</th>
                      <th>Titulo</th>
                      <th>Prioridad</th>
                      <th>Fecha</th>
                      <th>Hora</th>
                      <th>Avance</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  @foreach($tasks as $task)
                        @if($task->task->tipo=='grupal' && $task->task->status=='pendiente')
                        <tr>
                          <th><input type="checkbox"></th>
                          @foreach($usuarios as $usuario)
                              @if($usuario->id==$task->task->asignador_id)
                                <th><img src="{{$usuario->profile_image}}" class="img-circle" alt="User Image" style="width:40px;"> {{$usuario->name}} </th>
                              @endif
                          @endforeach
                          <th>{{$task->task->titulo}}</th>
                          <th>
                               @if($task->task->prioridad=='alta')
                                  <span class="badge badge-pill badge-red bg-red">Alta</span>
                               @endif
                               @if($task->task->prioridad=='baja')
                                  <span class="badge badge-pill badge-blue bg-blue">Baja</span>
                               @endif
                               @if($task->task->prioridad=='media')
                                   <span class="badge badge-pill badge-yellow bg-yellow">Media</span>
                               @endif
                          </th>
                          <th>{{$task->task->fecha_inicio}} - {{$task->task->fecha_fin}}</th>
                          <th>{{$task->task->hora_inicio}} - {{$task->task->hora_fin}}</th>
                          <th>{{$task->task->avance}}%</th>
                          <th>
                            <a href="javascript:verDescripcion({{$task->task->id}});"><button class="btn btn-primary"><i class="far fa-eye"></i></button></a>
                            <a href="javascript:checkTarea({{$task->task->id}});"><button class="btn btn-success"><i class="fas fa-check-square"></i></button></a>
                             @if($task->task->asignador_id==auth()->user()->id)
                             <a href="javascript:deleteTarea({{$task->task->id}});"><button class="btn btn-danger"><i class="fa fa-eraser"></i></button></a>
                             @endif
                          </th>
                        </tr>


                        @endif
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>

    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="modal-ver">
         <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" >Detalles de la tarea.</h4>
              </div>
              <div class="modal-body">
                <div class="container-fluid">
                <div class="col-sm-6 col-xl-6">
                <input type="hidden" id="task_id" name="task_id" value="">
                <div class="row">
                    <div class="col-sm-12 col-xl-6">
                        <label for="asignador_idV">Usuario Asignador</label>
                        <input class="form-control" type="text" id="asignador_idV" disabled>
                    </div>
                    <div class="col-sm-12 col-xl-6">
                      <label for="tituloV">Titulo de la Tarea</label>
                      <input id="tituloV" class="form-control"  disabled>
                    </div>
                </div>
                <div class="row">
                   <div class="col-sm-12 col-xl-12">
                     <label for="descripcionV"></label>
                        <label for="descripcionV">Descripcion de la tarea:</label>
                        <input type="text" class="form-control" id="descripcionV" name="descripcionV" placeholder="Descripcion de la tarea">
                   </div>
                </div>
              </diV>
                <div class="col-sm-6 col-xl-6">
                     <div class="box box-success">
                       <div class="box-header">
                         <i class="far fa-comments"></i>
                         <h3 class="box-title">comentarios</h3>
                         <div class="box-tools pull-right" data-toggle="tooltip" title="Status">
                         </div>
                       </div>
                       <div class="box-body chat" id="chat-box" style="height: 200px;overflow-y:scroll;;">
                       </div>
                       <div class="box-footer">
                         <div class="input-group">
                           <input class="form-control" placeholder="Escribir un Comentario" id="comentario">
                           <div class="input-group-btn">
                             <button type="button" id="add_comentario" class="btn btn-success"><i class="fa fa-plus"></i></button>
                           </div>
                         </div>
                       </div>
                     </div>
              </div>
            </div>
              </div>

              <div class="modal-footer">
                <div class="container-fluid">
                  <div class="col-sm-6 col-xl-6">
                     <select class="form-control" name="form-control" id="avance">
                       <option value="0">0%</option>
                       <option value="10">10%</option>
                       <option value="20">20%</option>
                       <option value="30">30%</option>
                       <option value="40">40%</option>
                       <option value="50">50%</option>
                       <option value="60">60%</option>
                       <option value="70">70%</option>
                       <option value="70">80%</option>
                       <option value="90">90%</option>
                       <option value="100">100%</option>
                     </select>
                   </div>
                   <div class="col-sm-6 col-xl-6">
                     <button type="button" id="cambiar_avance" class="btn btn-primary" name="button">Cambiar Avance</button>
                   </div>
                </div>
              </div>
            </div>
          </div>
        </div>


      <div class="modal fade" id="modal-default">
            <div class="modal-dialog bs-example-modal modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title">ABM de Tareas</h4>
                <div class="modal-body">
                   <form role="form" id="formuser">
                   {{csrf_field()}}
                      <div class="box-body">
                        <div class="row">
                          <div class="form-group col-lg-6 col-xl-12">
                              <label>Tipo de Tarea </label>
                              <select class="form-control" id="tipo" >
                              <option value="personal">Personal</option>
                              <option value="grupal">Asignar Tarea</option>
                              </select>
                          </div>
                          <div class="form-group col-lg-6 col-xl-12">
                              <label>Prioridad alta</label>
                              <select class="form-control" id="prioridad" >
                              <option value="alta">Alta</option>
                              <option value="baja">Baja</option>
                              <option value="media">Media</option>
                              </select>
                          </div>
                        </div>

                        <div class="row">
                          <div class="form-group col-lg-6 col-xl-12">
                              <label for="exampleInputPassword1">Fecha Inicio</label>
                              <input type="date" class="form-control" id="fecha_inicio" >
                          </div>
                         <div class="form-group col-lg-6 col-xl-12">
                             <label for="exampleInputEmail1">Fecha Fin</label>
                             <input type="date" class="form-control" id="fecha_fin" >
                         </div>
                       </div>
                       <div class="row">
                         <div class="form-group col-lg-6 col-xl-12">
                             <label for="exampleInputPassword1">Hora Inicio</label>
                             <input type="time" class="form-control" id="hora_inicio" >
                         </div>
                        <div class="form-group col-lg-6 col-xl-12">
                            <label for="exampleInputEmail1">Hora Fin</label>
                            <input type="time" class="form-control" id="hora_fin" >
                        </div>
                      </div>
                      <br>
                        <div class="row">
                             <div class="col-xl-12 col-sm-12">
                               <label for="usuarios">Asignar Usuarios</label><br>
                               <select style="width:100%;color:black;" id="usuarios" class="js-example-basic-multiple" name="states[]" multiple="multiple">
                                 @foreach($usuarios as $usuario)
                                 <option style="color:black;" value="{{$usuario->id}}">{{$usuario->name}}</option>
                                 @endforeach
                               </select>
                             </div>
                        </div>
                        <br>
                       <div class="row">
                         <div class="form-group col-lg-6 col-xl-12">
                         <input id="control" type="hidden" value="0">
                         <input id="token" type="hidden" value="{{csrf_token()}}">
                             <label for="exampleInputEmail1">Titulo</label>
                             <input type="text" class="form-control" id="titulo" placeholder="Titulo">
                         </div>
                         <div class="form-group col-lg-6 col-xl-12">
                             <label for="descripcion">Descripcion</label>
                             <input type="text" class="form-control" id="descripcion" name="descripcion" placeholder="Descripcion de la tarea">
                         </div>
                       </div>
                       <div class="row">
                         <div class="form-group col-lg-6 col-xl-12">
                             <label for="">Color de la tarea.</label>
                             <input type="color" list="color" id="listColor">
                             <datalist id="color">
                               <option value="#FF3562">#FF3562</option>
                               <option value="#693668">#693668</option>
                               <option value="#546BCE">#546BCE</option>
                               <option value="#65C6A4">#65C6A4</option>
                             </datalist>
                         </div>
                       </div>


                          <!--div class="form-group">
                             <label for="exampleInputFile">Imagen de Perfil</label>
                             <input type="file" id="profile_image">
                             <p class="help-block">Imagen que no supere 2 MB.</p>
                          </div-->

                      </div>
                      </div>
                      </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                  <button type="button" id="saveTarea" class="btn btn-primary">Guardar Cambios</button>
                </div>
                </form>
              </div>
            </div>
      </div>

@endsection
