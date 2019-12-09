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

<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>


<script>

    var InfoView,DetailsViewLayout; 
    
    var NameView = ['tipo','importe','grabado','total'];
    
    function Load_Info()
    {   
        InfoView = document.getElementsByClassName('InfoView');
        DataDetailsText = document.getElementsByClassName('DataDetailsText');
        
        DetailsViewLayout = document.getElementById('DetailsViewLayout');
        
        for(p = 0; p < InfoView.length; p++)
        {
            InfoView[p].addEventListener('click',AddToViewDetails,false);
        }
    }
    
    function AddToViewDetails(e)
    {
        DataForView = e.currentTarget.getAttribute('for');
        
        _DataSplit_ = DataForView.split('_');
        
        DataDetailsText[0].innerHTML = "Detalles Factura Compra Nª " + _DataSplit_[0];
        DataDetailsText[1].innerHTML = _DataSplit_[1];
        DataDetailsText[2].innerHTML = _DataSplit_[2];
        DataDetailsText[3].innerHTML = _DataSplit_[3];
        DataDetailsText[4].innerHTML = _DataSplit_[9];
        DataDetailsText[5].innerHTML = _DataSplit_[8];
        DataDetailsText[6].innerHTML = "$ " + _DataSplit_[10];
        
        Jitems = JSON.parse(_DataSplit_[11]);
        
        DetailsViewLayout.innerHTML = "";
        
        for(i = 0; i < Jitems.length ; i++)
        {
            View_row = document.createElement('div');
            View_row.className = 'row';
            
            for(k = 0 ; k < NameView.length ; k++)
            {
                ViewCols = document.createElement('div');
                ViewCols.className = 'col-lg-3 text-center py-3';    
                
                NameTotally = NameView[k];
                
                ViewTextNode = document.createTextNode(Jitems[i][NameTotally]);
                
                ViewCols.appendChild(ViewTextNode);
                
                View_row.appendChild(ViewCols);
            }
            
            DetailsViewLayout.appendChild(View_row);
            
        }
    }
    
    addEventListener('click',Load_Info,false);

</script>

<script>

    

    var PathToDelete,Selected_;

    function loadDelete()
    {
        Delete = document.getElementsByClassName('Delete');
        
        for(x = 0; x < Delete.length ; x++)
        {
            Delete[x].addEventListener('click',DeleteSelected,false);            
        }
    }
    
    function DeleteAjax()
    {    
        Conex_ = new XMLHttpRequest();
        Conex_.open('get',PathToDelete,true);
        Conex_.onreadystatechange = StatusDelete;
        Conex_.send();
    }
    
    function StatusDelete()
    {
        if(Conex_.readyState == 4)
        {
            TdParent = Selected_.parentNode;  
            TrParent = TdParent.parentNode;
            TbodyParent = TrParent.parentNode;
            
            TbodyParent.removeChild(TrParent);
        }
    }
    
    function DeleteSelected(e)
    {
        
        Selected_ = e.currentTarget;
        
        PathToDelete = e.currentTarget.getAttribute('for');
        
        
        
        swal({
          title: "Estas por borrar un registro",
          text: "Deseas borrar el registro seleccionado?",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
              
              DeleteAjax();
              
              swal("Borrado Correctamente!", {
              icon: "success",
            });
          } else {
              swal({
                  title:"Uf! Tu registro esta a salvo!",
                  icon:"success",
              });
          }
        });     
    }
    addEventListener('load',loadDelete,false);
    
    
    
</script>

