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
  $('#tableRecibo').DataTable({
      'paging'      : true,
  'lengthChange': true,
  'searching'   : true,
  'ordering'    : true,
  'info'        : true,
  'autoWidth'   : true
   });
   $('#Comprobantes').DataTable({
     'paging'      : true,
     'lengthChange': true,
     'searching'   : true,
     'ordering'    : true,
     'info'        : true,
     'autoWidth'   : true
    });
}
function CalcularTotal(){
      var Comprobantes = $('#Comprobantes').DataTable();
      var total = 0;
      $.map(Comprobantes.rows().data(), function (item) {
           var totalItem = item[3];
           total = parseFloat(totalItem) + total;
         });
         $('#total').val(total.toFixed(2));
         $('#TotalTXT').html("$"+total.toFixed(2));
}
$(document).ready(function(){
    table();
    $('#saveRecibo').click(function(){
      var Comprobantes =$('#Comprobantes').DataTable();
       var tab = Comprobantes.rows().data();

       var item = [];
       var c = 0;
       Comprobantes.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
          var d = this.data();
          item[c] = d;
          c++;
       });
       CalcularTotal();
       var items = JSON.stringify(item);
       $('#items').val(items);
       document.getElementById("addRecibo").submit();
    });
    $("#Comprobantes").on('click', '.btnDelete', function () {
        var results = $('#Comprobantes').DataTable();
            results
             .row( $(this).parents('tr') )
             .remove()
             .draw();
             CalcularTotal();
    });
    $('#saveFactura').click(function(){
        var tableFacturas = $('#tableFacturas').DataTable();
        var Comprobantes = $('#Comprobantes').DataTable();
        $.map(tableFacturas.rows('.selected').data(), function (item) {
         console.log(item);
         Comprobantes.row.add([
             item.id,
             "Factura",
             item.numero,
             item.subtotal,
             item.debe,
             item.pagado,
             "<button type='button' class='btn btn-danger btnDelete'><i class='fa fa-eraser'></i></button>"
         ]).draw(false);
       });
       tableFacturas.destroy();
       CalcularTotal();
       $('#modal-facturas').modal('hide');
    });
    $('#saveVenta').click(function(){
      var tableVentas = $('#tableVentas').DataTable();
      var Comprobantes = $('#Comprobantes').DataTable();
      $.map(tableVentas.rows('.selected').data(), function (item) {
        console.log(item);
        Comprobantes.row.add([
          item.id,
          "Venta",
          item.numero,
          item.subtotal,
          item.debe,
          item.pagado,
          "<button type='button' class='btn btn-danger btnDelete'><i class='fa fa-eraser'></i></button>"
        ]).draw(false);
      });

      tableVentas.destroy();
      CalcularTotal();
      $('#modal-ventas').modal('hide');

    });
    $('#cerrarFacturas').click(function(){
      var table = $('#tableFacturas').DataTable();
      table.destroy();
      $('#modal-facturas').modal('hide');

    });
    $('#cerrarVentas').click(function(){
      var table = $('#tableVentas').DataTable();
      table.destroy();
      $('#modal-ventas').modal('hide');

    });
    $('#seleccionarVenta').click(function(){
      var cliente_id = $('#SelectCliente').val();
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $('#tableVentas').DataTable({
        "scrollCollapse": true,
        "paging":         false,
        "bDeferRender": true,
        "sPaginationType": "full_numbers",

        "ajax": {
          "url": "getVentas?cliente_id="+cliente_id,
          "type": "post"
        },
        "columnDefs": [ {
          orderable: false,
          className: 'select-checkbox',
          targets:   0
        } ],
        "select": {
          'style': 'multi'
        },
        "order": [[ 1, 'asc' ]],
        "columns": [
          {"data":"#"},
          {"data" : "id"},
          {"data":"tipo"},
          {"data": "numero"},
          {"data": "subtotal"},
          { "data": "total" },
          { "data": "debe" },
          { "data": "pagado" }

        ]
      });
      $('#modal-ventas').modal('show');
    });

    $('#seleccionarFactura').click(function(){
      var cliente_id = $('#SelectCliente').val();
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $('#tableFacturas').DataTable({
        "scrollCollapse": true,
        "paging":         false,
        "bDeferRender": true,
        "sPaginationType": "full_numbers",

        "ajax": {
          "url": "getFacturas?cliente_id="+cliente_id,
          "type": "post"
        },
        "columnDefs": [ {
          orderable: false,
          className: 'select-checkbox',
          targets:   0
        } ],
        "select": {
          'style': 'multi'
        },
        "order": [[ 1, 'asc' ]],
        "columns": [
          {"data":"#"},
          {"data":"id"},
          {"data":"tipo"},
          {"data": "numero"},
          {"data": "subtotal"},
          { "data": "total" },
          { "data": "debe" },
          { "data": "pagado" }

        ]
      });
      $('#modal-facturas').modal('show');
    });

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var options = {
      url: function(phrase) {
        return "getClientes";
      },
      getValue: function(element) {
        return element.nombre;
      },
      ajaxSettings: {
        dataType: "json",
        method: "POST",
        data: {
          dataType: "json"
        }
      },
      preparePostData: function(data) {
        data.phrase = $("#cliente").val();
        return data;
      },
      template: {
        type: "description",
        fields: {
          description: "direccion"
        }
      },
      list: {
        maxNumberOfElements: 8,
        match: {
          enabled: true
        },
        sort: {
          enabled: true
        },
        onSelectItemEvent: function () {
          var selectedItemValue = $("#cliente").getSelectedItemData().id;
          $('#SelectCliente').val(selectedItemValue).trigger("change");
        }
      },
      theme: "square"
    };
    $("#cliente").easyAutocomplete(options);


});

