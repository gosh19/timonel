<div class="modal fade" id="modal-credito">
    <div class="modal-dialog bs-example-modal-lg modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">ABM Nota de Credito</h4>
            </div>

            <div class="modal-body" style="height: 450px;overflow-y: auto;">

                <input type="hidden" id="InputFormClienteCredito" name="cliente_id_credito" value="" placeholder="cliente">

                <!--<input type="text" name="empresa_id" value="">-->

                <input type="hidden" id="InputFormTotalCredito" name="total" value="" placeholder="total">

                <input type="hidden" name="usuario_id" value="{{ auth()->user()->id }}" id="UserId" placeholder="usuario id">

                <input type="hidden" id="allItems" name="items" value="" placeholder="items">



                <div class="tab-content">

                    <div class="active tab-pane" id="Factura">
                        <form id="formFactura" class="" action="/Facturar/{}" method="get" target="_blank">

                            <div class="form-group col-lg-6 col-sm-12">
                                <label for="exampleInputEmail1">Clientes</label>
                                <input type="text" class="form-control" name="clienteCredito" id="clienteCredito" value="">
                                <input type="hidden" class="form-control" name="SelectClienteCredito" id="SelectClienteCredito" value="">
                            </div>
                            <div class="form-group col-lg-2 col-sm-12">
                                <label for="exampleInputEmail1">Items</label><br>
                                <button type="button" onclick="agregarItemCredito();" class="btn btn-primary">Agregar Item</button>
                            </div>

                    </div>
                    <br>
                    <div class="col-lg-12">

                        <table id="tableCredito" class="table table-bordered table-striped">

                            <thead>
                            <tr>

                                <th class="text-center">Nombre</th>
                                <th class="text-center">IVA</th>
                                <th class="text-center">Precio</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-center">Subtotal</th>
                                <th class="text-center">Accion</th>

                            </tr>
                            </thead>
                            <tbody id="ResultsTableCredito" class="text-center">
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
                <div class="col-lg-4 text-center" id="TotalCreditoWithIva" style="font-size:2.4rem;font-weight:bold;">
                    $0.00
                </div>

                <input type="hidden" value="0.00" name="TotalFacturaCredito" id="TotalFacturaCredito">

                <input type="hidden" name = "itemsCredito" id ="itemsCredito" value="">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="SendCredito" onclick="crearNotaCredito();">Crear Nota de Credito</button>
            </div>

            </form>

        </div>

    </div>

</div>

<!--modal Item-->
<div class="modal fade" id="modal-item-credito" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Agregar Item</h5>
            </div>
            <div class="modal-body">
                <div class="col-xl-12 col-sm-12">
                    <label for="">Descripcion</label>
                    <input type="text" name="descripcionCredito" class="form-control" id="descripcionCredito" placeholder="Ingrese Descripcion">
                </div>
                <div class="col-xl-12 col-sm-12">
                    <label for="">Precio</label>
                    <input type="number" class="form-control" name="precioCredito" id="precioCredito" value="0.00">
                </div>
            </div>
            <br>
            <div class="modal-footer">
                <br>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" onclick="saveItemCredito();" class="btn btn-primary">Guardar Item</button>
            </div>
        </div>
    </div>
</div>

<script>

    function agregarItemCredito() {
      $('#modal-item-credito').modal('show');

    }
    function crearNotaCredito(){
        var items = [];
        $('#ResultsTableCredito tr').each(function () {
            var item = {
              "descripcion" :$(this).find("td").eq(0).html(),
                "iva"       : 21,
                "total"     :$(this).find("td").eq(2).html(),
                "cantidad"  :$(this).find("td").eq(3).html(),
                "subtotal"  :$(this).find("td").eq(4).html()
            };
            items.push(item);
        });
        var cliente_id= $('#SelectClienteCredito').val();
        var total = $('#TotalFacturaCredito').val();

        console.log(items,cliente_id,total);
    }
    function saveItemCredito(){
        var total = parseFloat($('#precioCredito').val());
        var descripcion = $('#descripcionCredito').val();
        var iva = parseFloat(total)*0.21;
        var bruto = total+iva;
        bruto = bruto.toFixed(2);
        var fila = '<tr>' +
                '<td>'+descripcion+'</td>' +
                '<td>21</td>'+
                '<td>'+total+'</td>'+
                 '<td>1</td>'+
                '<td>'+bruto+'</td>'+
                '<td><button type=\'button\' class=\'btn btn-danger btnDeleteCredito\'><i class=\'fa fa-eraser\'></i></button></td>'+
        '</td>';

        $('#ResultsTableCredito').append(fila);
        $('#modal-item-credito').modal('hide');
        $('#precioCredito').val('0.00');
        $('#descripcionCredito').val('');
        CalcularTotalCredito();

    }
    function CalcularTotalCredito(){
        var total = 0;
        $('#ResultsTableCredito tr').each(function () {
         var item = parseFloat($(this).find("td").eq(4).html());
         total = total+item;
        });

        $('#TotalCreditoWithIva').html('$'+total.toFixed(2));
        $('#TotalFacturaCredito').val(total);
    }

</script>