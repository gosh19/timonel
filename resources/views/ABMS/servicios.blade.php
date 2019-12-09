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
function table(){
  $('#tableServicios').DataTable({
      'paging'      : true,
  'lengthChange': true,
  'searching'   : true,
  'ordering'    : true,
  'info'        : true,
  'autoWidth'   : true

   }

    );
    $('#tableProductos').DataTable({
        'paging'      : true,
    'lengthChange': true,
    'searching'   : true,
    'ordering'    : true,
    'info'        : true,
    'autoWidth'   : false,
  "columnDefs": [ {
            orderable: false,
            className: 'select-checkbox',
            targets:   0
        } ],
        "select": {
            'style': 'multi'
        },
        "order": [[ 1, 'asc' ]]
     }

   );

}
$(document).ready(function(){
  $('.treeview').removeClass('active');
  $('#gesItem').addClass('active');
  $('#gesItem3').addClass('active');
   $(".wrapper").css("overflow-y","hidden");
  table();
  $('#addProducto').click(function(){
    $('#modal-productos').modal({backdrop: 'static', keyboard: false});
      resetTablePro();

      $('#modal-default').modal('hide');
      $('#modal-productos').modal('show');

  });
  $('#cerrarProductos').click(function(){
    $('#modal-productos').modal('hide');
    $('#modal-default').modal('show');
  });
  $('#saveServicio').click(function(){
    var validacion=validarform();
    if(validacion=="OK"){
            //ajax para enviar Servicio
     var productosSe=[];
     var cantidadesE=[];
    $('#productosAgregados tr').each(function(){
             var id=$(this).find("td").eq(0).html();
             var cantidad=$(this).find("td").eq(3).find("input").val();
             cantidadesE.push(cantidad);
             productosSe.push(id);
    });
        var produ=JSON.stringify(productosSe);
        var cantidades=JSON.stringify(cantidadesE);
        var nombre=$('#nombre').val()
        var descripcion=$('#descripcion').val();
        var valor_publico=$('#valor_publico').val();
        var valor_cambio = $('#valor_cambio').val();
        var costo=$('#costo').val();
        var tipo=$('#tipo').val();
        var control=$('#control').val();
          var data="valor_cambio="+valor_cambio+"&control="+control+"&cantidades="+cantidades+"&productos="+produ+"&nombre="+nombre+"&descripcion="+descripcion+"&valor_publico="+valor_publico+"&costo="+costo+"&tipo="+tipo;

          $.ajaxSetup({
             headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               }
           });
            $.ajax({
                method: "post",
                data: data,
                url: "<?php echo route('agregar_servicio')?>",
                success: function(response){ // What to do if we succeed
                  swal("Buen Trabajo!", response, "success")
                  .then((value) => {
                     location.reload();
                        });
                      }

                  });
    }else{
      swal('Error!',validacion,'error');
    }
  });
  $("#productosAgregados").on('click', '.btnDelete', function () {
    $(this).closest('tr').remove();
  });
  $('#saveProducto').click(function(){

     var table=$('#tableProductos').DataTable();

      //productosAgregados Nombre de la tabla para Agregar Productos seleccionados
          var newRow="";
          //$('#totalServicio').val("");
          $.map(table.rows('.selected').data(), function (item) {
                  newRow+="<tr>";
                     newRow+="<td>"+item[1]+"</td>";
                     newRow+="<td>"+item[3]+"</td>";
                     newRow+="<td>"+item[4]+"</td>";
                     newRow+="<td><input type='number' class='form-control' value='1' style='width:75px;'></td>";
                     newRow+="<td>"+"<button type='button' class='btn btn-danger btnDelete'><i class='fa fa-eraser'></i></button>"+"</td>";
                     newRow+="<td>"+item[5]+"</td>";
                  newRow+="</tr>";
           });
           if($('#costo').val()==""){
              swal('Error!','Ingreso Costo del Servicio','error');
           }else{
              $('#productosAgregados').append(newRow);
  //            calculartotal();
           }

           $('#modal-productos').modal('hide');
           $('#modal-default').modal('show');
          //    if(table.cell(index,0).nodes().to$().find('input').is(':checked')){
                //table.cell(index,6).nodes().to$().find('input').val("0");
                //    alert(table.cell(index,5).nodes().to$().find('input').val());
            //  }
  });
});

function resetTablePro(){
   var table=$('#tableProductos').DataTable();
    table.rows().deselect();
     //alert(valueT);
  /*  var data = table.$('input').serialize();
       alert(
           "The following data would have been submitted to the server: \n\n"+
           data.substr( 4,4 )+'...'
       );
*/
}
function validarform(){
var respuesta="";
if($('#nombre').val()==""){
   respuesta+="Falta Ingresar Nombre\n";
}
if($('#descripcion').val()==""){
   respuesta+="Falta Ingresar descripcion\n";
}
if($('#valor_publico').val()==""){
    respuesta+="Falta Ingresar Valor al Publico\n";
}
if($('#costo').val()==""){
  respuesta+="Falta Ingresar Costo\n";
}
if($('#tipo').val()=="0"){
   respuesta+="Seleccione Tipo de Servicio\n";
}
if(respuesta==""){
  respuesta="OK";
}
  return respuesta;
}


