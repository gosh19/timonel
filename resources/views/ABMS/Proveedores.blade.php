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
<script>
function limpiarForm(){
   $('#razon_social').val('');
   $('#cuit').val('');
   $('#direccion').val('');
   $('#telefono').val('');
   $('#correo').val('');
   $('#control').val('0');
}
function validarForm(){
var respuesta="";
  if($('#razon_social').val()=='')
       respuesta+="Debe Ingresar Razon Social \n";
  if($('#cuit').val()=='')
       respuesta+="Debe Ingresar CUIT \n";
  if($('#direccion').val()=='')
       respuesta+="Debe Ingresar Direccion \n";
  if($('#telefono').val()=='')
       respuesta+="Debe Ingresar Telefono \n";
  if($('#correo').val()=='')
       respuesta+="Debe Ingresar Correo \n";
  if(respuesta=="")
       respuesta="OK";

  return respuesta;
}
function table(){
  $('#tableProveedor').DataTable({
      'paging'      : true,
  'lengthChange': true,
  'searching'   : true,
  'ordering'    : true,
  'info'        : true,
  'autoWidth'   : true
   }

    );

}
$(document).ready(function(){
  $('.treeview').removeClass('active');
  $('#gesItem').addClass('active');
  $('#gesItem1').addClass('active');
  table();

  $('#saveOportunidades').click(function(){
       var validacion=validarForm();
       if(validacion!="OK"){
           swal("Error!", validacion, "error");
       }else{
        var razon_social= $('#razon_social').val();
        var telefono= $('#telefono').val();
        var direccion = $('#direccion').val();
        var correo = $('#correo').val();
        var cuit= $('#cuit').val();
        var control=$('#control').val();
        var data="razon_social="+razon_social+"&telefono="+telefono+"&direccion="+direccion+"&correo="+correo+"&cuit="+cuit+"&control="+control;

        $.ajaxSetup({
           headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             }
         });


        $.ajax({
            method: "post",
            data: data,
            url: "<?php echo route('agregar_proveedor')?>",
            success: function(response){ // What to do if we succeed
              swal("Buen Trabajo!", response, "success")
              .then((value) => {
                 location.reload();
                    });
                  },
                  error: function(xhr, status, error) {
                    var err = eval("(" + xhr.responseText + ")");
                     alert(err.Message);
                    }


              });

       }
  });


});
function editProveedor(id){
  limpiarForm();
  $.ajaxSetup({
              headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

              data="id="+id;
           $.ajax({
               method: "post",
               data: data,
               url: "<?php echo route('edit_proveedor')?>",
               success: function(response){ // What to do if we succeed
               loadData(response);


                     }
                 });

}
function loadData(Proveedor){
  limpiarForm();
  $('#razon_social').val(Proveedor.razon_social);
  $('#telefono').val(Proveedor.telefono);
  $('#direccion').val(Proveedor.direccion);
  $('#correo').val(Proveedor.correo);
  $('#cuit').val(Proveedor.cuit);
  $('#control').val(Proveedor.id);
  $('#modal-default').modal('show');
}
function deleteProveedor(id){

  swal({
      title: "Estas Seguro?",
      text: "Ten en cuenta que esto puede ocacionar un conflicto grave con los productos cargados con este Proveedor!",
      icon: "warning",
      buttons: ["Cancelar", "Borrar de Todas Formas!"],
      dangerMode: true,
       })
      .then((willDelete) => {
        if (willDelete) {
          $.ajaxSetup({
              headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

              data="id="+id;
           $.ajax({
               method: "post",
               data: data,
               url: "<?php echo route('delete_proveedor')?>",
               success: function(response){ // What to do if we succeed
                 swal("Buen Trabajo!", "Se elimino Correctamente!", "success")
                 .then((value) => {
                    location.reload();
                       });

                     }
                 });

        } else {
          swal("El proveedor y sus Productos estan a Salvo!");
        }
      });

}
</script>
<div class="callout callout-warning">
        <h4>Recuerda!</h4>
        Ante cualquier error contactar inmediatamente al Administrador del Sistema, el CMR esta en fase de desarrollo.

</div>

<section class="content-header">
      <h1>
        Administracion
        <small>Proveedores</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-laptop"></i> Administracion</a></li>
        <li class="active">Proveedores</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
                <br>
              <h3 class="box-title">Registro de Proveedores</h3>
            </div>

            <!-- /.box-header -->
            <div class="box-body">
              <button type="button" onClick="limpiarForm();" class="btn btn-default" data-toggle="modal" data-target="#modal-default">
                  Ingresar Proveedor
                </button>
              <br>
              <br>
              <table id="tableProveedor" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>#id</th>
                  <th>Nombre</th>
                  <th>Direccion</th>
                  <th>Telefono</th>
                  <th>Correo</th>
                  <th>Cuit</th>
                  <th>Accion</th>
                </tr>
                </thead>
                <tbody>
                  @foreach($proveedores as $item)
                    <tr>
                       <th>{{$item->id}}</th>
                       <th>{{$item->razon_social}}</th>
                       <th>{{$item->direccion}}</th>
                       <th>{{$item->telefono}}</th>
                       <th>{{$item->correo}}</th>
                       <th>{{$item->cuit}}</th>
                       <th>
                       <a href="javascript:editProveedor({{$item->id}});"><button class="btn btn-info"><i class="fa fa-edit"></i></button></a>
                       <a href="javascript:deleteProveedor({{$item->id}});"><button class="btn btn-danger"><i class="fa fa-eraser"></i></button></a>

                       </th>
                    </tr>
                  @endforeach
                </tbody>
                <tfoot>
                <tr>
                  <th>#id</th>
                  <th>Nombre</th>
                  <th>Direccion</th>
                  <th>Telefono</th>
                  <th>Correo</th>
                  <th>Cuit</th>
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
                <h4 class="modal-title">ABM Proveedores</h4>
              <div class="modal-body">
                 <form role="form" id="formuser">
                 {{csrf_field()}}
                    <div class="box-body">
                       <div class="row">
                        <div class="form-group col-lg-6 col-xl-12">
                        <input id="control" type="hidden" value="0">
                        <input id="token" type="hidden" value="{{csrf_token()}}">
                            <label for="exampleInputEmail1">Razon Social</label>
                            <input type="text" class="form-control" id="razon_social" placeholder="Ingrese Nombre">
                        </div>
                        <div class="form-group col-lg-6 col-xl-12">
                            <label for="exampleInputEmail1">Telefono</label>
                            <input type="text" class="form-control" id="telefono" placeholder="Ingrese Telefono">
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-group col-lg-6 col-xl-12">
                            <label for="exampleInputPassword1">Direccion</label>
                            <input type="text" class="form-control" id="direccion" placeholder="Ingrese Dirección">
                        </div>
                        <div class="form-group col-lg-6 col-xl-12">
                            <label for="descripcion">Correo Electronico</label>
                            <input type="email" class="form-control" id="correo" placeholder="Ingreso Correo Electronico">
                        </div>
                      </div>

                      <div class="row">
                        <div class="form-group col-lg-6 col-xl-12">
                            <label>Ingrese Cuit</label>
                            <input class="form-control" type="text" id="cuit" placeholder="Sin guiones">
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
                <button type="button" id="saveOportunidades" class="btn btn-primary">Guardar Cambios</button>
              </div>
              </form>
            </div>
          </div>
    </div>


@endsection