<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title DataDetailsText" id="exampleModalCenterTitle">Detalles Factura Compra Nª 1450</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="col-xl-12">
              <div class="row">
                <div class="col-lg-4 border"> 
                    <h4 class="text-center">Tipo de Factura:<h4>
                    <p class="text-center DataDetailsText"><strong>Fac.A</strong></p>
                </div>
                <div class="col-lg-4 border"> 
                    <h4 class="text-center">Razon Social:<h4>
                    <p class="text-center DataDetailsText"><strong>Alarmas Guerrero</strong></p>
                </div>
                <div class="col-lg-4 border"> 
                    <h4 class="text-center">CUIT:<h4>
                    <p class="text-center DataDetailsText"><strong>30711654093</strong></p>
                </div>
              </div>
          </div>
          <br><br>
          <div class="col-xl-12">
            <div class="row">
                <div class="col-lg-3 text-center">
                    <strong>Tipo</strong>
                </div>
                <div class="col-lg-3 text-center">
                    <strong>Importe</strong>
                </div>
                <div class="col-lg-3 text-center">
                    <strong>Grabado</strong>
                </div>
                <div class="col-lg-3 text-center">
                    <strong>Total</strong>
                </div>
            </div>
            <hr>
        </div>
        <div class="col-xl-12" id="DetailsViewLayout">
            
        </div>
      </div>
      <div class="modal-footer">
        <div class="col-xl-12">
            <div class="row">
                <div class="col-lg-4">
                    <h4 class="text-center">Forma de pago:</h4>
                    <h4 class="text-center DataDetailsText"></h4>
                </div> 
                <div class="col-lg-4">
                    <h4 class="text-center">Estado:</h4>
                    <h4 class="text-center DataDetailsText"></h4>
                </div> 
                <div class="col-lg-4">
                    <h4 class="text-center">Total:</h4>
                    <h4 class="text-center DataDetailsText">$ 0.00</h4>
                </div>
            </div>    
        </div>
      </div>
    </div>
  </div>
</div>

