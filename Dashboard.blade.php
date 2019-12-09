
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
    $('#calendar').daterangepicker();
});

</script>
@if(auth()->user()->categoria == 3)
<section class="content">
  <div class="row" >
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-aqua"><i class="fas fa-envelope"></i></span>

        <div class="info-box-content">
          <span class="info-box-text" style="font-size: 10px !important;">Ordenes de Compra Aprobadas</span>
          <span class="info-box-number">2<small>de 24</small></span>
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
          <span class="info-box-number">760</span>
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
             <span class="info-box-number">2,000</span>
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
            <span class="info-box-number">41,410</span>
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
          <h3>150</h3>

          <p>Clientes</p>
        </div>

        <a href="ClientesAgenda" class="small-box-footer">Ir a Clientes <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-6 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-aqua">
        <div class="inner" style="text-align:center;">
          <h3>150</h3>

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
              <h3>150</h3>

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
              <h3>150</h3>

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
              <h3>150</h3>

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
              <h3>150</h3>

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
                   <th>Order ID</th>
                   <th>Item</th>
                   <th>Status</th>
                   <th>Popularity</th>
                 </tr>
               </thead>
               <tbody>
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
           <a href="" class="btn btn-sm btn-default btn-flat pull-right">Ver todas las tareas</a>
         </div>
         <!-- /.box-footer -->
       </div>
       <!-- /.box -->
     </div>
     <div class="col-lg-4 col-xs-12">
       <div class="box box-solid bg-green-gradient">
             <div class="box-header">
               <i class="fa fa-calendar"></i>
               <h3 class="box-title">Calendar</h3>
               <!-- tools box -->
               <div class="pull-right box-tools">
                 <!-- button with a dropdown -->
                 <div class="btn-group">
                   <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown">
                     <i class="fa fa-bars"></i></button>
                   <ul class="dropdown-menu pull-right" role="menu">
                     <li><a href="#">Ir a Tareas Calendarizadas</a></li>
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
                 <!-- /.col -->
                 <div class="col-sm-12">
                   <!--div class="clearfix">
                     <span class="pull-left">Task #3</span>
                     <small class="pull-right">60%</small>
                   </div>
                 </div-->
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