//funciones de botones
 function editServicio(id){
   $('#control').val(id);


   $.ajaxSetup({
      headers: {
       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    data="id="+id;
     $.ajax({
         method: "post",
         data: data,
         url: "<?php echo route('editar_servicio')?>",
         success: function(response){ // What to do if we succeed
             cargarServicio(response);
               }

           });
 }
 function deleteServicio(id){
   swal({
       title: "Estas Seguro?",
       text: "Una vez eliminado el servicio no podra ser recuperado!",
       icon: "warning",
       buttons: ["Cancelar", "Eliminar Servicio!"],
       dangerMode: true,
        })
       .then((willDelete) => {
         if (willDelete) {
           $.ajaxSetup({
             headers:{
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             }
           });
           var data="id="+id;
           $.ajax({
             method: "post",
             data: data,
             url: "<?php echo route('deleteservicio')?>",
             success: function(response){
               swal("Buen Trabajo!", response, "success")
               .then((value) => {
                  location.reload();
                     });
             }
           });
         } else {
           swal("Servicio a Salvo!");
         }
       });
 }
function cargarServicio(servicio){
  $('#nombre').val(servicio.nombre)
  $('#descripcion').val(servicio.descripcion);
  $('#valor_publico').val(servicio.valor_publico);
  $('#valor_cambio').val(servicio.valor_cambio);
  $('#costo').val(servicio.costo);
  $('#tipo').val(servicio.tipo);

  $.ajaxSetup({
     headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
       }
   });
   data="id="+servicio.id;
    $.ajax({
        method: "post",
        data: data,
        url: "<?php echo route('getproductos')?>",
        success: function(response){ // What to do if we succeed
            var productos=JSON.parse(response);
            $('#productosAgregados').empty();
            var newRow='';
            $.each(productos,function(i,val){
              newRow+="<tr>";
                 newRow+="<td>"+val.id+"</td>";
                 newRow+="<td>"+val.nombre+"</td>";
                 newRow+="<td>"+val.costo+"</td>";
                 newRow+="<td>"+val.cantidad+"</td>";
                 newRow+="<td>"+val.accion+"</td>";
                 newRow+="<td>"+val.tipo_iva+"</td>";
              newRow+="</tr>";
            });
               $('#productosAgregados').append(newRow);

              }

          });

  $('#modal-default').modal('show');

}

function limpiarform(){
  $('#productosAgregados').empty();
  $('#control').val("0");
  $('#nombre').val("");
  $('#descripcion').val("");
  $('#valor_publico').val("");
  $('#valor_cambio').val("0.00");
  $('#costo').val("");
  $('#tipo').val("0");
}
</script>
<div class="callout callout-warning">
        <h4>Recuerda!</h4>
        Ante cualquier error contactar inmediatamente al Administrador del Sistema, el CMR esta en fase de desarrollo.

</div>

<section class="content-header">
      <h1>
        Administracion
        <small>Servicios</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-laptop"></i> Administracion</a></li>
        <li class="active">Servicios</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
                <br>
              <h3 class="box-title">Registro de Servicios</h3>
            </div>

            <!-- /.box-header -->
            <div class="box-body">
              <button type="button" onclick="limpiarform();"  class="btn btn-default" data-toggle="modal" data-target="#modal-default">
                  Ingresar Servicio
                </button>
              <br>
              <br>
              <table id="tableServicios" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>#id</th>
                  <th>Nombre</th>
                  <th>Precio</th>
                  <th>Descripción</th>
                  <th>Accion</th>
                </tr>
                </thead>
                <tbody>
                  @foreach($servicios as $item)
                    <tr>
                       <th>{{$item->id}}</th>
                       <th>{{$item->nombre}}</th>
                       <th>{{$item->valor_publico}}</th>
                       <th>{{$item->descripcion}}</th>
                       <th>
                       <a href="javascript:verServicio({{$item->id}});"><button class="btn btn-success"><i class="fa fa-eye"></i></button></a>
                       <a href="javascript:editServicio({{$item->id}});"><button class="btn btn-info"><i class="fa fa-edit"></i></button></a>
                       <a href="javascript:deleteServicio({{$item->id}});"><button class="btn btn-danger"><i class="fa fa-eraser"></i></button></a>
                       </th>
                    </tr>
                  @endforeach
                </tbody>
                <tfoot>
                <tr>
                  <th>#id</th>
                  <th>Nombre</th>
                  <th>Precio</th>
                  <th>Descripción</th>
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
                <h4 class="modal-title">ABM Servicios</h4>
              <div class="modal-body" style="height: 450px;overflow-y: auto;">
                 <form role="form" id="formuser">
                 {{csrf_field()}}
                    <div class="box-body">
                      <div class="nav-tabs-custom">
                          <ul class="nav nav-tabs">
                             <li id="firstli" class="active"><a href="#Principales" data-toggle="tab">Datos Principales</a></li>
                             <li class=""><a href="#Productos" data-toggle="tab">Productos</a></li>
                          </ul>
                        <div class="tab-content">
                            <div class="active tab-pane" id="Principales">
                              <div class="row">
                               <div class="form-group col-lg-6 col-xl-12">
                               <input id="control" type="hidden" value="0">
                               <input id="token" type="hidden" value="{{csrf_token()}}">
                                   <label for="exampleInputEmail1">Nombre</label>
                                   <input type="text" class="form-control" id="nombre" placeholder="Ingrese Nombre">
                               </div>
                               <div class="form-group col-lg-6 col-xl-12">
                                   <label for="exampleInputEmail1">Descripción</label>
                                   <input type="text" class="form-control" id="descripcion" placeholder="Ingrese Descripción del Servicio">
                               </div>
                             </div>
                             <div class="row">
                               <div class="form-group col-lg-6 col-xl-12">
                                   <label for="exampleInputPassword1">Valor al Publico</label>
                                   <input type="number" class="form-control" id="valor_publico" placeholder="Valor al Publico">
                               </div>
                               <div class="form-group col-lg-6 col-xl-12">
                                   <label for="exampleInputPassword1">Costo</label>
                                   <input type="number" class="form-control" id="costo" placeholder="Costo del Servicio">
                               </div>
                             </div>
                             <div class="row">
                               <div class="form-group col-lg-6 col-xl-12">
                                   <label for="descri">Tipo De Servicio</label>
                                    <select class="form-control" id="tipo">
                                        <option value="0">--Seleccionar--</option>
                                        <option value="1">Alquiler de Productos.</option>
                                        <option value="2">Otros Servicios.</option>
                                    </select>
                               </div>
                             </div>
                             <div class="row">
                                <div class="form-group col-lg-6 col-xl-12">
                                <label>Otros Costos</label>
                                <?php
                                 $divisas = auth()->user()->empresa->divisas;
                                 foreach($divisas as $divisa){
                                     $moneda = $divisa->moneda;
                                 }
                                 ?>
                                <input class="form-control" type="text" value="{{$moneda}}" disabled>
                                </div>
                                <div class="form-group col-lg-6 col-xl-12">
                                    <label for="">Valor</label>
                                    <input class="form-control" type="number" value="0.00" id="valor_cambio" placeholder="Valor">                            
                                </div>
                            </div>

                            </div>
                            <div class="tab-pane" id="Productos">
                              <div class="row">
                                <div class="form-group col-lg-12 col-sm-12">
                                    <button class="btn btn-info" type="button" id="addProducto">Agregar Productos</button>
                                </div>
                              </div>
                              <br>
                              <div class="row">
                                  <div class="form-group col-lg-12 col-sm-12">
                                    <table class="table" >
                                       <thead>
                                          <tr>
                                           <th>#</th>
                                           <th>Nombre</th>
                                           <th>Costo</th>
                                           <th>Cantidad</th>
                                           <th>Acciones</th>
                                           <th>Tipo Iva</th>
                                           </tr>
                                        </thead>
                                        <tbody id="productosAgregados">
                                       </tbody>
                                    </table>
                                  </div>
                              </div>
                            </div>
                        </div>
                      </div>

                    </div>

                        <!--div class="form-group">
                           <label for="exampleInputFile">Imagen de Perfil</label>
                           <input type="file" id="profile_image">
                           <p class="help-block">Imagen que no supere 2 MB.</p>
                        </div-->

                    </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                <button type="button" id="saveServicio" class="btn btn-primary">Guardar Cambios</button>
              </div>
              </form>
            </div>
          </div>
    </div>
  </div>
    <div class="modal fade" id="modal-productos">
          <div class="modal-dialog bs-example-modal modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" id="cerrarProductos"  class="close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Seleccionar Productos</h4>
              </div>
              <div class="modal-body" style="height: 450px;overflow-y: auto;">
                <table id="tableProductos" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th></th>
                    <th>#id</th>
                    <th>Codigo</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Iva</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach($productos as $item)
                      <tr>
                         <td></td>
                         <td>{{$item->id}}</td>
                         <td>{{$item->codigo}}</td>
                         <td>{{$item->nombre}}</td>
                         <td>{{$item->valor_venta}}</td>
                         <td>{{$item->tipo_iva}}</td>
                      </tr>
                    @endforeach
                  </tbody>
                  <tfoot>
                  <tr>
                    <th></th>
                    <th>#id</th>
                    <th>Codigo</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Iva</th>
                  </tr>
                  </tfoot>
                </table>
              </div>
              <div class="modal-footer">
                <button type="button" id="saveProducto" class="btn btn-primary">Guardar Productos</button>
              </div>

            </div>
          </div>
    </div>
@endsection