<section class="content-header">
      <h1>
        Facturas
        <!--<small>Agenda</small>-->
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fas fa-file-invoice-dollar"></i> Facturas Compras</a></li>
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
              <h3 class="box-title">Factura Compras Registradas</h3>
            </div>

            <!-- /.box-header -->
            <div class="box-body">
            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-default">
                Agregar Factura Compra
              </button>
              <br>
              <br>
              <table id="tableClientes" class="table table-bordered table-striped">
                <thead class="text-center">
                    <tr>
                        <th class="text-center">#id</th>
                        <th class="text-center">Tipo Comprobante</th>
                        <th class="text-center">Razon Social</th>
                        <th class="text-center">CUIT</th>
                        <th class="text-center">P.V</th>
                        <th class="text-center">Numero</th>
                        <th class="text-center">Fecha de Carga</th>
                        <th class="text-center">VTO</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center">Total Bruto</th>
                        <th class="text-center">Aciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($FacturaCompra as $FacsCompra)
                      <tr>
                         <td class="text-center">{{$FacsCompra->id}}</td>
                         <td class="text-center">{{$FacsCompra->tipocomprobante}}</td>
                         <td class="text-center">{{$FacsCompra->razon_social}}</td>
                         <td class="text-center">{{$FacsCompra->cuit}}</td>
                         <td class="text-center">{{$FacsCompra->pv}}</td>
                         <td class="text-center">{{$FacsCompra->numero}}</td>
                         <td class="text-center">{{$FacsCompra->fechadecarga}}</td>
                         <td class="text-center">{{$FacsCompra->vencimiento}}</td>
                         <td class="text-center">{{$FacsCompra->estado}}</td>
                         <td class="text-center">$ {{$FacsCompra->total_bruto}}</td>
                         <td class="text-center">
                             <button class="btn btn-info InfoView" 
                                     for="{{$FacsCompra->id}}_{{$FacsCompra->tipocomprobante}}_{{$FacsCompra->razon_social}}_{{$FacsCompra->cuit}}_{{$FacsCompra->pv}}_{{$FacsCompra->numero}}_{{$FacsCompra->fechadecarga}}_{{$FacsCompra->vencimiento}}_{{$FacsCompra->estado}}_{{$FacsCompra->metodopago}}_{{$FacsCompra->total_bruto}}_{{$FacsCompra->items}}" data-toggle="modal" data-target="#exampleModalCenter">
                                 <i class="fa fa-eye"></i>
                             </button>
                             <button for="/FacturaCompra/Delete/{{$FacsCompra->id}}" class="btn btn-danger Delete">
                                 <i class="fa fa-eraser"></i>
                             </button>
                         </td>
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
    
        var SelectProviders;
        var Conex;
        var Data;
        var InpRazonSocial,InpCuit,Subtotal,ExeAddItem,ItemSelected,tbody;
        var Bruto,JsonData,Importe,Grabado,Tipo,PartialTotal,DeleteItems,SendFactura;
        
        var Items = new Array();
        
        function load()
        {
            SelectProviders = document.getElementById('SelectProviders');
            InpRazonSocial = document.getElementById('RazonSocial');
            InpCuit = document.getElementById('Cuit');
            AddItem = document.getElementById('AddItem');
            Subtotal = document.getElementById('Subtotal');
            ExeAddItem = document.getElementById('ExeAddItem');
            tbody = document.getElementById('tbody');
            Bruto = document.getElementById('Bruto');
            JsonData = document.getElementById('JsonData');
            SendFactura = document.getElementById('SendFactura');
            
            SelectProviders.addEventListener('change',ToChange,false);
            ExeAddItem.addEventListener('click',AddToItem,false);
            SendFactura.addEventListener('click',ToJson,false);
        }
        
        function AddToItem()
        {
            ItemSelected = AddItem.options[AddItem.selectedIndex].value;
            
            Calculate(ItemSelected);
        }
        
        function Calculate(_Iva_)
        {
            
            
            if(_Iva_ == 'IVA 21%')
            {
                PreIva = 21;  
                Calculo(PreIva);
                
                InfoFact = [ItemSelected,Iva,ValueInp,Total];
                
            }else if(_Iva_ == 'IVA 27%')
            {
                PreIva = 27;  
                Calculo(PreIva);
                
                InfoFact = [ItemSelected,Iva,ValueInp,Total];
            }else if(_Iva_ == 'IVA 10.5%')
            {
                PreIva = 10.5; 
                Calculo(PreIva);
                
                InfoFact = [ItemSelected,Iva,ValueInp,Total];
            }else {
                Total = Subtotal.value;
                InfoFact = [ItemSelected,'0',Total,Total];
                
                console.log(Total);
                
            }
            
            CreateRowTable();
            
            BrutoToatal();
        }
        
        function BrutoToatal()
        {
            PartialTotal = document.getElementsByClassName('PartialTotal');
            Importe = document.getElementsByClassName('Importe');
            Grabado = document.getElementsByClassName('Grabado');
            Tipo = document.getElementsByClassName('Tipo');
            DeleteItems = document.getElementsByClassName('DeleteItems');
            console.log(DeleteItems);
            TotalCalculate = 0;
            
            for(x = 0; x < PartialTotal.length ; x++)
            {
                TotalCalculate = parseFloat(TotalCalculate) + parseFloat(PartialTotal[x].innerHTML);
                
                DeleteItems[x].addEventListener('click',DeleteItem,false);
            }
            
            TotalCalculate = TotalCalculate.toFixed(2);
            
            Bruto.value = TotalCalculate;
            
            ToJson();
           
        }
        
        function CreateRowTable() 
        {
            console.log('aca')
            
            tr_ = document.createElement('tr');
            
            for(i = 0 ; i < 5 ; i++)
            {
                console.log(InfoFact[i]);
                
                if(i == 4)
                {
                    td_ = document.createElement('td');
                    
                    deleteBtn = document.createElement('button');
                    deleteBtn.className = 'btn btn-danger DeleteItems';
                    deleteBtn.setAttribute('type','button');
                    
                    deleteIcon = document.createElement('i');
                    deleteIcon.className = 'fa fa-eraser';
                    
                    deleteBtn.appendChild(deleteIcon);
                    td_.appendChild(deleteBtn);
                }else {
                    
                    td_ = document.createElement('td');
                    
                    if(i == 0)
                    {
                        td_.className = 'Tipo';
                    }
                    if(i == 1)
                    {
                        td_.className = 'Importe';
                    }
                    if(i == 2)
                    {
                        td_.className = 'Grabado';
                    }
                    if(i == 3)
                    {
                        td_.className = 'PartialTotal';
                    }
                    
                    
                    NodeText = document.createTextNode(InfoFact[i]);
                    
                    td_.appendChild(NodeText); 
                }
                
                tr_.appendChild(td_);
                
                
            }
            
            tbody.appendChild(tr_);
        }
        
        function Calculo(_PreIva_)
        {
            ValueInp = Subtotal.value;
            
            Iva = ValueInp / 100 * _PreIva_;
                
            Total = parseFloat(ValueInp) + parseFloat(Iva);
            
            Total = Total.toFixed(2);
            
            
        }
        
        function ToJson()
        {
            Items = [];
            
            for(g = 0; g < PartialTotal.length; g++)
            {
                Items[g] = {
                    "tipo":Tipo[g].innerHTML,
                    "importe":Importe[g].innerHTML,
                    "grabado":Grabado[g].innerHTML,
                    "total":PartialTotal[g].innerHTML
                };     
            }
            
            ItemsEncoding = JSON.stringify(Items);
            
            JsonData.value = ItemsEncoding;
        }
        
        function DeleteItem(e)
        {
            btn_Todelete = e.currentTarget;
            
            td_ToDelete = btn_Todelete.parentNode;
            
            tr_ToDelete = td_ToDelete.parentNode;
            
            Tbody_ToDelete = tr_ToDelete.parentNode;
            
            Tbody_ToDelete.removeChild(tr_ToDelete);
            
            BrutoToatal();
        }
        
        function ToChange()
        {
            ValueProvider = SelectProviders.options[SelectProviders.selectedIndex].value;
            QueryProviders(ValueProvider);
        }
    
        function QueryProviders(_id_)
        {
            Conex = new XMLHttpRequest();    
            Conex.open('get','/ProviderQuery/' + _id_,true);
            Conex.onreadystatechange = Status;
            Conex.send();
        }
        
        function Status()
        {
            if(Conex.readyState == 4)
            {
               Data = JSON.parse(Conex.response);
               
               InpRazonSocial.value = Data.razon_social;
               
               InpCuit.value = Data.cuit;
               
            }
        }
    
        addEventListener('load',load,false);
    </script>
    


    <div class="modal fade" id="modal-default">
        <div class="modal-dialog bs-example-modal-lg modal-lg">
            <div class="modal-content">
                <form action="Crear FacturaCompra" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">ABM Factura Compras</h4>
                </div>
              
                <div class="modal-body" style="height: 450px;overflow-y: auto;">
                    
                        <?php echo csrf_field(); ?>
                         
                        <div class="tab-content">
                                
                            <div class="active tab-pane">
                                
                                <input type="hidden" name="usuario" value="{{auth()->user()->id}}">
                                
                                <input type="hidden" name="sucursalusuario" value="{{auth()->user()->empresa_id}}">
                                
                                <input type="hidden" class="form-control" id="RazonSocial" name="razon_social">
                                
                                <input type="hidden" class="form-control" id="Cuit" name="cuit">
                                
                                <input type="hidden" class="form-control" name="estado" value="Impago">
                                
                                <input type="hidden" class="form-control" name="metodopago" value="Efectivo">
                                
                                
                                
                                <div class="form-group col-lg-6 col-sm-12">
                                    <label for="exampleInputEmail1">Proveedor</label>
                                    <select type="text" class="form-control" id="SelectProviders">
                                        <option>--Seleccionar--</option>
                                        @foreach($Providers as $Providers_)
                                            <option value="{{$Providers_->id}}">{{$Providers_->razon_social}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="col-lg-12">
                                    <hr>
                                </div>
                                                            
                                <div class="form-group col-lg-4 col-sm-12">
                                    <label for="exampleInputEmail1">Tipo</label>
                                    <select type="text" class="form-control" name="tipocomprobante" required>
                                        <option>--Seleccionar--</option>
                                        <option value="Fac. A">Fac. A</option>
                                        <option value="Fac. B">Fac. B</option>
                                        <option value="Fac. C">Fac. C</option>
                                        <option value="Ret. IIBB">Ret. IIBB</option>
                                        <option value="Ret. Ganacias">Ret. Ganacias</option>
                                        <option value="Ret. IVA">Ret. IVA</option>
                                        <option value="Ret. Ingresos Patronales">Ret. Ingresos Patronales</option>
                                        <option value="Ret.Cont.Seg.Social">Ret.Cont.Seg.Social</option>
                                    </select>
                                </div>
                                
                                <div class="form-group col-lg-4 col-sm-12">
                                    <label for="exampleInputEmail1">Fecha Comprobante</label>
                                    <input type="date" class="form-control" name="fechadecarga" required>
                                </div>
                                
                                
                                
                                <div class="form-group col-lg-4 col-sm-12">
                                    <label for="exampleInputEmail1">Pv</label>
                                    <input type="number" class="form-control" name="pv" required>
                                </div>
                                
                                <div class="form-group col-lg-4 col-sm-12">
                                    <label for="exampleInputEmail1">Numero</label>
                                    <input type="number" class="form-control" name="numero" required>
                                </div>
                                
                               
                                    
                                
                                
                                <div class="form-group col-lg-4 col-sm-12">
                                    <label for="exampleInputEmail1">Vencimiento</label>
                                    <input type="date" class="form-control" name="vencimiento" required>
                                </div>
                                 
                                 <div class="col-lg-12">
                                    <hr>
                                </div>
                                
                                <div class="form-group col-lg-3 col-sm-12">
                                    
                                    <label for="exampleInputEmail1">Elegir Item</label>
                                    <select type="text" class="form-control" id="AddItem" required>
                                        <option>--Seleccionar--</option>
                                        <option value="IVA 27%">IVA 27%</option>
                                        <option value="IVA 21%">IVA 21%</option>
                                        <option value="IVA 10.5%">IVA 10.5%</option>
                                        <option value="IIBB">IIBB</option>
                                        <option value="No Gravado">No Grabado</option>
                                        <option value="Impuesto Interno">Impuesto Interno</option>
                                        <option value="Excento">Excento</option>
                                        <option value="Ganacias">Ganacias</option>
                                        <option value="Percep.IVA">Percep.IVA</option>
                                    </select>
                                </div>
                                
                                <div class="form-group col-lg-3 col-sm-12">
                                    <label for="exampleInputEmail1">Sub-Total</label>
                                    <input type="number" class="form-control" id="Subtotal">
                                </div>
                                <div class="form-group col-lg-3 col-sm-12" style="margin-top:22px;">
                                    <button type="button" class="btn btn-primary" id="ExeAddItem">Agregar Item</button>
                                </div>
                                    
                            </div>
                            
                            <div class="col-lg-12">
                                <br>
                                <br>
                                
                                <table class="table table-stripped">
                                            
                                    <thead>
                                        <tr>
                                            <th class="text-center">Tipo</th>
                                            <th class="text-center">Importe</th>
                                            <th class="text-center">Grabado</th>
                                            <th class="text-center">Total</th>
                                            <th class="text-center">Eliminar</th>
                                        </tr>
                                    </thead>
                                            
                                    <tbody class="text-center" id="tbody">
                                            
                                    </tbody>
                                            
                                </table> 
                                    
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-3 text-center border">
                                            <label for="exampleInputEmail1">Total Bruto</label>
                                            <input type="text" class="form-control" id="Bruto" name="total_bruto">    
                                        </div>
                                        
                                        <input type="hidden" value="" id="JsonData" name="items">
                                        </div>
                                    </div>
                                </div>
                                    
                            </div>
                                
                        </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button class="btn btn-primary" id="SendFactura">Crear Factura</button>
                </div>
                
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