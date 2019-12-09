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


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Detalles de orden de pago</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          
        <div class="row">
            <div class="col-lg-12 py-2 mb-4">
                <div class="row">
                    <div class="col-lg-6 text-center">
                        Nª Orden de compra  
                    </div> 
                    <div class="col-lg-6 text-center">
                        Estado: Vencido  
                    </div> 
                </div>
            </div>
            <br>
            <hr>
            <div class="col-lg-12" id="DataOrdenPago">
                
            </div>
        </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<section class="content-header">
      <h1>
        Ordenes de Pago
        <!--<small>Agenda</small>-->
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fas fa-file-invoice-dollar"></i> Ordenes de Pago</a></li>
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
              <h3 class="box-title">Ordenes de Pago Registradas</h3>
            </div>

            <!-- /.box-header -->
            <div class="box-body">
            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-default">
                Nueva orden de pago
              </button>
              <br>
              <br>
              <table id="tableClientes" class="table table-bordered table-striped">
                <thead class="text-center">
                    <tr>
                        <th class="text-center">#id</th>
                        <th class="text-center">Fecha de pago</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($OPagos as $OPago)
                    <tr class="text-center DataOP" for="{{$OPago->ItemsFacturaPago}}" data-toggle="modal" data-target="#exampleModal">
                        <td>{{$OPago->id}}</td>
                        <td>{{$OPago->FechaPago}}</td>
                        <td>{{$OPago->estado}}</td>
                        <td>$ {{$OPago->Total}}</td>
                    </tr>
                    @endforeach
                </tbody>
                
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
    
        var TipoFact,PorID,PorFecha;
        var SelectOrder;
        var ItemsFacturaPago,JsonIDFacturaCompra,TotalB;
        var Items = new Array();
        var Ids = new Array();
        var Total_ = 0;
        var DataOP,DataOrdenPago;
        
        function detailsOP(e)
        {
            DataOrdenPago.innerHTML = "";
            
            ForOP = e.currentTarget.getAttribute('for');
            
            J_Op = JSON.parse(ForOP);
            
            
            
            for(f = 0; f < J_Op.length ; f++)
            {
                Father_ = document.createElement('div');
                Father_.className = 'col-lg-12';
                
                OP_Row_ = document.createElement('div');
                OP_Row_.className = 'row';
                
                for(z = 0; z < 6; z++)
                {
                    Cols = document.createElement('div');
                    Cols.className = 'col-lg-2 border text-center';
                    
                    if(z == 0)
                    {
                        TXtNode = document.createTextNode(J_Op[f].ID);
                        Cols.appendChild(TXtNode);
                        
                    }else if (z == 1)
                    {
                        TXtNode = document.createTextNode(J_Op[f].TipoFactura);
                        Cols.appendChild(TXtNode);
                        
                    }else if(z == 2)
                    {
                        TXtNode = document.createTextNode(J_Op[f].Empresa);
                        Cols.appendChild(TXtNode);
                        
                    }else if(z == 3)
                    {
                        TXtNode = document.createTextNode(J_Op[f].FechaCreacion);
                        Cols.appendChild(TXtNode);
                        
                    }else if(z == 4)
                    {
                        TXtNode = document.createTextNode(J_Op[f].FechaVto);
                        Cols.appendChild(TXtNode);
                        
                    }else if(z == 5)
                    {
                        TXtNode = document.createTextNode("$ " + J_Op[f].Total);
                        Cols.appendChild(TXtNode);
                    } 
                    
                    OP_Row_.appendChild(Cols);
                }
                
                Father_.appendChild(OP_Row_);
                
                br_ = document.createElement('br');
                Father_.appendChild(br_);
                
                DataOrdenPago.appendChild(Father_);
            }
            
        }
        
        
        function load()
        {
            DataOP = document.getElementsByClassName('DataOP');
            
            for(x = 0 ; x < DataOP.length ; x++)
            {
                DataOP[x].addEventListener('click',detailsOP,false);
            }
            
            DataOrdenPago = document.getElementById('DataOrdenPago');
            
            TipoFact = document.getElementById('TipoFact');
            PorID = document.getElementById('PorID');
            PorFecha = document.getElementById('PorFecha'); 
            
            SelectOrder = document.getElementById('SelectOrder');
            
            ItemsFacturaPago = document.getElementById('ItemsFacturaPago'); 
                    
            JsonIDFacturaCompra = document.getElementById('JsonIDFacturaCompra');
            
            TotalB = document.getElementById('TotalB');
            
            TipoFact.addEventListener('change',PorTipoFactura,'false');
            PorID.addEventListener('change',Por_ID,'false');
            PorFecha.addEventListener('change',Por_Fecha,'false');
            
            SelectOrder.addEventListener('change',SelectResult,'false');
        }
        
        function Por_Fecha()
        {            
            _path_ = "/BuscarFacturaCompra/PorFecha_" + PorFecha.value;
            
            FirstConex(_path_);
        }
        
        function Por_ID()
        {
            _path_ = "/BuscarFacturaCompra/PorID_" + PorID.value;
            
            FirstConex(_path_);
        }
        
        function PorTipoFactura()
        {
            ValueTypeFact = TipoFact.options[TipoFact.selectedIndex].value;
            
            _path_ = "/BuscarFacturaCompra/TipoFactura_" + ValueTypeFact;
            
            FirstConex(_path_);
        }
        
        
        function FirstConex(_path_)
        {
            FConex = new XMLHttpRequest();
            FConex.open('get',_path_,true);
            FConex.onreadystatechange = FirstStatus;
            FConex.send();
        }
        
        function FirstStatus()
        {
            if(FConex.readyState == 4)
            {
               Json = JSON.parse(FConex.response);
               
               SelectOrder.innerHTML = "";
               
               optionsBasic = document.createElement('option');
               
               DataBasicText = document.createTextNode('-Seleccionar-');
               
               optionsBasic.appendChild(DataBasicText);
               
               SelectOrder.appendChild(optionsBasic);
               
               for(i = 0; i < Json.length; i++)
               {
                   options = document.createElement('option');

                   options.setAttribute('value',Json[i].id + '_' + Json[i].tipocomprobante + '_' + Json[i].razon_social +  '_' + Json[i].fechadecarga + '_' +Json[i].vencimiento + '_' + Json[i].estado + '_' + Json[i].total_bruto);
                   
                   _TN_ = document.createTextNode(Json[i].id + ' - ' + Json[i].tipocomprobante + ' - ' + Json[i].razon_social);
                   
                   options.appendChild(_TN_);
                   
                   SelectOrder.appendChild(options);   
               }
            }
        }
        
        
        function SelectResult()
        {
            DataSelected = SelectOrder.options[SelectOrder.selectedIndex].value;
            
            SplitData = DataSelected.split('_');
            
            
            _tr_ = document.createElement('tr');
            _tr_.className = 'AllListRows';
            _tr_.setAttribute('for',SplitData[0] + '_' + SplitData[1] + '_' + SplitData[2] + '_' + SplitData[3] + '_' + SplitData[4] + '_' + SplitData[5] + '_' + SplitData[6]);
            
            for(j = 0 ; j < 8 ; j++)
            {
                td = document.createElement('td');
                td.className = 'text-center py-2';
                
                if(j == 0)
                {
                    TextNodeData = document.createTextNode(SplitData[0]);
                    td.appendChild(TextNodeData);
                    
                }else if(j == 1)
                {
                    TextNodeData = document.createTextNode(SplitData[1]);
                    td.appendChild(TextNodeData);
                    
                }else if( j == 2)
                {
                    TextNodeData = document.createTextNode(SplitData[2]);
                    td.appendChild(TextNodeData);
                    
                }else if(j == 3)
                {
                    TextNodeData = document.createTextNode(SplitData[3]);
                    td.appendChild(TextNodeData);
                    
                }else if( j == 4)
                {
                    TextNodeData = document.createTextNode(SplitData[4]);
                    td.appendChild(TextNodeData);
                    
                }else if(j == 5)
                {
                    TextNodeData = document.createTextNode(SplitData[5]);
                    td.appendChild(TextNodeData);
                    
                }
                else if(j == 6)
                {
                    TextNodeData = document.createTextNode(SplitData[6]);
                    td.appendChild(TextNodeData);
                    
                }else if (j == 7)
                {
                    btndelete = document.createElement('button');
                    btndelete.className = 'btn btn-danger DeleteSelected';
                    btndelete.setAttribute('type','button');
                    
                    icon_ = document.createElement('i');
                    icon_.className = 'fa fa-eraser';
                    
                    btndelete.appendChild(icon_);
                    
                    td.appendChild(btndelete);
                }
                
                _tr_.appendChild(td);
                
                AddRegister.appendChild(_tr_);
            }
            
            AllListRows = document.getElementsByClassName('AllListRows');
            DeleteSelected = document.getElementsByClassName('DeleteSelected');
            
            for(t = 0; t < DeleteSelected.length ; t++)
            {
                DeleteSelected[t].addEventListener('click',DeleteRow,false);
            }
                
            loadToHiddenInputs(AddRegister);    
            
        }
        
        function DeleteRow(e)
        {
            SelectedToDelete = e.currentTarget;
            TdDelete = SelectedToDelete.parentNode;
            TrDelete = TdDelete.parentNode;
            
            AddRegister.removeChild(TrDelete);
            
            loadToHiddenInputs();
        }
        
        function loadToHiddenInputs()
        {   
            Items = [];
            
            Ids = [];
            
            Total_ = 0;
            
            for(g = 0; g < AllListRows.length; g++) 
            {
                For_ = AllListRows[g].getAttribute('for');
                
                SplitToJson = For_.split('_');
                
                Suma_ = SplitToJson[6];
                
                Total_ = parseFloat(Total_) + parseFloat(Suma_);
                
                
                
                Items[g] = {
                     "ID":SplitToJson[0],
                     "TipoFactura":SplitToJson[1],
                     "Empresa":SplitToJson[2],
                     "FechaCreacion":SplitToJson[3],
                     "FechaVto":SplitToJson[4],
                     "Total":SplitToJson[6]
                 }; 
                
                ItemsEncoding = JSON.stringify(Items);
                
                ItemsFacturaPago.value = ItemsEncoding;
                
                
                Ids[g] = {
                     "ID":SplitToJson[0],
                 };
                 
                ItemsId = JSON.stringify(Ids);
                
                JsonIDFacturaCompra.value = ItemsId;
                
            }
            
            TotalB.value = Total_;
             
        }
    
        addEventListener('load',load,false);
    
    </script>


    <div class="modal fade" id="modal-default">
        <div class="modal-dialog bs-example-modal-lg modal-lg">
            <div class="modal-content">
                
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">ABM Ordenes de Pago</h4>
                </div>
                
                <form action="Crear Orden" method="post">
                    
                    <?php echo csrf_field(); ?>
                    
                    <input type="text" name="ItemsFacturaPago" id="ItemsFacturaPago">
                    
                    <input type="text" name="JsonIDFacturaCompra" id="JsonIDFacturaCompra">
                    
                    <input type="hidden" name="estado" value="Saldado">
              
                    <div class="modal-body" style="height: 450px;overflow-y: auto;">
                             
                            <div class="tab-content">
                                    
                                <div class="active tab-pane">
                                    
                                    <div class="col-lg-12">
                                        <h4 class="text-center">Busqueda de factura compra</h4>
                                        <hr>
                                    </div>
                                    
                                    <div class="form-group col-lg-4">
                                        <label>Posterior a esta fecha</label>
                                        <input class="form-control" type="date" id="PorFecha">
                                    </div>
                                    
                                    <div class="form-group col-lg-4">
                                        <label>Por ID</label>
                                        <input class="form-control" type="number" id="PorID">
                                    </div>
                                    
                                    <div class="form-group col-lg-4">
                                        <label for="">Por tipo de comprobante</label>
                                        <select class="form-control" id="TipoFact">
                                            <option>-Seleccionar-</option>
                                            <option value="Fac. A">Fac.A</option>
                                            <option value="Fac. B">Fac.B</option>
                                            <option value="Fac. C">Fac.C</option>
                                            <option value="Ret. IIBB">Ret.IIBB</option>
                                            <option value="Ret.Ganacias">Ret.Ganacias</option>
                                            <option value="Ret.IVA">Ret.IVA</option>
                                            <option value="Ret.Ingresos Patronales">Ret.Ingresos Patronales</option>
                                            <option value="Ret.Cont.Seg.Social">Ret.Cont.Seg.Social</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-lg-12">
                                        <h4 class="text-center">Seleccion de elemetos</h4>
                                        <hr>
                                    </div>
                                    
                                    <div class="form-group col-lg-4 col-sm-12">
                                        <label for="exampleInputEmail1">Seleccionar Factura de compras</label>
                                        <select type="text" class="form-control" id="SelectOrder">
                                            <option>--Seleccionar--</option>    
                                        </select>
                                    </div>
                                    
                                    <div class="form-group col-lg-4 col-sm-12">
                                        <label for="exampleInputEmail1">Fecha de pago</label>
                                        <input type="date" class="form-control" name="FechaPago">
                                    </div>
                                    
                                    <div class="form-group col-lg-4 col-sm-12">
                                        <label for="exampleInputEmail1">Forma de pago</label>
                                        <select type="text" class="form-control" class="FormaPago" name="FormaPago">
                                            <option>--Seleccionar--</option>  
                                            <option value="Efectivo">Efectivo</option>  
                                            <option value="Cheque">Cheque</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-lg-12">
                                        <table class="table table-stripped" id="AddRegister">
                                            <tr>
                                                <th class="text-center">#ID</th>
                                                <th class="text-center">Tipo de factura</th>
                                                <th class="text-center">Empresa</th>
                                                <th class="text-center">Fecha de creacion</th>
                                                <th class="text-center">Fecha de Vencimiento</th>
                                                <th class="text-center">Estado</th>
                                                <th class="text-center">Total Bruto</th>
                                                <th class="text-center">Borrar</th>
                                            </tr>
                                    
                                        </table>   
                                        <hr>
                                    </div>
                                    
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-lg-4"></div>
                                            <div class="col-lg-4"></div>
                                            <div class="col-lg-4">
                                                <label>Total:</label>
                                                <input type="text" class="form-control" name="Total" value="" id="TotalB">
                                            </div>
                                        </div>
                                    </div>
                                        
                                </div>
                                
                            </div>
                        
                    
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button class="btn btn-primary" id="SendFactura">Crear Factura</button>
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
                    <th>#id</th>
                    <th>Tipo Comprobante</th>
                    <th>pv</th>
                    <th>Numero</th>
                    <th>Vto</th>
                    <th>Estado</th>
                    <th>Metodo de pago</th>
                  </tr>
                  </thead>
                  <tbody>
                    
                        
                      
                  
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