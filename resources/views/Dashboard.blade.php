
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
@if(auth()->user()->categoria != 3)
    var area = new Morris.Area({
     element: 'revenue-chart',
     resize: true,
     data: [

         @php
         $year=date('Y');
         $month=date('m');
         $from = date($year.'-'.$month.'-01');
         $to = date($year.'-'.$month.'-t');
         $from1 = new DateTime($from);
         $to1 = new DateTime($to);
         $interval = DateInterval::createFromDateString('1 day');
         $period = new DatePeriod($from1, $interval, $to1);
         $control = 0;
         $data = "";
         foreach ($period as $dt) {
                  $control++;
                  $dia = $dt->format("Y-m-d");
                  $total = 0;
                  foreach($facturaMes as $factura){
                    $fechaFactura = $factura->created_at;
                    $fechaFactura = date("Y-m-d", strtotime($fechaFactura));;
                     if($dia == $fechaFactura) {
                        $total = $total + (float)$factura->ImpTotal;
                     }
                  }
                  foreach($ventaMes as $factura){
                    $fechaFactura = $factura->created_at;
                    $fechaFactura = date("Y-m-d", strtotime($fechaFactura));;
                     if($dia == $fechaFactura) {
                        $total = $total + (float)$factura->subtotal;
                     }
                  }

                  $data .="{y: '".$dia."', item1: ".$total.", item2: 0},";

           }
               $data = substr($data,0, strlen($data) - 1);
          @endphp
          {!! $data !!}
     ],
     xkey: 'y',
     ykeys: ['item1', 'item2'],
     labels: ['Ventas', 'Gastos'],
     lineColors: ['#a0d0e0', '#3c8dbc'],
     hideHover: 'auto'
   });
});
@endif
</script>
@if(auth()->user()->categoria == 3)
<section class="content">
  <div class="row" >
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-aqua"><i class="fas fa-envelope"></i></span>

        <div class="info-box-content">
          <span class="info-box-text" style="font-size: 10px !important;">Ordenes de Compra Aprobadas</span>
          <span class="info-box-number">{{count($ComprasAutorizadas)}}<small>de {{count($ordenCompras)}}</small></span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-green"><i class="fas fa-flag"></i></span>

        <div class="info-box-content">
          <span class="info-box-text" style="font-size: 10px !important;">Ordenes de Ventas a Cerrar</span>
          <span class="info-box-number">{{count($presupuestos)}}</span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
         <div class="info-box">
           <span class="info-box-icon bg-yellow"><i class="fas fa-paste"></i></span>

           <div class="info-box-content">
             <span class="info-box-text" style="font-size: 10px !important;">Remitos Pendientes</span>
             <span class="info-box-number">{{count($remitosPendientes)}}</span>
           </div>
           <!-- /.info-box-content -->
         </div>
         <!-- /.info-box -->
       </div>
       <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-red"><i class="far fa-star"></i></span>

          <div class="info-box-content">
            <span class="info-box-text">Total fac. dia</span>
            <span class="info-box-number">{{$facturaDiaria->sum('ImpTotal')}}</span>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
      </div>
  </div>
  <div class="row">
    <div class="col-lg-6 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-aqua">
        <div class="inner" style="text-align:center;">
          <h3>{{count($clientes)}}</h3>

          <p>Clientes</p>
        </div>

        <a href="ClientesAgenda" class="small-box-footer">Ir a Clientes <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-6 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-aqua">
        <div class="inner" style="text-align:center;">
          <h3>{{count($proveedores)}}</h3>

          <p>Proveedores</p>
        </div>

        <a href="Proveedores" class="small-box-footer">Ir a Proveedores <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
   </div>

     <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3>{{count($facturas)}}</h3>

              <p>Facturas</p>
            </div>
            <div class="icon">
               <i class="fas fa-file-invoice-dollar"></i>
            </div>
            <a href="Facturacion" class="small-box-footer">Ir a Facturas <i class="fa fa-arrow-circle-right"></i></a>

          </div>
        </div>

        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3>{{count($recibos)}}</h3>

              <p>Recibos</p>
            </div>
            <div class="icon">
              <i class="fas fa-shopping-bag"></i>
            </div>
            <a href="Recibos" class="small-box-footer">Ir a Recibos <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>


        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-blue">
            <div class="inner">
              <h3>{{count($remitos)}}</h3>

              <p>Remitos</p>
            </div>
            <div class="icon">
                <i class="fas fa-truck-loading"></i>
            </div>
            <a href="Remito" class="small-box-footer">Ir a Remitos <i class="fa fa-arrow-circle-right"></i></a>

          </div>
        </div>


        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3>{{count($productos)}}</h3>

              <p>Listados</p>
            </div>
            <div class="icon">
              <i class="fab fa-product-hunt"></i>
            </div>
            <a href="Productos" class="small-box-footer">Ir a Productos <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
   </div>
   <div class="row">
     <div class="col-lg-8 col-xs-12">

       <div class="box box-info">
         <div class="box-header with-border">
           <h3 class="box-title">Tareas del día a realizar</h3>

           <div class="box-tools pull-right">
             <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
             </button>
             <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
           </div>
         </div>
         <!-- /.box-header -->
         <div class="box-body">
           <div class="table-responsive">
             <table class="table no-margin">
               <thead>
                 <tr>
                   <th>ID</th>
                   <th>Descripcion</th>
                   <th>Estado</th>
                   <th>Prioridad</th>
                 </tr>
               </thead>
               <tbody>
                 @foreach($tareaDiaria as $tarea)
                      @php
                      $mostrar = false;
                         foreach($tarea->users as $user){
                             if(auth()->user()->id == $user->user_id){
                                 $mostrar=true;
                             }
                         }
                       @endphp
                       @if($mostrar)
                           <tr>
                              <td>{{$tarea->id}}</td>
                              <td>{{$tarea->descripcion}}</td>
                              @if($tarea->status == 'pendiente')
                              <td><span class="label label-warning">{{$tarea->status}}</span></td>
                              @else
                              <td><span class="label label-success">{{$tarea->status}}</span></td>
                              @endif
                              <td>
                                @if($tarea->prioridad=='alta')
                                <span class="badge badge-pill badge-red bg-red">Alta</span>
                                @endif
                                @if($tarea->prioridad=='baja')
                                <span class="badge badge-pill badge-blue bg-blue">Baja</span>
                                @endif
                                @if($tarea->prioridad=='media')
                                <span class="badge badge-pill badge-yellow bg-yellow">Media</span>
                                @endif
                              </td>
                           </tr>
                       @endif
                 @endforeach
                 <!--tr>
                   <td><a href="pages/examples/invoice.html">OR9842</a></td>
                   <td>Call of Duty IV</td>
                   <td><span class="label label-success">Shipped</span></td>
                   <td>
                     <div class="sparkbar" data-color="#00a65a" data-height="20">90,80,90,-70,61,-83,63</div>
                   </td>
                 </tr-->
               </tbody>
             </table>
           </div>
           <!-- /.table-responsive -->
         </div>
         <!-- /.box-body -->
         <div class="box-footer clearfix">
           <a href="ListaCalendario" class="btn btn-sm btn-default btn-flat pull-right">Ver todas las tareas</a>
         </div>
         <!-- /.box-footer -->
       </div>
       <!-- /.box -->
     </div>
     <div class="col-lg-4 col-xs-12">
       <div class="box box-solid bg-green-gradient">
             <div class="box-header">
               <i class="fa fa-calendar"></i>
               <h3 class="box-title">Tareas Pendientes</h3>
               <!-- tools box -->
               <div class="pull-right box-tools">
                 <!-- button with a dropdown -->
                 <div class="btn-group">
                   <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown">
                     <i class="fa fa-bars"></i></button>
                   <ul class="dropdown-menu pull-right" role="menu">
                     <li><a href="Calendario">Ir a Tareas Calendarizadas</a></li>
                   </ul>
                 </div>
                 <button type="button" class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>
                 </button>
                 <button type="button" class="btn btn-success btn-sm" data-widget="remove"><i class="fa fa-times"></i>
                 </button>
               </div>
               <!-- /. tools -->
             </div>
             <!-- /.box-header -->
             <div class="box-body no-padding">
               <!--The calendar -->
               <div id="calendar" style="width: 100%"></div>
             </div>
             <!-- /.box-body -->
             <div class="box-footer text-black">
               <div class="row">
                 @foreach($tareas as $tarea)
                      @php
                      $mostrar = false;
                         foreach($tarea->users as $user){
                             if(auth()->user()->id == $user->user_id){
                                 $mostrar=true;
                             }
                         }
                       @endphp
                       @if($mostrar)
                       <div class="col-sm-12">
                         <div class="clearfix">
                           <span class="pull-left">{{$tarea->titulo}}</span>
                           <small class="pull-right">{{$tarea->avance}}%</small>
                         </div>
                         <div class="progress xs">
                          <div class="progress-bar" style="width: {{$tarea->avance}}%;background-color: {{$tarea->color}};"></div>
                        </div>
                       </div>
                       @endif
                  @endforeach
                 <!-- /.col -->

                 <!-- /.col -->
               </div>
               <!-- /.row -->
             </div>
           </div>
     </div>
     </div>
   </div>
