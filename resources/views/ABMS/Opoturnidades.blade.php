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
   $(document).ready(function(){

       $('.treeview').removeClass('active');
       $('#opor').addClass('active');
       $('#opor1').addClass('active');
       table();
       $('#insertarTelefono').click(function(){
         var cont=0;
         $('#tel tr').each(function (){
              cont++;
         });
        var referencia= $('#referencia').val();
         var telefono = $("#telefonoc").val();
         if(telefono!=''&&referencia!=''){
           var markup = "<tr><td>"+cont+"</td><td>" + telefono + "</td><td>"+referencia+"</td><td>" +
            "<a class='btn btn-danger' href='javascript:borrarTel("+cont+");'><li class='fa fa-eraser'></li><a>" +
            "</td></tr>";
            $("#tel tbody").append(markup);
         }else{
           swal("Error!", "Ingrese Numero de Telefono!", "error");
         }

            $('#telefonoc').val('');
            $('#referencia').val('');
       });

       $('#insertarCorreo').click(function(){
           var correo=$('#correoc').val();
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
           $('#correoc').val('');
       });
       $('#actionCliente').click(function(){
              var respuesta=validarcampos();
              if(respuesta=="OK"){

                var oportunidadc=$('#oportunidadc').val();
                var control=$("#controlc").val();

                var nombre=$("#nombrec").val();
                var direccion1=$("#direccion1c").val();
                var direccion2=$("#direccion2c").val();
                var cel1=$("#cel1c").val();
                var cel2=$("#cel2c").val();
                var fijo=$("#fijoc").val();
                var documento=$("#documentoc").val();
                var direccion_fiscal=$("#direccion_fiscalc").val();
                var condicion_fiscal=$("#condicion_fiscalc").val();
                var razon_social=$('#razon_social').val();
                var data="control="+control+"&nombre="+nombre+"&direccion1="+direccion1+"&direccion2="+direccion2+"&cel1="+cel1+"&cel2="+cel2+"&fijo="+fijo+
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

                     swal("Buen Trabajo!", "Has Convertido una oportunidad en un Cliente para Tu empresa, Felicidades!", "success")
                     .then((value) => {
                        deleo(oportunidadc);
                           });
                   }
                });
              }else{
                 swal("Error!",respuesta,"error");
              }
       });
       $('#saveProveedor').click(function(){
              var validacion=validarProveedor();
              if(validacion=='OK'){
                var razon_social= $('#razon_socialP').val();
                var telefono= $('#telefonoP').val();
                var direccion = $('#direccionP').val();
                var correo = $('#correoP').val();
                var cuit= $('#cuitP').val();
                var control=$('#controlP').val();
                var oportunidadc=$('#oportunidadp').val();
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
                      swal("Buen Trabajo!", "Has Convertido una oportunidad en un Proveedor para Tu empresa, Felicidades!", "success")
                      .then((value) => {
                         deleo(oportunidadc);
                            });
                          },
                          error: function(xhr, status, error) {
                            var err = eval("(" + xhr.responseText + ")");
                             alert(err.Message);
                            }


                      });

              }else{
                swal("Error!", validacion, "error");
              }
       });
     $('#saveOportunidades').click(function(){
          var validacion=validarFormularioO();
           if(validacion!="OK"){
               swal("Error!", validacion, "error");
           }else{
            var nombre= $('#nombre').val();
            var telefono= $('#telefono').val();
            var direccion = $('#direccion').val();
            var correo = $('#correo').val();
            var empresa= $('#empresa').val();
            var cargo= $('#cargo').val();
            var tipo= $('#tipo').val();
            var control= $('#control').val();
            var data="nombre="+nombre+"&telefono="+telefono+"&direccion="+direccion+"&correo="+correo+"&empresa="+empresa+"&cargo="+cargo+"&tipo="+tipo+"&control="+control;

            $.ajaxSetup({
               headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                 }
             });


            $.ajax({
                method: "post",
                data: data,
                url: "<?php echo route('agregar_oportunidad')?>",
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
   function limpiarForm(){
     $('#nombre').val('');
     $('#telefono').val('');
     $('#direccion').val('');
     $('#correo').val('');
     $('#empresa').val('');
     $('#cargo').val('');
     $('#tipo').val('0');
     $('#control').val('0');
   }
   function editOportunidad(id){
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
                  url: "<?php echo route('edit_oportunidad')?>",
                  success: function(response){ // What to do if we succeed
                  loadData(response);


                        }
                    });

   }
   function loadData(oportunidad){
     limpiarForm();
     $('#nombre').val(oportunidad.Nombre);
     $('#telefono').val(oportunidad.Telefono);
     $('#direccion').val(oportunidad.Direccion);
     $('#correo').val(oportunidad.Correo);
     $('#empresa').val(oportunidad.Empresa);
     $('#cargo').val(oportunidad.Cargo);
     $('#tipo').val(oportunidad.Tipo);
     $('#control').val(oportunidad.id);
     $('#modal-default').modal('show');
   }
   function validarFormularioO(){
     var respuesta="";

     if($('#nombre').val()=='')
          respuesta+="Debe Ingresar un Nombre \n";
     if($('#telefono').val()=='')
           respuesta+="Debe Ingresar un Telefono \n";
     if($('#direccion').val()=='')
           respuesta+="Debe Ingresar una Direccion \n";
     if($('#correo').val()=='')
           respuesta+="Debe Ingresar un Correo \n";
     if($('#empresa').val()=='')
            respuesta+="Debe Ingresar una Empresa \n";
     if($('#cargo').val()=='')
            respuesta+="Debe Ingresar un Cargo \n";
     if($('#tipo').val()=='0')
            respuesta+="Debe Seleccionar un Tipo de Oportunidad \n";

    if(respuesta=="")
           respuesta="OK";

           return respuesta;

   }
   function table(){
     $('#tableOportunidad').DataTable({
         'paging'      : true,
     'lengthChange': true,
     'searching'   : true,
     'ordering'    : true,
     'info'        : true,
     'autoWidth'   : true
      }

       );
   }
   function deleo(id){
     $.ajaxSetup({
         headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           }
       });

         data="id="+id;
      $.ajax({
          method: "post",
          data: data,
          url: "<?php echo route('delete_oportunidad')?>",
          success: function(response){ // What to do if we succeed
            swal("Buen Trabajo!", "La oportunidad se a Eliminada!", "success")
            .then((value) => {
                       location.reload();
                  });

                }
            });

   }
   function deleteOportunidad(id){

     swal({
         title: "Estas Seguro?",
         text: "No te des por Vencido aun puedes concretar el trato con esta oportunidad!",
         icon: "warning",
         buttons: ["Cancelar", "Me doy por Vencido!"],
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
                  url: "<?php echo route('delete_oportunidad')?>",
                  success: function(response){ // What to do if we succeed
                    swal("Buen Trabajo!", "Se elimino Correctamente!", "success")
                    .then((value) => {
                       location.reload();
                          });

                        }
                    });

           } else {
             swal("Sigue Planeando mejores estrategias para cautivar a nuevos Clientes!");
           }
         });

   }
   function confirmarOportunidad(id){
     limpiarFormCliente();
     $.ajaxSetup({
                 headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                   }
               });

                 data="id="+id;
              $.ajax({
                  method: "post",
                  data: data,
                  url: "<?php echo route('edit_oportunidad')?>",
                  success: function(response){
                     // What to do if we succeed
                     var tipo=response.Tipo;
                     if(tipo=="Cliente"){
                          loadDataCliente(response);
                     }else{
                       loadDataProveedor(response);

                     }



                        }
                    });

   }

  function limpiarFormCliente(){
    $('#controlc').val('0');
    $('#nombrec').val('');
    $('#direccion1c').val('');
    $('#direccion2c').val('');
    $('#cel1c').val('');
    $('#cel2c').val('');
    $('#fijoc').val('');
    $('#documentoc').val('');
    $('#direccion_fiscalc').val('');
    $('#condicion_fiscalc').val('--Seleccionar--');
    $('#telefonoc').val('');
    $('#correoc').val('');
    $('#razon_social').val('');
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
  function loadDataCliente(oportunidad){
    $('#controlc').val('0');
    $('#nombrec').val(oportunidad.Nombre);
    $('#direccion1c').val(oportunidad.Direccion);
    $('#direccion2c').val('S/E');
    $('#cel1c').val('S/E');
    $('#cel2c').val('S/E');
    $('#fijoc').val(oportunidad.Telefono);
    $('#documentoc').val('S/E');
    $('#direccion_fiscalc').val('S/E');
    $('#condicion_fiscalc').val('--Seleccionar--');
    $('#telefonoc').val('');
    $('#correoc').val(oportunidad.Correo);
    $('#modalCliente').modal('show');
    $('#oportunidadc').val(oportunidad.id);
    $('#razon_social').val(oportunidad.Empresa);
  }
  function validarcampos(){
       var respuesta="";
       if($('#nombrec').val()==''){
              respuesta+="Debe Ingresar Nombre\n";}
       if($("#direccion1c").val()==''){
              respuesta+="Debe Ingresar Direccion\n";}
        if($("#direccion2c").val()==''){
              respuesta+="Complete direccion #2\n";}
        if($("#cel1c").val()==''){
            respuesta+="Debe Ingresar Celular\n";}
        if($("#cel2c").val()==''){
            respuesta+="Complete Celular #2\n";}
        if($("#fijoc").val()==''){
            respuesta+="Debe Ingresar Telefono Fijo\n";}
        if($("#documentoc").val()==''){
            respuesta+="Debe Ingresar Numero de Documento\n";}
        if($("#direccion_fiscalc").val()==''){
            respuesta+="Debe Ingresar Direccion Fiscal\n";}
        if($("#condicion_fiscalc").val()=='--Seleccionar--'){
            respuesta+="Debe Seleccionar condicion fiscal\n";}
        if($('#razon_social').val()==''){
          respuesta+="De Ingresar Razon Social \n";
        }
        if(respuesta==''){
           respuesta="OK";  }

       return respuesta;
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

  //Conversion a proveedores
  function loadDataProveedor(response){
        $('#razon_socialP').val(response.Empresa);
        $('#cuitP').val('000000000000');
        $('#direccionP').val(response.Direccion);
        $('#telefonoP').val(response.Telefono);
        $('#correoP').val(response.Correo);
        $('#oportunidadp').val(response.id);
        $('#modalProveedor').modal('show');


  }
  function validarFormProveedor(){
  var respuesta="";
    if($('#razon_socialP').val()=='')
         respuesta+="Debe Ingresar Razon Social \n";
    if($('#cuitP').val()=='')
         respuesta+="Debe Ingresar CUIT \n";
    if($('#direccionP').val()=='')
         respuesta+="Debe Ingresar Direccion \n";
    if($('#telefonoP').val()=='')
         respuesta+="Debe Ingresar Telefono \n";
    if($('#correoP').val()=='')
         respuesta+="Debe Ingresar Correo \n";
    if(respuesta=="")
         respuesta="OK";

    return respuesta;
  }
  function validarProveedor(){
    var respuesta="";
      if($('#razon_socialP').val()=='')
           respuesta+="Debe Ingresar Razon Social \n";
      if($('#cuitP').val()=='')
           respuesta+="Debe Ingresar CUIT \n";
      if($('#direccionP').val()=='')
           respuesta+="Debe Ingresar Direccion \n";
      if($('#telefonoP').val()=='')
           respuesta+="Debe Ingresar Telefono \n";
      if($('#correoP').val()=='')
           respuesta+="Debe Ingresar Correo \n";
      if(respuesta=="")
           respuesta="OK";

      return respuesta;
  }
</script>
<div class="callout callout-warning">
        <h4>Recuerda!</h4>
        Ante cualquier error contactar inmediatamente al Administrador del Sistema, el CMR esta en fase de desarrollo.

</div>

<section class="content-header">
      <h1>
        Oportunidades
        <small>ABM Oportunidades</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-laptop"></i> Oportunidades</a></li>
        <li class="active">ABM Oportunidades</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
                <br>
              <h3 class="box-title">Registro de Oportunidades</h3>
            </div>

            <!-- /.box-header -->
            <div class="box-body">
              <button type="button" onClick="limpiarForm();" class="btn btn-default" data-toggle="modal" data-target="#modal-default">
                  Ingresar Oportunidad
                </button>
              <br>
              <br>
              <table id="tableOportunidad" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>#id</th>
                  <th>Nombre</th>
                  <th>Direccion</th>
                  <th>Telefono</th>
                  <th>Empresa</th>
                  <th>Tipo</th>
                  <th>Accion</th>
                </tr>
                </thead>
                <tbody>
                  @foreach($oportunidades as $item)
                    <tr>
                       <th>{{$item->id}}</th>
                       <th>{{$item->Nombre}}</th>
                       <th>{{$item->Direccion}}</th>
                       <th>{{$item->Telefono}}</th>
                       <th>{{$item->Empresa}}</th>
                       <th>{{$item->Tipo}}</th>
                       <th>
                       <a href="javascript:editOportunidad({{$item->id}});"><button class="btn btn-info"><i class="fa fa-edit"></i></button></a>
                       <a href="javascript:deleteOportunidad({{$item->id}});"><button class="btn btn-danger"><i class="fa fa-eraser"></i></button></a>
                         <a href="javascript:confirmarOportunidad({{$item->id}});"><button class="btn btn-success"><i class="fa fa-check"></i></button></a>
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
                  <th>Empresa</th>
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
                <h4 class="modal-title">ABM Oportunidades</h4>
              <div class="modal-body">
                 <form role="form" id="formuser">
                 {{csrf_field()}}
                    <div class="box-body">
                       <div class="row">
                        <div class="form-group col-lg-6 col-xl-12">
                        <input id="control" type="hidden" value="0">
                        <input id="token" type="hidden" value="{{csrf_token()}}">
                            <label for="exampleInputEmail1">Nombre</label>
                            <input type="text" class="form-control" id="nombre" placeholder="Ingrese Nombre">
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
                            <label>Oportunidad de Tipo</label>
                            <select class="form-control" id="tipo">
                            <option value="0">--Seleccionar--</option>
                            <option value="Cliente">Cliente</option>
                            <option value="Proveedor">Proveedor</option>
                            </select>
                        </div>
                        <div class="form-group col-lg-6 col-xl-12">
                            <label>Empresa</label>
                            <input type="text" class="form-control" id="empresa" placeholder="Ingrese una Empresa">
                        </div>
                      </div>
                      <div class="row">
                             <div class="form-group col-lg-6 col-lx-12">
                                <label>Cargo</label>
                                <input type="text" class="form-control" id="cargo" placeholder="Ingrese un cargo">
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

    <div class="modal fade" id="modalCliente">
          <div class="modal-dialog bs-example-modal-lg modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">ABM Cliente</h4>
              </div>
              <div class="modal-body">
                 <form role="form" id="formcliente"  enctype="multipart/form-data">
                 {{csrf_field()}}
                    <div class="box-body">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                           <li id="firstli" class="active"><a href="#Personales" data-toggle="tab">Datos Personales</a></li>
                           <li class=""><a href="#Fiscales" data-toggle="tab">Datos Fiscales</a></li>
                           <li class=""><a href="#Telefonos" data-toggle="tab">Telefonos</a></li>
                            <li class=""><a href="#Correos" data-toggle="tab">Correos</a></li>
                        </ul>
                      <div class="tab-content">
                          <div class="active tab-pane" id="Personales">
                              <div class="row">
                                  <div class="form-group col-lg-6 col-sm-12">
                                      <input id="controlc" name="control" type="hidden" value="0">
                                      <input id="oportunidadc" name="control" type="hidden" value="0">

                                      <input id="token" type="hidden" value="{{csrf_token()}}">
                                      <label for="exampleInputEmail1">Nombre Completo</label>
                                      <input type="text" class="form-control" id="nombrec" name="nombre" placeholder="Ingrese Nombre y Apellido">
                                  </div>
                                 <div class="form-group col-lg-6 col-sm-12">
                                      <label for="exampleInputEmail1">Direccion #1</label>
                                      <input type="text" class="form-control" id="direccion1c" name="direccion1" placeholder="Ingrese Direccion">
                                </div>
                              </div>
                              <div class="row">
                                 <div class="form-group col-lg-6 col-sm-12">
                                      <label for="exampleInputEmail1">Direccion #2</label>
                                      <input type="text" class="form-control" id="direccion2c" name="direccion2" placeholder="Ingrese Direccion #2 (Opcional)">
                                </div>
                                <div class="form-group col-lg-6 col-sm-12">
                                     <label for="exampleInputEmail1">Celular #1</label>
                                     <input type="text" class="form-control" id="cel1c" name="cel1c" placeholder="Ingrese Numero de Celular">
                               </div>
                             </div>
                             <div class="row">
                               <div class="form-group col-lg-6 col-sm-12">
                                    <label for="exampleInputEmail1">Celular #2</label>
                                    <input type="text" class="form-control" id="cel2c" name="cel2c" placeholder="Ingrese Numero de Celular #2 (Optional)">
                              </div>
                              <div class="form-group col-lg-6 col-sm-12">
                                   <label for="exampleInputEmail1">Telefono Fijo</label>
                                   <input type="text" class="form-control" id="fijoc" name="fijoc" placeholder="Ingrese Numero de Telefono fijo">
                             </div>
                             </div>
                          </div>
                          <div class="tab-pane" id="Fiscales">
                    <div class="row">
                        <div class="form-group col-lg-6 col-sm-12">
                            <label for="exampleInputEmail1">No. De Documento</label>
                            <input type="text" class="form-control" name="documento" id="documentoc" placeholder="Ingrese Numero de Documento">
                        </div>
                        <div class="form-group col-lg-6 col-sm-12">
                            <label for="exampleInputEmail1">Direccion Fiscal</label>
                            <input type="text" class="form-control" name="direccion_fiscal" id="direccion_fiscalc" placeholder="Ingrese Direccion Fiscal">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-6 col-sm-12">
                            <label for="exampleInputEmail1">Condicion Fiscal</label>
                            <select type="text" class="form-control" id="condicion_fiscalc" name="condicion_fiscal">
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
                                       <input type="text" class="form-control" name="telefonoc" id="telefonoc">
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
                                     <input type="email" class="form-control" name="correoc" id="correoc">
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
  <div class="modal fade" id="modalProveedor">
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
                      <input id="oportunidadp" name="control" type="hidden" value="0">
                      <input id="token" type="hidden" value="{{csrf_token()}}">
                          <label for="exampleInputEmail1">Razon Social</label>
                          <input type="text" class="form-control" id="razon_socialP" placeholder="Ingrese Nombre">
                      </div>
                      <div class="form-group col-lg-6 col-xl-12">
                          <label for="exampleInputEmail1">Telefono</label>
                          <input type="text" class="form-control" id="telefonoP" placeholder="Ingrese Telefono">
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-lg-6 col-xl-12">
                          <label for="exampleInputPassword1">Direccion</label>
                          <input type="text" class="form-control" id="direccionP" placeholder="Ingrese Dirección">
                      </div>
                      <div class="form-group col-lg-6 col-xl-12">
                          <label for="descripcion">Correo Electronico</label>
                          <input type="email" class="form-control" id="correoP" placeholder="Ingreso Correo Electronico">
                      </div>
                    </div>

                    <div class="row">
                      <div class="form-group col-lg-6 col-xl-12">
                          <label>Ingrese Cuit</label>
                          <input class="form-control" type="text" id="cuitP" placeholder="Sin guiones">
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
              <button type="button" id="saveProveedor" class="btn btn-primary">Guardar Cambios</button>
            </div>
            </form>
          </div>
        </div>
  </div>
@endsection
