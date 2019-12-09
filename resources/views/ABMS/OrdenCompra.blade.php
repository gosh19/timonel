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

<script>

    var ProcSelected,Con,Tbody,InpuTotal,InpuTotal2,ItemsJson;

    function load()
    {
        ProcSelected = document.getElementById('ProcSelected');
        Tbody = document.getElementById('Tbody');
        
        InpuTotal = document.getElementById('InpuTotal');
        InpuTotal2 = document.getElementById('InpuTotal2');
        ItemsJson = document.getElementById('ItemsJson');
        
        ProcSelected.addEventListener('change',SelectProc,false);
    }
    
    function SelectProc() 
    {
        IDProc = ProcSelected.options[ProcSelected.selectedIndex].value;
        
        Conex(IDProc);
    }
    
    function Conex(_IdProc_)
    {  
        Con = new XMLHttpRequest();
        Con.open('get','/OrdendeCompra/BuscarProducto/' + _IdProc_,true);
        Con.onreadystatechange = Status;
        Con.send();
    }
    
    function Status()
    {
        if(Con.readyState == 4)
        {
            _Js_ = JSON.parse(Con.response);
            
            CreateTable();
        }
    }
    
    function CreateTable()
    {
        for(i = 0; i < _Js_.length ; i++)
        {
            Tr_ = document.createElement('tr');
            Tr_.className = 'row DataforToJson';
            Tr_.setAttribute('for',_Js_[i].id +'_'+_Js_[i].id +'_'+_Js_[i].codigo +'_'+_Js_[i].nombre +'_'+_Js_[i].stock_sugerido +'_'+_Js_[i].costo);
            
            for(x = 0 ; x < 6 ; x++)
            {
                Td_ = document.createElement('td');
                Td_.className = 'text-center';
                
                if(x == 0)
                {
                    TxtNode = document.createTextNode(_Js_[i].id);
                    Td_.appendChild(TxtNode);
                    Tr_.appendChild(Td_);
                    
                }else if(x == 1) 
                {
                    TxtNode = document.createTextNode(_Js_[i].codigo);
                    Td_.appendChild(TxtNode);
                    Tr_.appendChild(Td_);
                    
                    
                }else if(x == 2)
                {
                    TxtNode = document.createTextNode(_Js_[i].nombre);
                    Td_.appendChild(TxtNode);
                    Tr_.appendChild(Td_);
                    
                    
                }else if(x == 3)
                {
                    TxtNode = document.createTextNode(_Js_[i].stock_sugerido);
                    Td_.appendChild(TxtNode);
                    Tr_.appendChild(Td_);
                }
                
                else if(x == 4)
                {
                    TxtNode = document.createTextNode(_Js_[i].costo);
                    Td_.appendChild(TxtNode);
                    Td_.className = 'ValorCosto'
                    Tr_.appendChild(Td_);
                }
                else if(x == 5)
                {
                    ButtonDelete = document.createElement('button');
                    ButtonDelete.className= "btn btn-danger DeleteButton";
                    ButtonDelete.setAttribute('type','button');
                    
                    IconDelete = document.createElement('i');
                    IconDelete.className = "fa fa-eraser";
                    
                    ButtonDelete.appendChild(IconDelete);
                    
                    Td_.appendChild(ButtonDelete);
                    Tr_.appendChild(Td_);
                    
                }
                
                Tbody.appendChild(Tr_);   
            }
        }
        
        ValorCosto = document.getElementsByClassName('ValorCosto');
        DataforToJson = document.getElementsByClassName('DataforToJson');
        DeleteButton = document.getElementsByClassName('DeleteButton');
        
        for(r = 0; r < DeleteButton.length ; r++)
        {
            DeleteButton[r].addEventListener('click',DeleteItem,false);
        }
        
        Suma();
    }
    
    function DeleteItem(e)
    {
        ButtonDeleteToRow = e.currentTarget;
        TdDelete = ButtonDeleteToRow.parentNode;
        TrDelete = TdDelete.parentNode;
        
        Tbody.removeChild(TrDelete);
        
        Suma();
    }
    
    function Suma()
    {
        
        _Costo_ = 0;
        
        for(j = 0 ; j < ValorCosto.length ; j++)
        {
           _Costo_ = parseFloat(_Costo_) + parseFloat(ValorCosto[j].innerHTML); 
        }
        
        InpuTotal.value = "Total : $ " + _Costo_;
        InpuTotal2.value = _Costo_;
        
        ToJson();
    }
    
    function ToJson()
    {
        Items = [];
        
        for(p = 0; p < DataforToJson.length ; p++)
        {
            DataFor = DataforToJson[p].getAttribute('for');
            
            _Data_ =DataFor.split('_');
            
            Items[p] = {
              "ID":_Data_[1],
              "codigo":_Data_[2],
              "nombre":_Data_[3],
              "stock":_Data_[4],
              "costo":_Data_[5]
            };
            
            DataJsonParse = JSON.stringify(Items);
        }
        
        ItemsJson.value = DataJsonParse;
        
        console.log(DataJsonParse);
    }
    
    addEventListener('load',load,false);