</section>

@else
<section class="content">
  <div class="row" >
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-aqua"><i class="fas fa-envelope"></i></span>

        <div class="info-box-content">
          <span class="info-box-text" style="font-size: 10px !important;">Ordenes de Compra Aprobadas</span>
          <span class="info-box-number">{{count($ComprasAutorizadas)}}<small>de {{count($ordenCompras)}}</small></span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-green"><i class="fas fa-flag"></i></span>

        <div class="info-box-content">
          <span class="info-box-text" style="font-size: 10px !important;">Ordenes de Ventas a Cerrar</span>
          <span class="info-box-number">{{count($presupuestos)}}</span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
         <div class="info-box">
           <span class="info-box-icon bg-yellow"><i class="fas fa-paste"></i></span>

           <div class="info-box-content">
             <span class="info-box-text" style="font-size: 10px !important;">Remitos Pendientes</span>
             <span class="info-box-number">{{count($remitosPendientes)}}</span>
           </div>
           <!-- /.info-box-content -->
         </div>
         <!-- /.info-box -->
       </div>
       <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-red"><i class="far fa-star"></i></span>

          <div class="info-box-content">
            <span class="info-box-text">Total fac. Mes</span>
            <span class="info-box-number">${{$facturaMes->sum('ImpTotal')}}</span>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
      </div>
  </div>
  <div class="row">
    <div class="col-lg-9 col-xs-6">
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs pull-right">
             <li class="active"><a href="#revenue-chart" data-toggle="tab">Area</a></li>
             <li class="pull-left header"><i class="fa fa-inbox"></i> Ventas</li>
           </ul>
           <div class="tab-content no-padding">
             <div class="chart tab-pane active" id="revenue-chart" style="position: relative; height: 300px;">

             </div>
           </div>
         </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="col-lg-12 col-xs-6">
        <div class="small-box bg-black">
          <div class="inner">
            <h3></h3>
            <p>Deuda Proveedores</p>
          </div>
        </div>
      </div>
        <div class="col-lg-12 col-xs-6">
          <div class="small-box bg-black">
            <div class="inner">
              <h3></h3>
              <p>Remitos a Cobrar</p>
            </div>
          </div>
        </div>
          <div class="col-lg-12 col-xs-6">
            <div class="small-box bg-black">
              <div class="inner">
                <h3></h3>
                <p>Gastos a Pagar</p>
              </div>
            </div>
          </div>

            <div class="col-lg-12 col-xs-6">
              <div class="small-box bg-black">
                <div class="inner">
                  <h3></h3>
                  @php
                    foreach(auth()->user()->empresa->divisas as $divisa){
                       $divisaValor = $divisa -> valor_venta;
                    }
                  @endphp
                  <p>Valor U$$ Vta ${{$divisaValor}}</p>
                </div>
              </div>
            </div>
    </div>
   </div>

     <div class="row">
        <div class="col-lg-2 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3></h3>

              <p>Facturas</p>
            </div>
            <a href="Facturacion" class="small-box-footer">Ir a Facturas <i class="fa fa-arrow-circle-right"></i></a>

          </div>
        </div>

        <div class="col-lg-2 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3></h3>

              <p>Recibos</p>
            </div>
            <a href="Recibos" class="small-box-footer">Ir a Recibos <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>


        <div class="col-lg-2 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3></h3>

              <p>Ordenes de Compra</p>
            </div>
            <a href="Ordenes de compra" class="small-box-footer">Ir a OC <i class="fa fa-arrow-circle-right"></i></a>

          </div>
        </div>

        <div class="col-lg-2 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3></h3>

              <p>Ordenes de Venta</p>
            </div>
            <a href="Preventa" class="small-box-footer">Ir a PreVentas <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-lg-2 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3></h3>

              <p>Listados</p>
            </div>
            <a href="Productos" class="small-box-footer">Ir a Productos <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-lg-2 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3></h3>

              <p>Promoción</p>
            </div>
            <a href="#" class="small-box-footer">Ir a Promociones <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>

   </div>
   <div class="row">
     <div class="col-lg-8 col-xs-12">

       <div class="box box-info">
         <div class="box-header with-border">
           <h3 class="box-title">Tareas del día a realizar</h3>

           <div class="box-tools pull-right">
             <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
             </button>
             <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
           </div>
         </div>
         <!-- /.box-header -->
         <div class="box-body">
           <div class="table-responsive">
             <table class="table no-margin">
               <thead>
                 <tr>
                   <th>ID</th>
                   <th>Descripcion</th>
                   <th>Estado</th>
                   <th>Prioridad</th>
                   <th>Responsable</th>
                 </tr>
               </thead>
               <tbody>
                 @foreach($tareaDiaria as $tarea)
                           <tr>
                              <td>{{$tarea->id}}</td>
                              <td>{{$tarea->descripcion}}</td>
                              @if($tarea->status == 'pendiente')
                              <td><span class="label label-warning">{{$tarea->status}}</span></td>
                              @else
                              <td><span class="label label-success">{{$tarea->status}}</span></td>
                              @endif
                              <td>
                                @if($tarea->prioridad=='alta')
                                <span class="badge badge-pill badge-red bg-red">Alta</span>
                                @endif
                                @if($tarea->prioridad=='baja')
                                <span class="badge badge-pill badge-blue bg-blue">Baja</span>
                                @endif
                                @if($tarea->prioridad=='media')
                                <span class="badge badge-pill badge-yellow bg-yellow">Media</span>
                                @endif
                              </td>
                              <td>
                                <img src="{{$tarea->user->profile_image}}" class="img-circle" alt="User Image" style="width:40px;">{{$tarea->user->name}}
                              </td>
                           </tr>
                 @endforeach
                 <!--tr>
                   <td><a href="pages/examples/invoice.html">OR9842</a></td>
                   <td>Call of Duty IV</td>
                   <td><span class="label label-success">Shipped</span></td>
                   <td>
                     <div class="sparkbar" data-color="#00a65a" data-height="20">90,80,90,-70,61,-83,63</div>
                   </td>
                 </tr-->
               </tbody>
             </table>
           </div>
           <!-- /.table-responsive -->
         </div>
         <!-- /.box-body -->
         <div class="box-footer clearfix">
           <a href="ListaCalendario" class="btn btn-sm btn-default btn-flat pull-right">Ver todas las tareas</a>
         </div>
         <!-- /.box-footer -->
       </div>
       <!-- /.box -->
     </div>
     <div class="col-lg-4 col-xs-12">
       <div class="box box-solid bg-green-gradient">
             <div class="box-header">
               <i class="fa fa-calendar"></i>
               <h3 class="box-title">Tareas Pendientes</h3>
               <!-- tools box -->
               <div class="pull-right box-tools">
                 <!-- button with a dropdown -->
                 <div class="btn-group">
                   <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown">
                     <i class="fa fa-bars"></i></button>
                   <ul class="dropdown-menu pull-right" role="menu">
                     <li><a href="Calendario">Ir a Tareas Calendarizadas</a></li>
                   </ul>
                 </div>
                 <button type="button" class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>
                 </button>
                 <button type="button" class="btn btn-success btn-sm" data-widget="remove"><i class="fa fa-times"></i>
                 </button>
               </div>
               <!-- /. tools -->
             </div>
             <!-- /.box-header -->
             <div class="box-body no-padding">
               <!--The calendar -->
               <div id="calendar" style="width: 100%"></div>
             </div>
             <!-- /.box-body -->
             <div class="box-footer text-black">
               <div class="row">
                 @foreach($tareas as $tarea)
                       <div class="col-sm-12">
                         <div class="clearfix">
                           <span class="pull-left">{{$tarea->titulo}}</span>
                           <small class="pull-right">{{$tarea->avance}}%</small>
                         </div>
                         <div class="progress xs">
                          <div class="progress-bar" style="width: {{$tarea->avance}}%;background-color: {{$tarea->color}};"></div>
                        </div>
                       </div>
                  @endforeach
                 <!-- /.col -->

                 <!-- /.col -->
               </div>
               <!-- /.row -->
             </div>
           </div>
     </div>
     </div>
   </div>
</section>
@endif

@endsection
