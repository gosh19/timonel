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
<script type="text/javascript">
  function sendNotification(id){
    $('#modal-spinner').modal({backdrop: 'static', keyboard: false});
    $('#modal-spinner').modal('show');
    $.ajaxSetup({
       headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         }
     });
     var data="id="+id;
    $.ajax({
      method: "post",
      data: data,
      url: "<?php echo route('sendNotification')?>",
      success: function(html){
        swal('Operación Exitosa.','Notificación enviadas con exitos','success')
        .then((value) => {
           location.reload();
              });
      },
      complete: function(){
        $('#modal-spinner').modal('hide');
      }
    });
  }
  function setTipo(){
       var tipo=$('#tipo').val();
       if(tipo=='Eventual'){
           $('#fecha').attr('disabled',true);
           $('#hora').attr('disabled',true);
           $('#fecha').val('');
           $('#hora').val('');
       }else{
         $('#fecha').attr('disabled',false);
         $('#hora').attr('disabled',false);
       }
  }
  function limpiarform(){
      $('#titulo').val('');
      $('#fecha').val('');
      $('#hora').val('');
      $('#mensaje').val('');
  }
  function validarform(){
    var respuesta="";
    if($('#titulo').val()==""){
      respuesta+="Falta Agregar titulo\n";
    }
    if($('#tipo').val()!='Eventual'){

      if($('#fecha').val()==""){
        respuesta+="Falta Agregar Fecha\n";
      }
      if($('#hora').val()==""){
        respuesta+="Falta Agregar Hora\n";
      }
    }
    if($('#mensaje').val()==""){
      respuesta+="Falta Agregar Mensaje \n";
    }
    if(respuesta==''){
       respuesta="OK";
    }
    return respuesta;
  }
  function editNotification(id){
    $.ajaxSetup({
       headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         }
     });
     var data="id="+id;
    $.ajax({
        method: "post",
        data: data,
        url: "<?php echo route('getNotification')?>",
        success: function(response){ // What to do if we succeed
           cargarNotification(response);
         }
     });
  }
  function cargarNotification(notificacion){
    alert(notificacion.titulo);
    $('#titulo').val(notificacion.titulo);
    if(notificacion.tipo=='Eventual'){
      $('#fecha').attr('disabled',true);
      $('#hora').attr('disabled',true);
      $('#fecha').val('');
      $('#hora').val('');
    }else{
      $('#fecha').attr('disabled',false);
      $('#hora').attr('disabled',false);
      $('#fecha').val(notificacion.fecha);
      $('#hora').val(notificacion.hora);
    }
    $('#control').val(notificacion.id);
    $('#mensaje').val(notificacion.mensaje);
    $('#tipo').val(notificacion.tipo);
    $('#modal-default').modal('show');
  }
  function table(){
    $('#tableNotificaciones').DataTable({
        'paging'      : true,
    'lengthChange': true,
    'searching'   : true,
    'ordering'    : true,
    'info'        : true,
    'autoWidth'   : true
     });
  }
  function deleteNotification(id){
    swal({
        title: "¿Estas Seguro?",
        text: "Los datos no se podran recuperar!",
        icon: "warning",
        buttons: ["Cancelar", "Eliminar Notificación!"],
        dangerMode: true,
         })
        .then((willDelete) => {
          if (willDelete) {

        $.ajaxSetup({
           headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             }
         });
         var data="id="+id;
        $.ajax({
            method: "post",
            data: data,
            url: "<?php echo route('deleteNotification')?>",
            success: function(response){ // What to do if we succeed
              swal("Buen Trabajo!", "Notificación Eliminada con exito!", "success")
              .then((value) => {
                 location.reload();
                    });
             }
         });
          } else {
            swal("Notificación a Salvo!");
          }
        });

  }
  $(document).ready(function(){
    // Replace the <textarea id="editor1"> with a CKEditor
   // instance, using default configuration.
  // CKEDITOR.replace('editor1')
   //bootstrap WYSIHTML5 - text editor
    $('#editor').wysihtml5()
    $('.treeview').removeClass('active');
    $('#cmrItem').addClass('active');
    $('#cmrItem2').addClass('active');
    table();
    $(document).ready(function(){
      $('#saveNotificacion').click(function(){
         var validacion=validarform();
         if(validacion=="OK"){
           var tipo=$('#tipo').val();
           var hora=$('#hora').val();
           var fecha=$('#fecha').val();
           var titulo=$('#titulo').val();
           var mensaje=$('#editor').val();
           var control=$('#control').val();
           var data="tipo="+tipo+"&hora="+hora+"&fecha="+fecha+"&titulo="+titulo+"&mensaje="+mensaje+"&control="+control;
           $.ajaxSetup({
              headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
           $.ajax({
               method: "post",
               data: data,
               url: "<?php echo route('saveNotification')?>",
               success: function(response){ // What to do if we succeed
                 swal("Buen Trabajo!", "Notificación Cargada con exito!", "success")
                 .then((value) => {
                    location.reload();
                       });
                }
            });
         }else{
           swal("Error!", validacion, "error");
         }
      });
    });
  });
</script>
<div class="callout callout-warning">
        <h4>Recuerda!</h4>
        Ante cualquier error contactar inmediatamente al Administrador del Sistema, el CMR esta en fase de desarrollo.

</div>

<section class="content-header">
      <h1>
        Notificaciones
        <small>ABM Notificaciones</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-laptop"></i> Notificaciones</a></li>
        <li class="active">ABM Notificaciones</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
                <br>
              <h3 class="box-title">Panel Notificaciones</h3>
            </div>

            <!-- /.box-header -->
            <div class="box-body">
              <button type="button" onClick="limpiarform();" class="btn btn-default" data-toggle="modal" data-target="#modal-default">
                  Agregar Notificacion
                </button>
              <br>
              <br>
              <table id="tableNotificaciones" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>#id</th>
                  <th>Titulo</th>
                  <th>Fecha</th>
                  <th>Hora</th>
                  <th>Status</th>
                  <th>Tipo</th>
                  <th>Accion</th>
                </tr>
                </thead>
                <tbody>
                  @foreach($notificaciones as $item)
                    <tr>
                       <th>{{$item->id}}</th>
                       <th>{{$item->titulo}}</th>
                       <th>{{$item->fecha}}</th>
                       <th>{{$item->hora}}</th>
                       @if($item->status=='Pendiente')
                         <th><span class="badge badge-pill badge-warning">{{$item->status}}</span></th>
                       @else
                         <th><span class="badge badge-pill badge-success">{{$item->status}}</span></th>
                       @endif
                       <th>{{$item->tipo}}</th>
                       <th>
                       <a href="javascript:editNotification({{$item->id}});"><button class="btn btn-info"><i class="fa fa-edit"></i></button></a>
                       <a href="javascript:deleteNotification({{$item->id}});"><button class="btn btn-danger"><i class="fa fa-eraser"></i></button></a>
                         @if($item->tipo=='Eventual')
                         <a href="javascript:sendNotification({{$item->id}});"><button class="btn btn-primary"><i class="far fa-paper-plane"></i></button></a>
                         @endif
                      </th>
                    </tr>
                  @endforeach
                </tbody>
                <tfoot>
                <tr>
                  <th>#id</th>
                  <th>Titulo</th>
                  <th>Fecha</th>
                  <th>Hora</th>
                  <th>Status</th>
                  <th>Tipo</th>
                  <th>Accion</th>
                </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>


    <div class="modal fade" id="modal-default">
          <div class="modal-dialog bs-example-modal modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">ABM Notificaciones</h4>
              <div class="modal-body" style="height: 400px;overflow-y:scroll;">
                 <form role="form" id="formuser">
                 {{csrf_field()}}
                    <div class="box-body">
                      <div class="row">
                        <div class="form-group col-lg-6 col-xl-12">
                            <label>Tipo de Notificación</label>
                            <select class="form-control" id="tipo" onchange="setTipo();">
                            <option value="Eventual">Eventual</option>
                            <option value="Programada">Programada</option>
                            </select>
                        </div>
                        <div class="form-group col-lg-6 col-xl-12">
                        <input id="control" type="hidden" value="0">
                        <input id="token" type="hidden" value="{{csrf_token()}}">
                            <label for="exampleInputEmail1">Titulo</label>
                            <input type="text" class="form-control" id="titulo" placeholder="Titulo">
                        </div>
                      </div>

                      <div class="row">
                        <div class="form-group col-lg-6 col-xl-12">
                            <label for="exampleInputPassword1">Hora</label>
                            <input type="time" class="form-control" id="hora" disabled>
                        </div>
                       <div class="form-group col-lg-6 col-xl-12">
                           <label for="exampleInputEmail1">Fecha</label>
                           <input type="date" class="form-control" id="fecha" disabled>
                       </div>
                     </div>

          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Mensaje
                <small>Diseña tu mensaje</small>
              </h3>
              <!-- tools box -->
              <div class="pull-right box-tools">
                <button type="button" class="btn btn-default btn-sm" data-widget="collapse" data-toggle="tooltip"
                        title="Collapse">
                  <i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-default btn-sm" data-widget="remove" data-toggle="tooltip"
                        title="Remove">
                  <i class="fa fa-times"></i></button>
              </div>
              <!-- /. tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body pad">
              <form>
                <textarea class="textarea" id="editor" placeholder="Place some text here"
                          style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
              </form>
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
                <button type="button" id="saveNotificacion" class="btn btn-primary">Guardar Cambios</button>
              </div>
              </form>
            </div>
          </div>
    </div>
    <div class="modal fade" id="modal-spinner">
          <div class="modal-dialog bs-example-modal modal-sm">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title" style="text-align:center;">Enviando Notificación</h4>
              <div class="modal-body">
                <div class="col-sm-12 col-xl-4 col-offset-4">
                   <img style="align:center;" src="../../public/dist/Spinner.gif">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
@endsection
