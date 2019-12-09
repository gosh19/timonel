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
              <br>
              <br>
              <table id="tableFacturas" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>#id</th>
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
                       <a href=""><button class="btn btn-success"><i class="fa fa-eye"></i></button></a>
                       </th>
                    </tr>
                  @endforeach
                </tbody>
                <tfoot>
                <tr>
                  <th>#id</th>
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

        tablefactura.on( 'select', function ( e, dt, type, indexes ) {
                  if ( type === 'row' ) {
                    var data = tablefactura.rows( indexes ).data();
                    var total = parseFloat(data[0][7]);
                    var totalactual = parseFloat($('#TotalFactura').val());
                    totalactual = total + totalactual;
                     $('#TotalFactura').val(totalactual);
                     totalactual = parseFloat(totalactual);
                     $('#TotalWithIva').html('$'+totalactual);
                    }
            });

            tablefactura.on( 'deselect', function ( e, dt, type, indexes ) {
                  if ( type === 'row' ) {
                    var data = tablefactura.rows( indexes ).data();
                    var total = parseFloat(data[0][7]);
                    var totalactual = parseFloat($('#TotalFactura').val());
                    totalactual =  totalactual - total ;
                     $('#TotalFactura').val(totalactual);
                     totalactual = parseFloat(totalactual);
                     $('#TotalWithIva').html('$'+totalactual);
                    }
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
           var editor;
            editor = new $.fn.dataTable.Editor( {
        table: "#tableProductos",
        ajax: "getProductos",
        fields: [ {
                label: "#id",
                name: "id"
            }, {
                label: "Codigo",
                name: "Codigo"
            }, {
                label: "Nombre",
                name: "Nombre"
            }, {
                label: "Precio",
                name: "Precio"
            }, {
                label: "Costo",
                name: "Costo"
            }, {
                label: "Iva",
                name: "Iva"
            }, {
                label: "Total",
                name: "Total",
            }, {
                label: "Stock",
                name: "Stock",
            }
        ]
    } );
 
    $('#tableProductos').on( 'click', 'tbody td:not(:first-child)', function (e) {
        editor.inline( this, {
            submit: 'allIfChanged'
        } );
    } );
    $('#tableProductos').DataTable( {
        dom: "Bfrtip",
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
            { data: "#id" },
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
            style:    'os',
            selector: 'td:first-child'
        },
        buttons: [
            { extend: "create", editor: editor },
            { extend: "edit",   editor: editor },
            { extend: "remove", editor: editor }
        ]
    } );


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
                results
                     .row( $(this).parents('tr') )
                     .remove()
                     .draw();
               
            });
            $('#saveProducto').click(function(){
               var table=$('#tableProductos').DataTable();
               var newRow="";
               var results = $('#tableFactura').DataTable();
               $.map(table.rows('.selected').data(), function (item) {
                console.log(item);

                results.row.add([
                    "",
                    item[1],
                    item[2],
                    item[3],
                    item[6],
                    item[4],
                    '<input type="number" class="form-control" value="1" style="width:75px;">',
                    item[7],
                    "<button type='button' class='btn btn-danger btnDelete'><i class='fa fa-eraser'></i></button>"
                ]).draw(false);

                });
                    $('#modal-productos').modal('hide');
                    $('#modal-default').modal('show');
           });

        });

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
                                    
                                <div class="form-group col-lg-6 col-sm-12">
                                    <label for="exampleInputEmail1">Clientes</label>
                                    <select type="text" class="form-control" id="SelectCliente">
                                        <option>--Seleccionar--</option>
                                        @foreach($Clientes as $Cliente)
                                            <option value="{{$Cliente->id}}">{{$Cliente->nombre}}</option>
                                        @endforeach
                                    </select>
                                </div> 
                                    
                                <div class="form-group col-lg-3 col-sm-12">
                                    <label for="exampleInputEmail1">Productos</label><br>
                                    <button class="btn btn-info" type="button" id="addProducto">Agregar Productos</button>                                    
                                </div>
                                <div class="form-group col-lg-3 col-sm-12">
                                    <label for="exampleInputEmail1">Servicios</label><br>
                                    <button type="button" id="addServicio" class="btn btn-primary">Agregar Servicios</button>
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
                                            <th class="text-center">Sub-total</th>
                                            <th class="text-center">Accion</th>
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
                    <input type="hidden" value="0.00" id="TotalFactura">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="SendFactura">Crear Factura</button>
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
                      <th>#id</th>
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
@endsection

