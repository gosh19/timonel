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
  $('#tableProductos').DataTable({
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
  $('#gesItem2').addClass('active');


  $('#saveProducto').click(function(){

         var validacion=validarForm();
         if(validacion=='OK'){
           var tipo_iva=$('#tipo_iva').val();
           var valor_venta=$('#valor_venta_producto').val()
           var valor_cambio = $('#valor_cambio').val();
           var stock=$('#stock').val();
           var proveedore_id=$('#proveedore_id').val();
           var costo=$('#costo').val();
           var control=$('#control').val();
           var stock_sugerido=$('#stock_sugerido').val();
           var nombre=$('#nombre').val();
           var codigo=$('#codigo').val();

           var data="valor_cambio="+valor_cambio+"&tipo_iva="+tipo_iva+"&valor_venta="+valor_venta+"&stock="+stock+"&proveedore_id="+proveedore_id+'&control='+control+'&costo='+costo+
           "&stock_sugerido="+stock_sugerido+"&nombre="+nombre+"&codigo="+codigo;


           $.ajaxSetup({
              headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


           $.ajax({
               method: "post",
               data: data,
               url: "<?php echo route('agregar_producto')?>",
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
table();
}
);
function validarForm(){
 var respuesta="";
  if($('#tipo_iva').val()=='0'){
      respuesta+="Debe Ingresar Tipo de Iva \n";
  }
  if($('#valor_venta_producto').val()==''){
    respuesta+="Debe Ingresar Valor de Venta \n";
  }
  if($('#stock').val()==''){
    respuesta+="Debe Ingresar Stock \n";
  }
  if($('#proveedore_id').val()=='0'){
    respuesta+="Debe Ingresar Proveedor \n";
  }
  if($('#costo').val()==''){
    respuesta+="Debe Ingresar Costo \n";
  }
  if($('#stock_sugerido').val()==''){
    respuesta+="Debe Ingresar Stock Sugerido \n";
  }
  if($('#nombre').val()==''){
    respuesta+="Debe Ingresar Nombre \n";
  }
  if($('#codigo').val()==''){
    respuesta+="Debe Ingresar Codigo \n";
  }
  if(respuesta==''){
       respuesta="OK";
  }
  return respuesta;

}
function editProducto(id){
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
                url: "<?php echo route('edit_producto')?>",
                success: function(response){ // What to do if we succeed
                loadData(response);


                      }
                  });
}
function loadData(data){
  $('#tipo_iva').val(data.tipo_iva);
  $('#valor_venta_producto').val(data.valor_venta);
  $('#valor_cambio').val(data.valor_cambio);
  $('#stock').val(data.stock);
  $('#proveedore_id').val(data.proveedore_id);
  $('#costo').val(data.costo);
  $('#stock_sugerido').val(data.stock_sugerido);
  $('#nombre').val(data.nombre);
  $('#codigo').val(data.codigo);
  $('#control').val(data.id);
  $('#modal-default').modal('show');
}
function limpiarForm(){
  $('#tipo_iva').val('0');
  $('#valor_venta_producto').val('');
  $('#valor_cambio').val('0.00');
  $('#stock').val('');
  $('#proveedore_id').val('0');
  $('#costo').val('');
  $('#stock_sugerido').val('');
  $('#nombre').val('');
  $('#codigo').val('');
  $('#control').val('0');

}
function deleteProducto(id){
  swal({
      title: "Estas Seguro?",
      text: "Una vez eliminado el producto no podra ser recuperado!",
      icon: "warning",
      buttons: ["Cancelar", "Eliminar Producto!"],
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
               url: "<?php echo route('delete_producto')?>",
               success: function(response){ // What to do if we succeed
                 swal("Buen Trabajo!", "Producto Eliminado!", "success")
                 .then((value) => {
                    location.reload();
                       });

                     }
                 });

        } else {
          swal("Producto a Salvo!");
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
        <small>Productos</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-laptop"></i> Administracion</a></li>
        <li class="active">Productos</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
                <br>
              <h3 class="box-title">Registro de Productos</h3>
            </div>

            <!-- /.box-header -->
            <div class="box-body">
              <button type="button" onClick="limpiarForm();" class="btn btn-default" data-toggle="modal" data-target="#modal-default">
                  Ingresar Producto
                </button>
              <br>
              <br>
              <table id="tableProductos" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>#id</th>
                  <th>Codigo</th>
                  <th>Nombre</th>
                  <th>Proveedor</th>
                  <th>Costo</th>
                  <th>Stock</th>
                  <th>Accion</th>
                </tr>
                </thead>
                <tbody>
                  @foreach($productos as $item)
                    <tr>
                       <th>{{$item->id}}</th>
                       <th>{{$item->codigo}}</th>
                       <th>{{$item->nombre}}</th>
                       <th>{{$item->proveedore->razon_social}}</th>
                       <th>{{$item->costo}}</th>
                       <th>{{$item->stock}}</th>
                       <th>
                       <a href="javascript:verProducto({{$item->id}});"><button class="btn btn-success"><i class="fa fa-eye"></i></button></a>
                       <a href="javascript:editProducto({{$item->id}});"><button class="btn btn-info"><i class="fa fa-edit"></i></button></a>
                       <a href="javascript:deleteProducto({{$item->id}});"><button class="btn btn-danger"><i class="fa fa-eraser"></i></button></a>

                       </th>
                    </tr>
                  @endforeach
                </tbody>
                <tfoot>
                <tr>
                  <th>#id</th>
                  <th>Codigo</th>
                  <th>Nombre</th>
                  <th>Proveedor</th>
                  <th>Costo</th>
                  <th>Stock</th>
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
                <h4 class="modal-title">ABM Producto</h4>
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
                            <label for="exampleInputEmail1">Codigo</label>
                            <input type="number" class="form-control" id="codigo" placeholder="Ingrese Codigo">
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-group col-lg-6 col-xl-12">
                            <label for="exampleInputPassword1">Stock</label>
                            <input type="number" class="form-control" id="stock" placeholder="Ingrese Stock Inicial">
                        </div>
                        <div class="form-group col-lg-6 col-xl-12">
                            <label for="descri">Stock Sugerido</label>
                            <input type="number" class="form-control" id="stock_sugerido" placeholder="Ingreso Stock Minimo">
                        </div>
                      </div>

                      <div class="row">
                        <div class="form-group col-lg-6 col-xl-12">
                            <label>Valor Para la Venta</label>
                            <input class="form-control" type="number" id="valor_venta_producto" placeholder="Valor Para la Venta">
                        </div>
                        <div class="form-group col-lg-6 col-xl-12">
                            <label>Costo</label>
                            <input class="form-control" type="number" id="costo" placeholder="Costo del Producto">
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-group col-lg-6 col-xl-12">
                            <label>Seleccionar Proveedor</label>
                            <select class="form-control" id="proveedore_id">
                                <option value="0">--Seleccionar--</option>
                              @foreach($proveedores as $itemp)
                                    <option value="{{$itemp->id}}">{{$itemp->razon_social}}</option>
                              @endforeach
                            </select>
                        </div>
                        <div class="form-group col-lg-6 col-xl-12">
                            <label>Tipo de Iva</label>
                            <select class="form-control" id="tipo_iva">
                                    <option value="0">--Seleccionar--</option>
                                    <option value="27.00">27%</option>
                                    <option value="21.00">21%</option>
                                    <option value="10.50">10.5%</option>
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
                <button type="button" id="saveProducto" class="btn btn-primary">Guardar Cambios</button>
              </div>
              </form>
            </div>
          </div>
    </div>
@endsection
