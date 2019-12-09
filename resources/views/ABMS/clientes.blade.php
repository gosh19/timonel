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
  $('#tableClientes').DataTable({
      'paging'      : true,
  'lengthChange': true,
  'searching'   : true,
  'ordering'    : true,
  'info'        : true,
  'autoWidth'   : true
   }

    );
    $('#tableServicios').DataTable({
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
function limpiarForm(){
   $('#serviciosAgregados2').empty();
   $('#total_servicios').val("$0.00");
   $('#inicio_facturacion').val("");
   $('#control').val('0');
   $('#nombre').val('');
   $('#direccion1').val('');
   $('#direccion2').val('');
   $('#cel1').val('');
   $('#cel2').val('');
   $('#fijo').val('');
   $('#documento').val('');
   $('#direccion_fiscal').val('');
   $('#condicion_fiscal').val('--Seleccionar--');
   $('#telefono').val('');
   $('#correo').val('');
   var cont=0;
   $('#tel tr').each(function(){
    if(cont!=0){
          $(this).remove();
    }
     cont++;
    });
     cont=0;
    $('#Cor tr').each(function(){
    if(cont!=0){
          $(this).remove();
       }
     cont++;
      });

    }

function borrarCor(fila){
  var control=$("#control").val();
  if(control==0){
    $('#Cor tr').each(function(){
       var No=$(this).find("td").eq(0).html();
      if(No==fila){
          $(this).fadeOut();
      }
    });
  }else{


  }

}
function borrarTel(fila){
  var control=$("#control").val();
  if(control==0){
    $('#tel tr').each(function(){
           var No=$(this).find("td").eq(0).html();

           if(No==fila){
               $(this).fadeOut();
           }
    });

  }else{
  }
}
function resetTablePro(){
   var table=$('#tableServicios').DataTable();
    table.rows().deselect();
}
$(document).ready(function(){


    $('.treeview').removeClass('active');
    $('#cmrItem').addClass('active');
    $('#cmrItem1').addClass('active');
    table();
    $("#serviciosAgregados2").on('click', '.btnDelete', function () {
      $(this).closest('tr').remove();
      calcularTotalS();
    });
    $('#cerrarServicio').click(function(){
      $('#modal-servicios').modal('hide');
      $('#modal-default').modal('show');
    });
    $('#saveServicio').click(function(){
       var table=$('#tableServicios').DataTable();
            var newRow="";

            $.map(table.rows('.selected').data(), function (item) {
                    newRow+="<tr>";
                       newRow+="<td>"+item[1]+"</td>";
                       newRow+="<td>"+item[2]+"</td>";
                       newRow+="<td>"+item[4]+"</td>";
                       newRow+="<td><input type='number' onchange='calcularTotalS();' class='form-control' value='0.0' style='width:75px;'></td>";
                       newRow+="<td><input type='month' class='form-control' style='width:200px;'></td>";
                       newRow+="<td>"+"<button type='button' class='btn btn-danger btnDelete'><i class='fa fa-eraser'></i></button>"+"</td>";
                       newRow+="<td style='display:none;'>"+item[4]+"</td>";
                    newRow+="</tr>";
             });

                $('#serviciosAgregados2').append(newRow);
                calcularTotalS();
                $('#modal-servicios').modal('hide');
                $('#modal-default').modal('show');
    });






    $('#addServicio').click(function(){
      $('#modal-servicios').modal({backdrop: 'static', keyboard: false});
        resetTablePro();

        $('#modal-default').modal('hide');
        $('#modal-servicios').modal('show');

    });
       $('#insertarTelefono').click(function(){
         var cont=0;
         $('#tel tr').each(function (){
              cont++;
         });
        var referencia= $('#referencia').val();
         var telefono = $("#telefono").val();
         if(telefono!=''&&referencia!=''){
           var markup = "<tr><td>"+cont+"</td><td>" + telefono + "</td><td>"+referencia+"</td><td>" +
            "<a class='btn btn-danger' href='javascript:borrarTel("+cont+");'><li class='fa fa-eraser'></li><a>" +
            "</td></tr>";
            $("#tel tbody").append(markup);
         }else{
           swal("Error!", "Ingrese Numero de Telefono!", "error");
         }

            $('#telefono').val('');
            $('#referencia').val('');
       });

       $('#insertarCorreo').click(function(){
           var correo=$('#correo').val();
           if(correo!=''){
             var cont=0;
             $('#Cor tr').each(function(){
               cont++;
             });

                   var markup="<tr><td>"+cont+"</td><td>"+correo+"</td><td><a class='btn btn-danger' href='javascript:borrarCor("+cont+");'><li class='fa fa-eraser'></li></a></td></tr>";
             $("#Cor tbody").append(markup);
           }else{
             swal("Error!", "Ingrese un Correo Electronico!", "error");
           }
           $('#correo').val('');
       });
       $('#actionCliente').click(function(){
              var respuesta=validarcampos();

              if(respuesta=="OK"){

                var control=$("#control").val();
                var nombre=$("#nombre").val();
                var direccion1=$("#direccion1").val();
                var direccion2=$("#direccion2").val();
                var cel1=$("#cel1").val();
                var cel2=$("#cel2").val();
                var fijo=$("#fijo").val();
                var documento=$("#documento").val();
                var direccion_fiscal=$("#direccion_fiscal").val();
                var condicion_fiscal=$("#condicion_fiscal").val();
                var razon_social=$('#razon_social').val();
                var inicio_facturacion=$('#inicio_facturacion').val();
                var servicios=[];
                var descuentos=[];
                var vigencias=[];
               $('#serviciosAgregados2 tr').each(function(){
                        var id=$(this).find("td").eq(0).html();
                        var descuento=parseFloat($(this).find("td").eq(3).find("input").val());
                        var vigen=$(this).find("td").eq(4).find("input").val();

                        servicios.push(id);
                        descuentos.push(descuento);
                        vigencias.push(vigen);
               });


                   var serviciosC=JSON.stringify(servicios);
                   var descuentosC=JSON.stringify(descuentos);
                   var vigenciasC=JSON.stringify(vigencias);

                var data="inicio_facturacion="+inicio_facturacion+"&vigencias="+vigenciasC+"&descuentos="+descuentosC+"&servicios="+serviciosC+"&control="+control+"&nombre="+nombre+"&direccion1="+direccion1+"&direccion2="+direccion2+"&cel1="+cel1+"&cel2="+cel2+"&fijo="+fijo+
                "&documento="+documento+"&direccion_fiscal="+direccion_fiscal+"&condicion_fiscal="+condicion_fiscal+"&razon_social="+razon_social;
                   $.ajaxSetup({
                     headers: {
                       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                      }
                     });
                 $.ajax({
                    method: "post",
                    data: data,
                    url: "<?php echo route('agregar_cliente')?>",
                    success: function(response){ // What to do if we succeed
                        //alert(response.id_last);
                     cargarReferencias(response);
                     cargarCorreos(response);
                     swal("Buen Trabajo!", "Cliente Cargado Con Exito!", "success")
                     .then((value) => {
                        location.reload();
                           });
                   }
                });
              }else{
                 swal("Error!",respuesta,"error");
              }
       });

});

function calcularTotalS(){
  var total=0;
  $('#serviciosAgregados2 tr').each(function(){
    var precio=parseFloat($(this).find("td").eq(6).html());
     var item=parseFloat($(this).find("td").eq(2).html());
     var descuento=parseFloat($(this).find("td").eq(3).find("input").val());
     if(descuento!=0){
         descuento=descuento/100;
         var des=precio*descuento;
         var preciofinal=precio-des;
        $(this).find("td").eq(2).html(preciofinal);
         item=preciofinal*1.21;
     }else{
       $(this).find("td").eq(2).html(precio);
        item=precio*1.21;
     }
    total=total+item;
  });
   $('#total_servicios').val("$"+total.toFixed(2));
}
function cargarReferencias(response){
  var cliente_id=response.id_last;

  var telefono=new Array();
  var referencia=new Array();
  var cont=0;
  $('#tel tr').each(function(){
        if(cont!=0){
              telefono[cont]=$(this).find("td").eq(1).html();
              referencia[cont]=$(this).find("td").eq(2).html();
        }else{
        }
        cont++;
  });
  for(i=0;i<=cont;i++){
        if(telefono[i]!=null){

          var data="telefono="+telefono[i]+"&referencia="+referencia[i]+"&cliente_id="+cliente_id;
             $.ajaxSetup({
               headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
               });
           $.ajax({
              method: "post",
              data: data,
              url: "<?php echo route('agregar_telefono')?>",
              success: function(response){ // What to do if we succeed
                  //alert(response.id_last);

             }
          });
        }
  }
}
function cargarCorreos(response){
       var cliente_id=response.id_last;
       var correo=new Array();
       var cont=0;
   $('#Cor tr').each(function (){
        if(cont!=0){
             correo[cont]=$(this).find("td").eq(1).html();

        }else{}

       cont++;
   });

  for(var i=0;i<=cont;i++){
        if(correo[i]!=null){
          var data="correo="+correo[i]+"&cliente_id="+cliente_id;
             $.ajaxSetup({
               headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
               });
           $.ajax({
              method: "post",
              data: data,
              url: "<?php echo route('agregar_correo')?>",
              success: function(response){ // What to do if we succeed
                  //alert(response.id_last);

             }
          });
        }
  }
}
function validarcampos(){
     var respuesta="";
     if($('#nombre').val()==''){
            respuesta+="Debe Ingresar Nombre\n";}
     if($("#direccion1").val()==''){
            respuesta+="Debe Ingresar Direccion\n";}
      if($("#direccion2").val()==''){
            respuesta+="Complete direccion #2\n";}
      if($("#cel1").val()==''){
          respuesta+="Debe Ingresar Celular\n";}
      if($("#cel2").val()==''){
          respuesta+="Complete Celular #2\n";}
      if($("#fijo").val()==''){
          respuesta+="Debe Ingresar Telefono Fijo\n";}
      if($("#documento").val()==''){
          respuesta+="Debe Ingresar Numero de Documento\n";}
      if($("#direccion_fiscal").val()==''){
          respuesta+="Debe Ingresar Direccion Fiscal\n";}
      if($("#condicion_fiscal").val()=='--Seleccionar--'){
          respuesta+="Debe Seleccionar condicion fiscal\n";}
      if($('#razon_social').val()==''){
           respuesta+="Debe Ingresar Razon Social";
      }
      if(respuesta==''){
         respuesta="OK";  }

     return respuesta;
}
function verCliente(id){
location.href="PerfilClientes?id="+id;
}

</script>

<div class="callout callout-warning">
        <h4>Recuerda!</h4>
        Ante cualquier error contactar inmediatamente al Administrador del Sistema, el CMR esta en fase de desarrollo.

</div>

<section class="content-header">
      <h1>
        Clientes
        <small>Agenda</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-laptop"></i> Clientes</a></li>
        <li class="active">Agenda</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
                <br>
              <h3 class="box-title">Registro de Clientes</h3>
            </div>

            <!-- /.box-header -->
            <div class="box-body">
            <button type="button" onClick="limpiarForm();" class="btn btn-default" data-toggle="modal" data-target="#modal-default">
                Agregar Cliente
              </button>
              <br>
              <br>
              <table id="tableClientes" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>#id</th>
                  <th>Nombre</th>
                  <th>Direccion</th>
                  <th>Telefono</th>
                  <th>Documento</th>
                  <th>Profile</th>
                  <th>Accion</th>
                </tr>
                </thead>
                <tbody>
                  @foreach($clientes as $item)
                    <tr>
                       <th>{{$item->id}}</th>
                       <th>{{$item->nombre}}</th>
                       <th>{{$item->direccion1}}</th>
                       <th>{{$item->cel1}}</th>
                       <th>{{$item->documento}}</th>

                       <th> <img src="../../public/dist/clientes/{{$item->profile_image}}" class="img-circle" alt="User Image" style="width:40px;"></th>
                       <th>

                       <a href="javascript:verCliente({{$item->id}});"><button class="btn btn-success"><i class="fa fa-eye"></i></button></a>
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
                  <th>Documento</th>
                  <th>Profile</th>
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
          <div class="modal-dialog bs-example-modal-lg modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">ABM Cliente</h4>
              </div>
              <div class="modal-body" style="height: 450px;overflow-y: auto;">
                 <form role="form" id="formcliente"  enctype="multipart/form-data">
                 {{csrf_field()}}
                    <div class="box-body">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                           <li id="firstli" class="active"><a href="#Personales" data-toggle="tab">Datos Personales</a></li>
                           <li class=""><a href="#Fiscales" data-toggle="tab">Datos Fiscales</a></li>
                           <li class=""><a href="#Telefonos" data-toggle="tab">Telefonos</a></li>
                            <li class=""><a href="#Correos" data-toggle="tab">Correos</a></li>
                            <li class=""><a href="#Servicios" data-toggle="tab">Servicios</a></li>

                        </ul>
                      <div class="tab-content">
                          <div class="active tab-pane" id="Personales">
                              <div class="row">
                                  <div class="form-group col-lg-6 col-sm-12">
                                      <input id="control" name="control" type="hidden" value="0">
                                      <input id="token" type="hidden" value="{{csrf_token()}}">
                                      <label for="exampleInputEmail1">Nombre Completo</label>
                                      <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingrese Nombre y Apellido">
                                  </div>
                                 <div class="form-group col-lg-6 col-sm-12">
                                      <label for="exampleInputEmail1">Direccion #1</label>
                                      <input type="text" class="form-control" id="direccion1" name="direccion1" placeholder="Ingrese Direccion">
                                </div>
                              </div>
                              <div class="row">
                                 <div class="form-group col-lg-6 col-sm-12">
                                      <label for="exampleInputEmail1">Direccion #2</label>
                                      <input type="text" class="form-control" id="direccion2" name="direccion2" placeholder="Ingrese Direccion #2 (Opcional)">
                                </div>
                                <div class="form-group col-lg-6 col-sm-12">
                                     <label for="exampleInputEmail1">Celular #1</label>
                                     <input type="text" class="form-control" id="cel1" name="cel1" placeholder="Ingrese Numero de Celular">
                               </div>
                             </div>
                             <div class="row">
                               <div class="form-group col-lg-6 col-sm-12">
                                    <label for="exampleInputEmail1">Celular #2</label>
                                    <input type="text" class="form-control" id="cel2" name="cel2" placeholder="Ingrese Numero de Celular #2 (Optional)">
                              </div>
                              <div class="form-group col-lg-6 col-sm-12">
                                   <label for="exampleInputEmail1">Telefono Fijo</label>
                                   <input type="text" class="form-control" id="fijo" name="fijo" placeholder="Ingrese Numero de Telefono fijo">
                             </div>
                             </div>
                          </div>
                          <div class="tab-pane" id="Fiscales">
                    <div class="row">
                        <div class="form-group col-lg-6 col-sm-12">
                            <label for="exampleInputEmail1">No. De Documento</label>
                            <input type="text" class="form-control" name="documento" id="documento" placeholder="Ingrese Numero de Documento">
                        </div>
                        <div class="form-group col-lg-6 col-sm-12">
                            <label for="exampleInputEmail1">Direccion Fiscal</label>
                            <input type="text" class="form-control" name="direccion_fiscal" id="direccion_fiscal" placeholder="Ingrese Direccion Fiscal">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-6 col-sm-12">
                            <label for="exampleInputEmail1">Condicion Fiscal</label>
                            <select type="text" class="form-control" id="condicion_fiscal" name="condicion_fiscal">
                                 <option>--Seleccionar--</option>
                                 <option>Consumidor Final</option>
                                 <option>Responsable Inscripto</option>
                                 <option>Monotributista</option>
                                 <option>Exento</option>
                            </select>
                        </div>
                        <div class="form-group col-lg-6 col-sm-12">
                            <label for="exampleInputEmail1">Razon Social</label>
                            <input type="text" class="form-control" name="razon_social" id="razon_social" placeholder="Ingrese Razon Social">
                        </div>

                    </div>
                          </div>
                          <div class="tab-pane" id="Telefonos">
                               <div class="row">
                                   <div class="form-group col-lg-6 col-sm-12">
                                       <label for="exampleInputFile">Agregar Telefono</label>
                                       <input type="text" class="form-control" name="telefono" id="telefono">
                                   </div>
                                   <div class="form-group col-lg-6 col-sm-12">
                                       <label for="exampleInputFile">Referencia</label>
                                       <input type="text" class="form-control" name="referencia" id="referencia">
                                   </div>
                                  <div class="form-group col-lg-12 col-sm-12">
                                      <label for="exampleInputFile">Agregar.</label>
                                      <button type="button" class="btn btn-primary btn-block" name="insertarTelefono" id="insertarTelefono">
                                        Agregar Telefono
                                      </button>
                                  </div>
                               </div>
                               <div class="row">
                                   <div class="form-group col-lg-12 col-sm-12">
                                          <table class="table" id="tel">
                                             <thead>
                                                <tr>
                                                 <th>#</th>
                                                 <th>Telefono</th>
                                                 <th>Referencia</th>
                                                 <th>Accion</th>
                                                 </tr>
                                              </thead>
                                              <tbody>
                                             </tbody>
                                          </table>
                                   </div>
                               </div>
                        </div>
                        <div class="tab-pane" id="Correos">
                             <div class="row">
                                 <div class="form-group col-lg-6 col-sm-12">
                                     <label for="exampleInputFile">Agregar Correo Electronico</label>
                                     <input type="email" class="form-control" name="correo" id="correo">
                                 </div>
                                <div class="form-group col-lg-6 col-sm-12">
                                    <label for="exampleInputFile">Agregar.</label>
                                    <button type="button" class="btn btn-primary btn-block" name="insertarCorreo" id="insertarCorreo">
                                      Agregar Correo
                                    </button>
                                </div>
                             </div>
                             <div class="row">
                                 <div class="form-group col-lg-12 col-sm-12">
                                        <table class="table" id="Cor">
                                           <thead>
                                              <tr>
                                               <th>#</th>
                                               <th>Correo</th>
                                               <th>Accion</th>
                                               </tr>
                                            </thead>
                                            <tbody>
                                           </tbody>
                                        </table>
                                 </div>
                             </div>
                      </div>
                      <div class="tab-pane" id="Servicios">
                            <div class="row">
                                  <div class="form-group col-lg-12 col-sm-12">
                                       <button type="button" id="addServicio" class="btn btn-primary">Agregar Servicios</button>
                                  </div>
                            </div>
                            <div class="row">
                                  <div class="form-group col-lg-12 col-sm-12">
                                    <table class="table" id="serviciosAgregados">
                                       <thead>
                                          <tr>
                                           <th>#</th>
                                           <th>Nombre</th>
                                           <th>Costo</th>
                                           <th>Descuento</th>
                                           <th>Vigencia</th>
                                           <th>Acciones</th>
                                           </tr>
                                        </thead>
                                        <tbody id="serviciosAgregados2">
                                       </tbody>
                                    </table>
                                  </div>
                            </div>
                            <div class="row">
                                 <div class="form-group col-lg-6 col-sm-12">
                                          <label for="total_servicios">Total de Los Servicios</label>
                                          <input id="total_servicios" class="form-control" type="text" disabled>
                                 </div>
                                 <div class="form-group col-lg-6 col-sm-12">
                                         <label for="inicio_facturacion">Inicio De Facturación</label>
                                         <input id="inicio_facturacion" class="form-control" type="month">
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
                <button type="Button" id="actionCliente"  class="btn btn-primary">Guardar Cambios</button>
              </div>
              </form>
            </div>
          </div>
    </div>
  </div>


    <!--servicio modal-->


    <div class="modal fade" id="modal-servicios">
          <div class="modal-dialog bs-example-modal modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" id="cerrarServicio"  class="close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Seleccionar servicio</h4>
              </div>
              <div class="modal-body" style="height: 450px;overflow-y: auto;">
                <table id="tableServicios" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th></th>
                    <th>#id</th>
                    <th>nombre</th>
                    <th>descripcion</th>
                    <th>valor al publico</th>
                    <th>Costo</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach($servicios as $item)
                        @if($item->tipo==2)
                      <tr>
                         <td></td>
                         <td>{{$item->id}}</td>
                         <td>{{$item->nombre}}</td>
                         <td>{{$item->descripcion}}</td>
                         <td>{{$item->valor_publico}}</td>
                         <td>{{$item->costo}}</td>
                      </tr>
                      @endif
                    @endforeach
                  </tbody>
                  <tfoot>
                  <tr>
                    <th></th>
                    <th>nombre</th>
                    <th>descripcion</th>
                    <th>valor al publico</th>
                    <th>Costo</th>
                  </tr>
                  </tfoot>
                </table>
              </div>
              <div class="modal-footer">
                <button type="button" id="saveServicio" class="btn btn-primary">Guardar Servicios</button>
              </div>

            </div>
          </div>
    </div>
@endsection