</script>

<div class="callout callout-warning">
        <h4>Recuerda!</h4>
        Ante cualquier error contactar inmediatamente al Administrador del Sistema, el CMR esta en fase de desarrollo.

</div>

<section class="content-header">
      <h1>
        Comprobantes
        <small>Recibos</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-laptop"></i> Comprobantes</a></li>
        <li class="active">Recibos</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
                <br>
              <h3 class="box-title">Registro de Recibos</h3>
            </div>

            <!-- /.box-header -->
            <div class="box-body">
              <button type="button" onClick="" class="btn btn-default" data-toggle="modal" data-target="#modal-default">
                  Ingresar Recibo
                </button>
              <br>
              <br>
              <table id="tableRecibo" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>#id</th>
                  <th>Cliente</th>
                  <th>Total</th>
                  <th>Fecha</th>
                  <th>Metodo de pago</th>
                  <th>Accion</th>
                </tr>
                </thead>
                <tbody>
                  @foreach($recibos as $item)
                    <tr>
                       <th>{{$item->id}}</th>
                       <th>{{$item->cliente->nombre}}</th>
                       <th>{{$item->total}}</th>
                       <th>{{$item->fecha}}</th>
                       <th>{{$item->metodo_pago}}</th>
                       <th>
                         <form id="getRecibo" class="" action="getRecibo" method="POST" target="_blank">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{{$item->id}}">
                             <a href=""><button class="btn btn-success" type="submit"><i class="fa fa-eye"></i></button></a>
                         </form>
                       </th>
                    </tr>
                  @endforeach
                </tbody>
                <tfoot>
                <tr>
                  <th>#id</th>
                  <th>Cliente</th>
                  <th>Total</th>
                  <th>Fecha</th>
                  <th>Metodo de pago</th>
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
                <h4 class="modal-title">Crear Recibo</h4>
              <div class="modal-body" style="height: 450px;overflow-y: auto;">
                 <form role="form" id="addRecibo" action="addRecibo" method="POST" target="_blank">
                 {{csrf_field()}}
                    <div class="box-body">
                       <div class="row">
                        <div class="form-group col-lg-6 col-xl-12">
                        <input id="control" type="hidden" value="0">
                        <input id="token" type="hidden" value="{{csrf_token()}}">
                            <label for="exampleInputEmail1">Cliente</label>
                            <input type="text" class="form-control" id="cliente" placeholder="Ingrese Cliente">
                            <input type="hidden" class="form-control" name="SelectCliente" id="SelectCliente" placeholder="Ingrese Cliente_id">
                        </div>
                        <div class="form-group col-lg-6 col-xl-12">
                          <label for="exampleInputPassword1">Metodo de pago</label>
                          <select id="metodo_pago" class="form-control" name="metodo_pago">
                            <option value="0">--Seleccionar--</option>
                            <option value="Efectivo">Efectivo</option>
                            <option value="Transferencia">Transferencia</option>
                            <option value="Tarjeta">Tarjeta Debito</option>
                          </select>
                        </div>
                      </div>
                      <div class="row">
                          <div class="form-group col-lg-6 col-xl-12">
                            <label for="">Facturas</label>
                            <button type="button" class="btn btn-primary form-control" id="seleccionarFactura">Seleccionar Factura</button>
                          </div>
                          <div class="form-group col-lg-6 col-xl-12">
                            <label for="">Ventas</label>
                            <button type="button" class="btn btn-primary form-control" id="seleccionarVenta">Seleccionar Venta</button>
                          </div>
                      </div>
                      <div class="row">
                            <div class="form-group col-lg-12 col-xl-12">
                                    <label for="">Ventas</label>
                                <table class="table" id="Comprobantes">
                                  <thead>
                                    <tr>
                                      <th>id</th>
                                      <th>Tipo</th>
                                      <th>Numero</th>
                                      <th>Total</th>
                                      <th>Debe</th>
                                      <th>Pagado</th>
                                      <th>Accion</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                  </tbody>
                                  <tbody>
                                    <tr>
                                      <th>id</th>
                                        <th>Tipo</th>
                                        <th>Numero</th>
                                        <th>Total</th>
                                        <th>Debe</th>
                                        <th>Pagado</th>
                                        <th>Accion</th>
                                    </tr>
                                  </tbody>
                                </table>
                            </div>
                      </div>
                    </div>
                    </div>
                    </div>

              <div class="modal-footer">
                <div class="col-lg-4 text-center" id="TotalTXT" style="font-size:2.4rem;font-weight:bold;">
                    $0.00
                </div>
                <input type="hidden" name="total" id="total" value="">
                <input type="hidden" name="items" id ="items" value="">
                <button type="button" id="saveRecibo" class="btn btn-primary">Guardar Cambios</button>
              </div>
              </form>
            </div>
          </div>
    </div>

    <div class="modal fade" id="modal-facturas">
            <div class="modal-dialog bs-example-modal modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" id="cerrarFacturas"  class="close">
                    <span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title">Seleccionar Facturas</h4>
                </div>
                <div class="modal-body" style="height: 450px;overflow-y: auto;">
                  <table id="tableFacturas" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                      <th>#</th>
                      <th>id</th>
                      <th>tipo</th>
                      <th>numero</th>
                      <th>subtotal</th>
                      <th>total</th>
                      <th>debe</th>
                      <th>pagado</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                    <tr>
                            <th>#</th>
                            <th>id</th>
                            <th>tipo</th>
                            <th>numero</th>
                            <th>subtotal</th>
                            <th>total</th>
                            <th>debe</th>
                            <th>pagado</th>
                    </tr>
                    </tfoot>
                  </table>
                </div>
                <div class="modal-footer">
                  <button type="button" id="saveFactura" class="btn btn-primary">Guardar Facturas</button>
                </div>

              </div>
            </div>
      </div>
      <div class="modal fade" id="modal-ventas">
              <div class="modal-dialog bs-example-modal modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" id="cerrarVentas"  class="close">
                      <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Seleccionar Ventas</h4>
                  </div>
                  <div class="modal-body" style="height: 450px;overflow-y: auto;">
                    <table id="tableVentas" class="table table-bordered table-striped">
                      <thead>
                      <tr>
                        <th>#</th>
                        <th>id</th>
                        <th>tipo</th>
                        <th>numero</th>
                        <th>subtotal</th>
                        <th>total</th>
                        <th>debe</th>
                        <th>pagado</th>
                      </tr>
                      </thead>
                      <tbody>
                      </tbody>
                      <tfoot>
                      <tr>
                              <th>#</th>
                              <th>id</th>
                              <th>tipo</th>
                              <th>numero</th>
                              <th>subtotal</th>
                              <th>total</th>
                              <th>debe</th>
                              <th>pagado</th>
                      </tr>
                      </tfoot>
                    </table>
                  </div>
                  <div class="modal-footer">
                    <button type="button" id="saveVenta" class="btn btn-primary">Guardar Facturas</button>
                  </div>

                </div>
              </div>
        </div>
@endsection
