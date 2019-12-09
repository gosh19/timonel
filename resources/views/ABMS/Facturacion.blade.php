@extends( auth()->user()->id_categoria == 1 ? 'Layout/_LayoutSU' : 'Layout/_Layout')@section('ProfiImage')
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
<div class="callout callout-warning">
        <h4>Recuerda!</h4>
        Ante cualquier error contactar inmediatamente al Administrador del Sistema, el CMR esta en fase de desarrollo.

</div>
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
 .DTED_Lightbox_Wrapper{
  z-index: 1000000000000000000 !important;
}
</style>


<section class="content-header">
      <h1>
        Facturas
        <!--<small>Agenda</small>-->
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fas fa-file-invoice-dollar"></i> Facturas</a></li>
        <!--<li class="active">Agenda</li>-->
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
                <br>
              <h3 class="box-title">Facturas registradas</h3>
            </div>

            <!-- /.box-header -->
            <div class="box-body">
            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-default">
                Agregar Factura
              </button>
                <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-credito">
                    Agregar Nota de Credito
                </button>
              <br>
              <br>
              <table id="tableFacturas" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>id</th>
                  <th>Cliente</th>
                  <th>Numero</th>
                  <th>Subtotal</th>
                  <th>Total</th>
                  <th>Debe</th>
                  <th>Pagado</th>
                  <th>Estado</th>
                  <th>Accion</th>
                </tr>
                </thead>
                <tbody>
                  @foreach($Facturas as $factura)
                    <tr>
                       <th>{{$factura->id}}</th>
                       <th>{{$factura->cliente->nombre}}</th>
                       <?php $numero = str_pad($factura->PtoVta, 4, "0", STR_PAD_LEFT).'-'. str_pad($factura->CbteDesde, 8, "0", STR_PAD_LEFT);?>
                       <th>{{ $numero }}</th>
                       <th>{{$factura->ImpTotal}}</th>
                       <th>{{$factura->ImpNeto}}</th>
                       <th>{{$factura->debe}}</th>
                       <th>{{$factura->pagado}}</th>
                       @if($factura->estado == 'Impaga')
                       <th><span class="badge badge-pill badge-red bg-red">Impaga</span></th>
                       @else
                        <th><span class="badge badge-pill badge-blue bg-green">Pagada</span></th>
                       @endif
                       <th>
                         <form id="getFactura" class="" action="getFactura" method="POST" target="_blank">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{{$factura->id}}">
                             <a href=""><button class="btn btn-success" type="submit"><i class="fa fa-eye"></i></button></a>
                         </form>
                       </th>
                    </tr>
                  @endforeach
                </tbody>
                <tfoot>
                <tr>
                  <th>id</th>
                  <th>Cliente</th>
                  <th>Numero</th>
                  <th>Subtotal</th>
                  <th>Total</th>
                  <th>Debe</th>
                  <th>Pagado</th>
                  <th>Estado</th>
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


    <script>
        function table(){
            $('#tableFacturas').DataTable({
              'paging'      : true,
              'lengthChange': true,
              'searching'   : true,
              'ordering'    : true,
              'info'        : true,
              'autoWidth'   : true
             });

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
                }],
                "select": {
                'style': 'multi'
                 },
                 "order": [[ 1, 'asc' ]]
            });



        var tablefactura = $('#tableFactura').DataTable({
               'paging'      : true,
               'lengthChange': true,
               'searching'   : true,
               'ordering'    : true,
               'info'        : true,
               'autoWidth'   : false,
              "order": [[ 1, 'asc' ]],
              "columnDefs": [
                {
                  "targets": [ 9 ],
                  "visible": false,
                  "searchable": false
                }
             ]
          });




        }
        function resetTablePro(){
            var table=$('#tableProductos').DataTable();
                table.rows().deselect();
        }
        function resetTablePro2(){
           var table=$('#tableServicios').DataTable();
           table.rows().deselect();
         }
        $(document).ready(function(){
            table();
            $('#SendFactura').click(function(){
              var table=$('#tableFactura').DataTable();
                 var tab = table.rows().data();

                 var item = [];
                 var c = 0;
                 var neto = 0;
                 table.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
                   var d = this.data();
                    var t = parseFloat(d[5])*parseFloat(d[9]);
                    neto = neto + t;
                    item[c] = d;
                    c++;
                 });

                 console.log(item);
              var items = JSON.stringify(item);


                 $.ajaxSetup({
                   headers: {
                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                   }
                });
                var cliente = $('#SelectCliente').val();
                var total = $('#TotalFactura').val();
                var iva21 = $('#iva21').val();
                var iva105 = $('#iva105').val();
                var iva27 = $('#iva27').val();
                var Netoiva21 = $('#Netoiva21').val();
                var Netoiva105 = $('#Netoiva105').val();
                var Netoiva27 = $('#Netoiva27').val();
                $('#items').val(items);
                $('#neto').val(neto);
                document.getElementById("formFactura").submit();

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
                      var selectedItemValueCredito = $('#clienteCredito').getSelectedItemData().id;
                      $('#SelectCliente').val(selectedItemValue).trigger("change");
                      $('#selectClienteCredito').val(selectedItemValueCredito).trigger("change");
                  }
              },
              theme: "square"
          };
          $("#cliente").easyAutocomplete(options);
          $("#clienteCredito").easyAutocomplete(options);

            $('#saveItem').click(function(){
                 var precio = parseFloat($('#precio').val());
                 var descripcion = $('#descripcion').val();
                 var iva = parseFloat($('#iva').val());
                 var results = $('#tableFactura').DataTable();
                 var impuesto = precio * (iva/100);
                 var suma = precio + impuesto;

                 results.row.add([
                     "Producto",
                     "9999",
                     "9999",
                     descripcion,
                     iva,
                     precio,
                     '<input type="number" class="form-control cantidadProducto" value="1" style="width:75px;">',
                     suma.toFixed(2),
                     "<button type='button' class='btn btn-danger btnDelete'><i class='fa fa-eraser'></i></button>",
                     "1"
                 ]).draw(false);
                 CalcularTotal();
                 $('#precio').val('0.00');
                 $('#descripcion').val('');
                 $('#iva').val('21.0');
                 $('#modalItem').modal('hide');
            });
            $('#addItem').click(function(){
              $('#modalItem').modal('show');
            });
            $('#addProducto').click(function(){
                $('#modal-productos').modal({backdrop: 'static', keyboard: false});
                resetTablePro();
                $('#modal-default').modal('hide');
                $('#modal-productos').modal('show');
            });
            $.ajaxSetup({
                   headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                     }
                 });



    $('#tableProductos').DataTable( {
        dom: "Bfrtip",
        'paging'      : true,
               'lengthChange': true,
               'searching'   : true,
               'ordering'    : true,
               'info'        : true,
               'autoWidth'   : false,
        "ajax": {
            "url": "getProductos",
            "type": "GET"
       },
        columns: [
            {
                data: null,
                defaultContent: '',
                className: 'select-checkbox',
                orderable: false
            },
            { data: "id" },
            { data: "Codigo" },
            { data: "Nombre" },
            { data: "Precio" },
            { data: "Costo" },
            { data: "Iva" },
            { data: "Total" },
            { data: "Stock" }


        ],
        order: [ 1, 'asc' ],
        select: {
            style:    'multi',
            selector: 'td:first-child'
        }

    });


            $('#cerrarProductos').click(function(){
               $('#modal-productos').modal('hide');
               $('#modal-default').modal('show');
            });
            $('#cerrarServicio').click(function(){
                $('#modal-servicios').modal('hide');
                $('#modal-default').modal('show');
            });
            $('#addServicio').click(function(){
                $('#modal-servicios').modal({backdrop: 'static', keyboard: false});
                resetTablePro2();
                $('#modal-servicios').modal('show');
            });
            $("#tableFactura").on('click', '.btnDelete', function () {
                var results = $('#tableFactura').DataTable();

                //console.log(results.row( $(this).parents('tr') ).data());
                //var row = results.row( $(this).parents('tr');


               results
                     .row( $(this).parents('tr') )
                     .remove()
                     .draw();

                     CalcularTotal();

            });
            $('#ResultsTableCredito').on('click','.btnDeleteCredito',function () {
                $(this).closest ('tr').remove ();
                CalcularTotalCredito();
            });
            $("#tableFactura").on('change', '.cantidadProducto', function () {
                var results = $('#tableFactura').DataTable();
                var row = results.row( $(this).parents('tr')).data();
                var total = parseFloat(row[5]);
                var cantidad = parseFloat($(this).val());
                var subTotal = total * cantidad;
                var iva = parseFloat(row[4]);

                var impuesto = subTotal * (iva/100);
                var suma = subTotal + impuesto;

                results.row( $(this).parents('tr')).every(function (rowIdx, tableLoop, rowLoop) {
                   results.cell(rowIdx,7).data(suma.toFixed(2));
                   results.cell(rowIdx,9).data(cantidad);
                 });


                     CalcularTotal();

            });
            $('#saveProducto').click(function(){
               var table=$('#tableProductos').DataTable();
               var newRow="";
               var results = $('#tableFactura').DataTable();

               $.map(table.rows('.selected').data(), function (item) {
                console.log(item);

                results.row.add([
                    "Producto",
                    item.id,
                    item.Codigo,
                    item.Nombre,
                    item.Iva,
                    item.Precio,
                    '<input type="number" class="form-control cantidadProducto" value="1" style="width:75px;">',
                    item.Total,
                    "<button type='button' class='btn btn-danger btnDelete'><i class='fa fa-eraser'></i></button>",
                    "1"
                ]).draw(false);

                });

                CalcularTotal();
                    $('#modal-productos').modal('hide');
                    $('#modal-default').modal('show');
           });
           //
           $('#saveServicio').click(function(){
              var table=$('#tableServicios').DataTable();
              var newRow="";
              var results = $('#tableFactura').DataTable();


              $.map(table.rows('.selected').data(), function (item) {
               console.log(item);
               var impuesto = parseFloat(item[4]) * 0.21;
               var total = parseFloat(item[4]) + impuesto;

               results.row.add([
                   "Servicio",
                   item[1],
                   item[1],
                   item[2],
                   "21.00",
                   item[4],
                   '<input type="number" class="form-control cantidadProducto" value="1" style="width:75px;">',
                   total.toFixed(2),
                   "<button type='button' class='btn btn-danger btnDelete'><i class='fa fa-eraser'></i></button>",
                   "1"
               ]).draw(false);

               });

               CalcularTotal();
                   $('#modal-servicios').modal('hide');
                   $('#modal-default').modal('show');
          });

        });

        function CalcularTotal(){
            var results = $('#tableFactura').DataTable();
            var total = 0;
            var iva21 =0;
            var iva105 = 0;
            var iva27 = 0;
            var Netoiva21 = 0;
            var Netoiva105 = 0;
            var Netoiva27 = 0;

            $.map(results.rows().data(), function (item) {
                 var totalItem = item[7];
                 totalItem = parseFloat(totalItem);

                 total = total + totalItem;
                 var iva = parseFloat(item[4]);

                 if(iva == 21){
                  var impuesto = totalItem / 1.21;
                  impuesto = totalItem - impuesto;
                  iva21 = iva21 + impuesto;
                  $('#iva21').val(iva21);
                  Netoiva21 = Netoiva21+totalItem;
                  $('#Netoiva21').val(Netoiva21);
                 }
                 if(iva == 10.5){
                   var impuesto = totalItem / 1.105;
                   impuesto = totalItem - impuesto;
                   iva105 = iva105 + impuesto;
                   $('#iva105').val(iva105);
                   Netoiva105 = Netoiva105+totalItem;
                   $('#Netoiva105').val(Netoiva105);

                 }
                 if(iva == 27){
                   var impuesto = totalItem / 1.27;
                   impuesto = totalItem - impuesto;
                   iva27 = iva27 + impuesto;
                   $('#iva27').val(iva27);
                   Netoiva27 = Netoiva27+totalItem;
                   $('#Netoiva27').val(Netoiva27);
                 }

            });

            $('#TotalFactura').val(total.toFixed(2));
            totalactual = parseFloat(total.toFixed(2));
            $('#TotalWithIva').html('$'+total.toFixed(2));

        }

    </script>


    <div class="modal fade" id="modal-default">
        <div class="modal-dialog bs-example-modal-lg modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">ABM Facturacion</h4>
                </div>

                <div class="modal-body" style="height: 450px;overflow-y: auto;">

                        <input type="hidden" id="InputFormCliente" name="cliente_id" value="" placeholder="cliente">

                        <!--<input type="text" name="empresa_id" value="">-->

                        <input type="hidden" id="InputFormTotal" name="total" value="" placeholder="total">

                        <input type="hidden" name="usuario_id" value="{{ auth()->user()->id }}" id="UserId" placeholder="usuario id">

                        <input type="hidden" id="allItems" name="items" value="" placeholder="items">



                        <div class="tab-content">

                            <div class="active tab-pane" id="Factura">
                              <form id="formFactura" class="" action="/Facturar/{}" method="get" target="_blank">


                                <div class="form-group col-lg-6 col-sm-12">
                                    <label for="exampleInputEmail1">Clientes</label>
                                    <input type="text" class="form-control" name="cliente" id="cliente" value="">
                                    <input type="hidden" class="form-control" name="SelectCliente" id="SelectCliente" value="">
                                </div>

                                <div class="form-group col-lg-2 col-sm-12">
                                    <label for="exampleInputEmail1">Productos</label><br>
                                    <button class="btn btn-info" type="button" id="addProducto">Agregar Productos</button>
                                </div>
                                <div class="form-group col-lg-2 col-sm-12">
                                    <label for="exampleInputEmail1">Servicios</label><br>
                                    <button type="button" id="addServicio" class="btn btn-primary">Agregar Servicios</button>
                                </div>
                                <div class="form-group col-lg-2 col-sm-12">
                                    <label for="exampleInputEmail1">Items</label><br>
                                    <button type="button" id="addItem" class="btn btn-primary">Agregar Item</button>
                                </div>

                            </div>
                                <br>
                            <div class="col-lg-12">

                                <table id="tableFactura" class="table table-bordered table-striped">

                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th class="text-center">id</th>
                                            <th class="text-center">Codigo</th>
                                            <th class="text-center">Nombre</th>
                                            <th class="text-center">IVA</th>
                                            <th class="text-center">Precio</th>
                                            <th class="text-center">Cantidad</th>
                                            <th class="text-center">Subtotal</th>
                                            <th class="text-center">Accion</th>
                                            <th>CantidadPro</th>
                                        </tr>
                                    </thead>
                                    <tbody id="ResultsTable" class="text-center">
                                    </tbody>
                                </table>
                                <div class="col-lg-12">
                                    <div class="row">

                                    </div>
                                </div>

                            </div>

                        </div>


                </div>

                <div class="modal-footer">
                    <div class="col-lg-4 text-center" id="TotalWithIva" style="font-size:2.4rem;font-weight:bold;">
                        $0.00
                    </div>

                    <input type="hidden" value="0.00" name="TotalFactura" id="TotalFactura">
                    <input type="hidden" name="iva21" value="0.00" id="iva21">
                    <input type="hidden" name="iva105" value="0.00" id="iva105">
                    <input type="hidden" name="iva27" value="0.00" id="iva27">
                    <input type="hidden" name="Netoiva21" value="0.00" id="Netoiva21">
                    <input type="hidden" name="Netoiva105" value="0.00" id="Netoiva105">
                    <input type="hidden" name="Netoiva27" value="0.00" id="Netoiva27">
                    <input type="hidden" name = "items" id ="items" value="">
                    <input type="hidden" name = "neto" id ="neto" value="">

                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="SendFactura">Crear Factura</button>
                </div>

                  </form>

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
                    <th>id</th>
                    <th>nombre</th>
                    <th>descripcion</th>
                    <th>Valor</th>
                    <th>Costo</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach($Servicios as $item)
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
                    <th>Valor</th>
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
                      <th>id</th>
                      <th>Codigo</th>
                      <th>Nombre</th>
                      <th>Precio</th>
                      <th>Costo</th>
                      <th>Iva</th>
                      <th>Total</th>
                      <th>Stock</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                    <tr>
                      <th></th>
                      <th>id</th>
                      <th>Codigo</th>
                      <th>Nombre</th>
                      <th>Precio</th>
                      <th>Costo</th>
                      <th>Iva</th>
                      <th>Total</th>
                      <th>Stock</th>
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

<div class="modal fade" id="modalItem" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Agregar Item</h5>
        </div>
         <div class="modal-body">
         <div class="col-xl-12 col-sm-12">
           <label for="">Descripcion</label>
           <input type="text" name="descripcion" class="form-control" id="descripcion" placeholder="Ingrese Descripcion">
         </div>
           <div class="col-xl-12 col-sm-12">
             <label for="">Precio</label>
             <input type="number" class="form-control" name="precio" id="precio" value="0.00">
           </div>
         <div class="col-xl-12 col-sm-12">
           <label for="">Iva</label>
           <select class="form-control" name="iva" id="iva">
              <option value="10.5">10.5%</option>
              <option value="21.0">21%</option>
              <option value="27.0">27%</option>
           </select>
         </div><br>
       </div>
       <br>
      <div class="modal-footer">
        <br>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" id="saveItem" class="btn btn-primary">Guardar Item</button>
      </div>
    </div>
  </div>
</div>
    @extends('ABMS.credito')
@endsection