</script>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="crear orden de compra" method="post">
                
                <?php echo csrf_field(); ?>
                
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Nueva Orden de compra</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                    <div class="row">
                        <div class="col-lg-6">
                            <label>Seleccionar Productos</label>
                            <select name="" class="form-control" id="ProcSelected">
                                <option>-Seleccionar-</option>
                                @foreach($products as $proc)
                                    <option value="{{$proc->id}}">{{$proc->id}} - {{$proc->nombre}} - {{$proc->costo}} - {{$proc->stock_sugerido}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label>Seleccionar Proveedor</label>
                            <select class="form-control" name="proveedor">
                                <option>-Seleccionar-</option>
                                @foreach($provider as $prov)
                                    <option value="{{$prov->id}}">{{$prov->razon_social}}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-lg-12">
                            <hr>
                            <table class="table table-stripped">
                                <thead>
                                    <tr>
                                        <th class="text-center"></th>
                                        <th class="text-center">ID#</th>
                                        <th class="text-center">Codigo</th>
                                        <th class="text-center">Nombre</th>
                                        <th class="text-center">Stock</th>
                                        <th class="text-center">Precio</th>
                                        <th class="text-center">Borrar</th>
                                    </tr>
                                </thead>
                                <tbody id="Tbody">
                                    
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-4">
                                    
                                    <input type="text" class="form-control text-center" value="" id="InpuTotal" disabled>
                                    
                                    <input type="hidden" class="form-control text-center" value="" name="Total" id="InpuTotal2">
                                    
                                    <input type="hidden" id="ItemsJson" name="Items">
                                    
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                    
                                    <input type="hidden" name="user_name" value="{{ auth()->user()->name }}">
                                    
                                    <input type="hidden" name="user_empresa" value="{{ auth()->user()->empresa_id }}">
                                    
                                </div>
                            </div>
                            <hr>
                        </div>
                        
                    </div>
                   
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<section class="content-header">
      <h1>
        Ordenes de compra
        <!--<small>Agenda</small>-->
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fas fa-file-invoice-dollar"></i> Ordenes de compras</a></li>
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
              <h3 class="box-title">Compras realizadas</h3>
            </div>

            <!-- /.box-header -->
            <div class="box-body">
            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#exampleModal">
                Nueva orden de compra
              </button>
              <br>
              <br>
              <table id="tableClientes" class="table table-bordered table-striped">
                <thead class="text-center">
                    <tr>
                        <th class="text-center">#id</th>
                        <th class="text-center">Hora enviado</th>
                        <th class="text-center">fecha Enviado</th>
                        <th class="text-center">Solicitante</th>
                        <th class="text-center">Proveedor</th>
                        <th class="text-center">Total</th>
                        <th class="text-center">Anular</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($OrderCompra as $OCompra)
                    <tr>
                        <td class="text-center">{{$OCompra->id}}</td>
                        
                        @php 
                        
                            $TimeSend = explode(" ",$OCompra->created_at);
                        @endphp
                        
                        <td class="text-center">{{$TimeSend[0]}}</td>
                        <td class="text-center">{{$TimeSend[1]}}</td>
                        <td class="text-center">{{$OCompra->user_name}}</td>
                        <td class="text-center">{{$OCompra->proveedor}}</td>
                        <td class="text-center">$ {{$OCompra->Total}}</td>
                        <td class="text-center">
                            <button class="btn btn-danger">
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
    
@endsection