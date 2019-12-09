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
<!--Perfil de Clientes-->
<meta name="csrf-token" content="{{ csrf_token() }}">
<script type="text/javascript">
function borrarTel(fila){
  var control=$("#control").val();
  if(control==0){
    $('#tel tr').each(function(){
           var No=$(this).find("td").eq(0).html();
           if(No==fila){
               $(this).remove();
           }
    });
  }else{
  }
}
function borrarCor(fila){
    $('#Cor tr').each(function(){
       var No=$(this).find("td").eq(0).html();
      if(No==fila){
          $(this).fadeOut();
      }
    });
}

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
function resetTablePro(){
  var table=$('#tableServicios').DataTable();
   table.rows().deselect();
}
function table(){
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
$(document).ready(function(){
  table();
  $('#cargar_imagen').click(function(){
    var profile_image=$('#profile_image').val();
    var id=$('#control').val();
  });
  $('#addServicio').click(function(){
    $('#modal-servicios').modal({backdrop: 'static', keyboard: false});
      resetTablePro();

    //  $('#modal-default').modal('hide');
      $('#modal-servicios').modal('show');

  });
  $("#serviciosAgregados2").on('click', '.btnDelete', function () {
    $(this).closest('tr').remove();
    calcularTotalS();
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
});
</script>

  <section class="content-header">
    <h1>
      Perfil del Cliente
    </h1>
    <ol class="breadcrumb">
      <li><a href="/"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li><a href="ClientesAgenda">Clientes</a></li>
      <li class="active">{{$cliente->nombre}}</li>
    </ol>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-md-3">
        <div class="box box-primary">
          <div class="box-body box-profile">
            <img class="profile-user-img img-responsive img-circle" src="../../public/dist/clientes/{{$cliente->profile_image}}" alt="User profile picture">
            <h3 class="profile-username text-center">{{$cliente->nombre}}</h3>
            <!--p class="text-muted text-center">Software Engineer</p-->
            <ul class="list-group list-group-unbordered">
              <li class="list-group-item">
                <b>Ventas</b> <a class="pull-right">1,322</a>
              </li>
            </ul>
            <!--a href="#" class="btn btn-primary btn-block"><b>Follow</b></a-->
          </div>
        </div>
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Sobre El Cliente</h3>
          </div>
          <div class="box-body">
            <strong><i class="fa fa-book margin-r-5"></i> Información</strong>
            <p class="text-muted">
              {{$cliente->nombre}} se unio a {{$cliente->empresa->razon_social}} el dia {{$cliente->created_at}}
            </p>
            <hr>
            <strong><i class="fa fa-map-marker margin-r-5"></i> Dirección</strong>
            <p class="text-muted">{{$cliente->direccion1}} {{$cliente->direccion2}}</p>
            <hr>
            <strong><i class="fa fa-file-text-o margin-r-5"></i> Condición Fiscal</strong>
            <p>{{$cliente->condicion_fiscal}}</p>
          </div>
        </div>
      </div>
      <div class="col-md-9">
        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            <li id="firstli" class="active"><a href="#Personales" data-toggle="tab">Datos Personales</a></li>
            <li class=""><a href="#Fiscales" data-toggle="tab">Datos Fiscales</a></li>
            <li class=""><a href="#Telefonos" data-toggle="tab">Telefonos</a></li>
             <li class=""><a href="#Correos" data-toggle="tab">Correos</a></li>
             <li class=""><a href="#Servicios" data-toggle="tab">Servicios</a></li>
             <li class=""><a href="#foto_cliente" data-toggle="tab">Cargar Foto</a></li>
            <li><a href="#timeline" data-toggle="tab">Cuenta Corriente</a></li>

          </ul>
          <div class="tab-content">
            <div class="active tab-pane" id="Personales">
                <div class="row">
                    <div class="form-group col-lg-6 col-sm-12">
                        <input id="control" name="control" type="hidden" value="{{$cliente->id}}">
                        <input id="token" type="hidden" value="{{csrf_token()}}">
                        <label for="exampleInputEmail1">Nombre Completo</label>
                        <input value="{{$cliente->nombre}}" type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingrese Nombre y Apellido">
                    </div>
                   <div class="form-group col-lg-6 col-sm-12">
                        <label for="exampleInputEmail1">Direccion #1</label>
                        <input type="text" value="{{$cliente->direccion1}}" class="form-control" id="direccion1" name="direccion1" placeholder="Ingrese Direccion">
                  </div>
                </div>
                <div class="row">
                   <div class="form-group col-lg-6 col-sm-12">
                        <label for="exampleInputEmail1">Direccion #2</label>
                        <input type="text" class="form-control" value="{{$cliente->direccion2}}" id="direccion2" name="direccion2" placeholder="Ingrese Direccion #2 (Opcional)">
                  </div>
                  <div class="form-group col-lg-6 col-sm-12">
                       <label for="exampleInputEmail1">Celular #1</label>
                       <input type="text" class="form-control" value="{{$cliente->cel1}}" id="cel1" name="cel1" placeholder="Ingrese Numero de Celular">
                 </div>
               </div>
               <div class="row">
                 <div class="form-group col-lg-6 col-sm-12">
                      <label for="exampleInputEmail1">Celular #2</label>
                      <input type="text" class="form-control" value="{{$cliente->cel2}}" id="cel2" name="cel2" placeholder="Ingrese Numero de Celular #2 (Optional)">
                </div>
                <div class="form-group col-lg-6 col-sm-12">
                     <label for="exampleInputEmail1">Telefono Fijo</label>
                     <input type="text" class="form-control" value="{{$cliente->fijo}}" id="fijo" name="fijo" placeholder="Ingrese Numero de Telefono fijo">
               </div>
               </div>
            </div>
            <div class="tab-pane" id="Fiscales">
      <div class="row">
          <div class="form-group col-lg-6 col-sm-12">
              <label for="exampleInputEmail1">No. De Documento</label>
              <input type="text" class="form-control" value="{{$cliente->documento}}" name="documento" id="documento" placeholder="Ingrese Numero de Documento">
          </div>
          <div class="form-group col-lg-6 col-sm-12">
              <label for="exampleInputEmail1">Direccion Fiscal</label>
              <input type="text" class="form-control" name="direccion_fiscal" value="{{$cliente->direccion_fiscal}}" id="direccion_fiscal" placeholder="Ingrese Direccion Fiscal">
          </div>
      </div>
      <div class="row">
          <div class="form-group col-lg-6 col-sm-12">
              <label for="exampleInputEmail1">Condicion Fiscal</label>
              <select type="text" class="form-control" value="{{$cliente->condicion_fiscal}}" id="condicion_fiscal" name="condicion_fiscal">
                   <option>--Seleccionar--</option>
                   <option>Consumidor Final</option>
                   <option>Responsable Inscripto</option>
                   <option>Monotributista</option>
                   <option>Exento</option>
              </select>
          </div>
          <div class="form-group col-lg-6 col-sm-12">
              <label for="exampleInputEmail1">Razon Social</label>
              <input type="text" class="form-control" value="{{$cliente->razon_social}}" name="razon_social" id="razon_social" placeholder="Ingrese Razon Social">
          </div>

      </div>
            </div>
            <div class="tab-pane" id="Telefonos">
                 <div class="row">
                     <div class="form-group col-lg-6 col-sm-12">
                         <label for="exampleInputFile">Agregar Telefono</label>
                         <input type="text"  class="form-control" name="telefono" id="telefono">
                     </div>
                     <div class="form-group col-lg-6 col-sm-12">
                         <label for="exampleInputFile">Referencia</label>
                         <input type="text" class="form-control"  name="referencia" id="referencia">
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
                                  @foreach($cliente->telefonos as $telefono)
                                     <tr>
                                       <td>{{$telefono->id}}</td>
                                       <td>{{$telefono->telefono}}</td>
                                       <td>{{$telefono->referencia}}</td>
                                       <td><a class='btn btn-danger' href='javascript:borrarTel({{$telefono->id}});'><li class='fa fa-eraser'></li><a></td>
                                     </tr>
                                  @endforeach
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
                                @foreach($cliente->correos as $correo)
                                    <tr>
                                      <td>{{$correo->id}}</td>
                                      <td>{{$correo->correo}}</td>
                                      <td><a class='btn btn-danger' href='javascript:borrarCor({{$cliente->id}});'><li class='fa fa-eraser'></li></a></td>
                                    </tr>
                                @endforeach
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
                          <?php $total_servicio=0; ?>
                          <tbody id="serviciosAgregados2">
                            @foreach($servicios as $servicio)
                            <tr>
                              <?php
                              $totalS=$servicio->servicio->valor_publico;
                              $descuento=$servicio->descuento;
                              if($descuento!=0.00){
                                $descuento=$descuento/100;
                                $descuento=$descuento*$totalS;
                                $totalS=$totalS-$descuento;
                              }else{

                              }

                               $total_servicio=$total_servicio+$totalS; ?>
                              <td>{{$servicio->servicio->id}}</td>
                              <td>{{$servicio->servicio->nombre}}</td>
                              <td>{{$servicio->servicio->valor_publico}}</td>
                              <td>{{$servicio->descuento}}</td>
                              <td>{{$servicio->vigencia}}</td>
                              <td>
                              <button type='button' class='btn btn-danger btnDelete'><i class='fa fa-eraser'></i></button>
                              </td>
                            </tr>
                            @endforeach
                         </tbody>
                      </table>
                    </div>
              </div>
              <div class="row">
                   <div class="form-group col-lg-6 col-sm-12">
                            <label for="total_servicios">Total de Los Servicios</label>
                            <input id="total_servicios" value="{{$total_servicio}}" class="form-control" type="text" disabled>
                   </div>
                   <div class="form-group col-lg-6 col-sm-12">
                           <label for="inicio_facturacion">Inicio De Facturación</label>
                           <input id="inicio_facturacion" value="{{$cliente->inicio_facturacion}}" class="form-control" type="month">
                   </div>
              </div>
        </div>
        <div class="tab-pane" id="foto_cliente">
          <div class="container">
            <form id="profile_form" method="post" action="upload_profile" enctype="multipart/form-data">
              {{csrf_field()}}
              <input type="hidden" name="cliente_id" id="cliente_id"  value="{{$cliente->id}}">
            <div class="row">
              <div class="form-group col-sm-12 col-lg-6">
                   <label for="exampleInputFile">Imagen de Perfil</label>
                   <input type="file" name="profile_image" id="profile_image">
                   <p class="help-block">Imagen que no supere 2 MB.</p>
              </div>
              <div class="form-group col-sm-12 col-lg-6">
                  <button type="submit"  class="btn btn-primary"  name="button">Cambiar Imagen de Perfil!</button>
              </div>
            </div>
          </form>
          </div>
        </div>

            <div class="tab-pane" id="timeline">
                <h1>Saldo a la fecha : {{$saldo}}</h1><br>
                <table class="table" id="tel">
                    <thead>
                    <tr>
                        <th>id</th>
                        <th>numero</th>
                        <th>tipo</th>
                        <th>fecha</th>
                        <th>total</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($cuentas as $cuenta)
                        <tr>
                            <td>{{$cuenta->id}}</td>
                            <td>{{$cuenta->numero}}</td>
                            <td>{{$cuenta->tipo}}</td>
                            <td>{{$cuenta->fecha}}</td>
                            <td>{{$cuenta->total}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
      </div>
    </div>
  </section>

<!-- Servicios -->

    <div class="modal fade" id="modal-servicios">
          <div class="modal-dialog bs-example-modal modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button"  data-dismiss="modal" aria-label="Close"  class="close">
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
                    @foreach($servis as $item)
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
