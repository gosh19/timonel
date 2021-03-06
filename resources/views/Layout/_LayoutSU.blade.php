<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>CMR ADMIN</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="shortcut icon" href="../../public/favicon.ico">
  <link rel="stylesheet" type="text/css" href="../../public/css/main.css">
  <link rel="stylesheet" href="../../public/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../public/bower_components/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="../../public/bower_components/Ionicons/css/ionicons.min.css">
  <link rel="stylesheet" href="../../public/dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="../../public/dist/css/skins/_all-skins.min.css">
  <link rel="stylesheet" href="../../public/bower_components/morris.js/morris.css">
  <link rel="stylesheet" href="../../public/bower_components/jvectormap/jquery-jvectormap.css">
  <link rel="stylesheet" href="../../public/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <link rel="stylesheet" href="../../public/bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="../../public/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>

  <link rel="stylesheet" href="../../public/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../public/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="../../public/bower_components/Ionicons/css/ionicons.min.css">
  <link rel="stylesheet" href="../../public/bower_components/autocomplete/jquery.easy-autocomplete.min.css">
  <link rel="stylesheet" href="../../public/bower_components/autocomplete/easy-autocomplete.themes.min.css">



  <link rel="stylesheet" href="../../public/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
  <link rel="stylesheet" href="../../public/bower_components/datatables.net-bs/css/buttons.dataTables.min.css">



  <!-- Theme style -->
  <link rel="stylesheet" href="../../public/dist/css/AdminLTE.min.css">

  <link rel="stylesheet" href="../../public/dist/css/skins/_all-skins.min.css">
  <link href="../../public/bower_components/select2/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../public/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/select/1.2.7/css/select.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue sidebar-mini">

<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="index2.html" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><img height="50" src="../../public/dist/img/icono.png"></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><img height="50" src="../../public/dist/img/NombreTest.png"></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          <li class="dropdown messages-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fas fa-envelope"></i>
              <span class="label label-success"><!--4--></span>
            </a>
            <ul class="dropdown-menu">
              <li class="header"><!--You have 4 messages--></li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <!--<li>
                    <a href="#">
                      <div class="pull-left">
                        <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                      </div>
                      <h4>
                        Support Team
                        <small><i class="fa fa-clock-o"></i> 5 mins</small>
                      </h4>
                      <p>Why not buy a new awesome theme?</p>
                    </a>
                  </li>-->
                  <!-- end message -->
                </ul>
              </li>
              <li class="footer"><a href="chat">See All Messages</a></li>
            </ul>
          </li>
          <!-- Notifications: style can be found in dropdown.less -->
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fas fa-bell"></i>
              <span class="label label-warning"><!--10--></span>
            </a>
            <ul class="dropdown-menu">
              <li class="header"><!--You have 10 notifications--></li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <!--<li>
                    <a href="#">
                      <i class="fa fa-users text-aqua"></i> 5 new members joined today
                    </a>
                  </li>-->



                </ul>
              </li>
              <li class="footer"><a href="#">View all</a></li>
            </ul>
          </li>
          <!-- Tasks: style can be found in dropdown.less -->
          <li class="dropdown tasks-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fas fa-flag"></i>
              <span class="label label-danger"><!--9--></span>
            </a>
            <ul class="dropdown-menu">
              <li class="header"><!--You have 9 tasks--></li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <!--<li>
                    <a href="#">
                      <h3>
                        Design some buttons
                        <small class="pull-right">20%</small>
                      </h3>
                      <div class="progress xs">
                        <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar"
                             aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                          <span class="sr-only">20% Complete</span>
                        </div>
                      </div>
                    </a>
                  </li>-->

                  <!-- end task item -->
                </ul>
              </li>
              <li class="footer">
                <a href="#">View all tasks</a>
              </li>
            </ul>
          </li>
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="@yield('ProfiImage')" class="user-image" alt="User Image">
              <span class="hidden-xs">@yield('namesidebar')</span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="@yield('ProfiImage')" class="img-circle" alt="User Image">

                <p>
                 @yield('namesidebar') - @yield('descripcion_puesto')
                  <small>Miembro de @yield('empresaUth')</small>
                </p>
              </li>
              <!-- Menu Body -->
              <!--li class="user-body">


              </li-->
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Foto de Perfil</a>
                </div>
                <div class="pull-right">
                  <a href="logout" class="btn btn-default btn-flat">Cerrar Sesión</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <li>
            <a href="javascript:void(0);" onclick="config();" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <div class="modal fade" id="modal-config">
    <div class="modal-dialog bs-example-modal modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Configuración General</h4>
        <div class="modal-body">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                   <li class="active"><a href="#foto" data-toggle="tab">Foto de perfil</a></li>
                   <li><a href="#cambiarcontraseña" data-toggle="tab">Cambiar Contraseña</a></li>
                   <li class=""><a href="#cotizacion" data-toggle="tab">Cotizacion del Dolar</a></li>
                   <div class="tab-content">
                      <div class="active tab-pane" id="foto">
                        <form id="reemplazar_foto" method="post" action="poner_foto" enctype="multipart/form-data">
                            {{csrf_field()}}
                          <div class="row">
                              <div class="form-group col-lg-6 col-sm-12">
                                  <label for="exampleInputFile">Imagen de Perfil</label>
                                  <input type="file" name="foto_perfil" id="foto_perfil">
                                  <p class="help-block">Formato PNG</p>
                              </div>
                            </div>
                            <br>
                            <div class="col-xl-12 col-sm-12">
                              <br>
                                <button type="button" onclick="cambiar_foto();" class="btn btn-primary">Cambiar Foto.</button>
                                <br>
                             </div>
                             <br>
                            </form>
                      </div>
                      <div class="tab-pane" id="cambiarcontraseña">
                         <div class="col-xl-12 col-sm-12">
                              <label for="">Contraseña Actual</label>
                              <input type="password" id="contrasena_actual" class="form-control">
                         </div>
                         <div class="col-xl-12 col-sm-12">
                            <label for="">Contraseña Nueva</label>
                            <input type="password" id="contrasena_nueva" class="form-control">
                         </div>
                         <div class="col-xl-12 col-sm-12">
                            <label for="">Repetir Contraseña</label>
                            <input type="password" id="contrasena_repetir" class="form-control">
                       </div>
                       <br>
                       <div class="col-xl-12 col-sm-12">
                         <br>
                         <button id ="actualizar_contrasena" type="button" onclick="actualizar_contrasena();" class="btn btn-primary">Actualizar Contraseña</button>
                         <br>
                        </div>
                      <br>
                      </div>
                      <div class="tab-pane" id="cotizacion">
                          <div class="col-xl-12 col-sm-12">
                              <label for="">Divisa</label>
                              <input type="text" id="moneda" value="Dolar" class="form-control" disabled>
                         </div>
                         <div class="col-xl-12 col-sm-12">
                            <label for="">Venta</label>
                            <input type="number" id="valor_venta" class="form-control">
                         </div>
                         <div class="col-xl-12 col-sm-12">
                            <label for="">Compra</label>
                            <input type="number" id="valor_compra" class="form-control">
                         </div>
                         <br>
                         <div class="col-xl-12 col-sm-12">
                           <br>
                           <button class="btn btn-primary" id="actualizar_cambio" type="button" onclick="actualizar_cambio();">Actualizar Cambio</button>
                           <br>
                         </div>
                         <br>
                      </div>
                    </div>
                </ul>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
    function config(){

      $.ajaxSetup({
              headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


           $.ajax({
               method: "post",
               url: "<?php echo route('getDivisa')?>",
               success: function(response){ // What to do if we succeed
                $('#valor_compra').val(response.valor_compra);
                $('#valor_venta').val(response.valor_venta);
                $('#modal-config').modal('show');
                     }
                 });


    }
    function cambiar_foto(){

        if($('#foto_perfil').val()!=''){

          document.getElementById("reemplazar_foto").submit();
        }else{
          swal('Error','Debe Cargar una Foto primero','error');
        }
    }
    function actualizar_contrasena(){
       var contrasena_actual = $('#contrasena_actual').val();
       var contrasena_nueva = $('#contrasena_nueva').val();
       var contrasena_repetir = $('#contrasena_repetir').val();
       var validacion = validar_contrasena();
       if(validacion == 'OK'){
        $.ajaxSetup({
                   headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                     }
                 });

                   data="contrasena_actual="+contrasena_actual+"&contrasena_nueva="+contrasena_nueva+"&contrasena_repetir="+contrasena_repetir;
                $.ajax({
                    method: "post",
                    data: data,
                    url: "<?php echo route('cambio_contrasena')?>",
                    success: function(response){ // What to do if we succeed
                         if(response == 1){
                            swal('Error','Contraseña Incorrecta','error');
                         }
                         if(response == 0){
                          swal("Buen Trabajo!", 'Contraseña actualizada', "success");
                         }
                          }
                      });
       }else{
        swal('Error',validacion,'error');

       }

    }
    function actualizar_cambio(){
       var validacion =  validar_moneda();
       if(validacion == 'OK'){
         var valor_venta = $('#valor_venta').val();
         var valor_compra = $('#valor_compra').val();
        $.ajaxSetup({
              headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

              data="valor_venta="+valor_venta+"&valor_compra="+valor_compra;
           $.ajax({
               method: "post",
               data: data,
               url: "<?php echo route('updateDivisa')?>",
               success: function(response){ // What to do if we succeed
               swal("Buen Trabajo!", 'Valor actualizado', "success")
               .then((value) => {
                  location.reload();
                     });
                     }
                 });
       }else{
         swal('Error',validacion,'error');
       }
    }
    function validar_moneda(){
        var respuesta = '';
        if($('#valor_venta').val()==''){
            respuesta += 'Ingrese valor para la venta \n';
        }if($('#valor_compra').val()==''){
            respuesta += 'Ingrese valor para la compra \n';
        }
        if(respuesta == ''){
            respuesta ='OK';
        }
        return respuesta;
    }
    function validar_contrasena(){
      var respuesta = '';

      if($('#contrasena_actual').val()== ''){
         respuesta += 'Ingrese contraseña actual \n';
      }
      if($('#contrasena_nueva').val()==''){
        respuesta += 'Ingrese contraseña nueva \n';
      }
      if($('#contrasena_repetir').val()==''){
        respuesta += 'Confirme Contraseña \n';
      }
      if($('#contrasena_nueva').val()!=$('#contrasena_repetir').val()){
        respuesta += 'Contraseñas no coinciden \n';
      }
      if(respuesta == ''){
          respuesta = 'OK';
      }

      return respuesta;

    }


    </script>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="@yield('ProfiImage')" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>@yield('namesidebar')</p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- search form -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Buscar...">
          <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">Menu de Navegacion</li>
        <li class="treeview" id="das">
          <a href="#">
            <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li id="das1" class=""><a href="dashboard"><i class="fas fa-circle-notch"></i> Dashboard</a></li>

          </ul>
        </li>
        @if(auth()->user()->categoria == 1)
        <li class="treeview" id="SU">
          <a href="#">
            <i class="glyphicon glyphicon-briefcase"></i> <span>Super Usuario</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li id="SU1" class=""><a href="usuariosEmpresa"><i class="fas fa-circle-notch"></i> Gestión de Usuarios</a></li>
            <li id="SU2" class=""><a href="empresa"><i class="fas fa-circle-notch"></i> Gestión de Empresas</a></li>
          </ul>
        </li>
          @endif
          @if(auth()->user()->categoria == 2 || auth()->user()->categoria == 1)
        <li id="sistItem" class="treeview">
          <a href="#">
          <i class="fa fa-laptop"></i>
            <span>Sistema</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li id="sistItem1" class=""><a href="Usuarios"><i class="fas fa-circle-notch"></i> Gestión de Usuarios</a></li>
          </ul>
        </li>
        @endif
        <li class="header">CMR</li>

        <li id="cmrItem" class="treeview">
          <a href="#">
          <i class="fa fa-users"></i>
            <span>CMR</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li id="cmrItem1" class=""><a href="ClientesAgenda"><i class="fas fa-circle-notch"></i> Contactos</a></li>
            <li id="cmrItem2" class=""><a href="#"><i class="fas fa-circle-notch"></i> Mails</a></li>
            <li id="cmrItem2" class=""><a href="notificaciones"><i class="fas fa-circle-notch"></i> Campañas</a></li>
          </ul>
        </li>

        <li id="cmrItem" class="treeview">
          <a href="#">
          <i class="fa fa-users"></i>
            <span>Organizador</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li id="cmrItem1" class=""><a href="ClientesAgenda"><i class="fas fa-circle-notch"></i> Agenda</a></li>
            <li id="cmrItem2" class=""><a href="#"><i class="fas fa-circle-notch"></i> Proyectos</a></li>
            <li id="cmrItem2" class=""><a href="ListaCalendario"><i class="fas fa-circle-notch"></i> Tareas</a></li>
            <li><a href="Calendario"><i class="fas fa-circle-notch"></i> Tareas Calendarizadas</a></li>
          </ul>
        </li>

        <li id="cmrItem" class="treeview">
          <a href="#">
          <i class="fa fa-users"></i>
            <span>Oferta</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li id="cmrItem1" class=""><a href="Productos"><i class="fas fa-circle-notch"></i> Productos</a></li>
            <li id="cmrItem2" class=""><a href="Servicios"><i class="fas fa-circle-notch"></i> Servicios</a></li>
            <li id="cmrItem2" class=""><a href="#"><i class="fas fa-circle-notch"></i> Inventario</a></li>
          </ul>
        </li>

        <li id="cmrItem" class="treeview">
          <a href="#">
          <i class="fa fa-users"></i>
            <span>Demanda</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li id="gesItem1" class=""><a href="Proveedores"><i class="fas fa-circle-notch"></i> Proveedores</a></li>
            <li id="cmrItem1" class=""><a href="Ordenes Pago"><i class="fas fa-circle-notch"></i> Ordenes de pago</a></li>
            <li id="cmrItem1" class=""><a href="Ordenes de compra"><i class="fas fa-circle-notch"></i> Ordenes de Compras</a></li>
            <li id="cmrItem1" class=""><a href="/Factura Compra/"><i class="fas fa-circle-notch"></i>Factura compra</a></li>

          </ul>
        </li>

        <li id="cmrItem" class="treeview">
          <a href="#">
          <i class="fa fa-users"></i>
            <span>Comprobantes</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">

            <li id="cmrItem1" class=""><a href="/Facturacion/"><i class="fas fa-circle-notch"></i>Crear Factura</a></li>
            <li class=""><a href="Ventas"><i class="fas fa-circle-notch"></i>Ingreso de Ventas</a></li>
            <li class=""><a href="Preventa"><i class="fas fa-circle-notch"></i>Presupuestos</a></li>
            <li class=""><a href="Remito"><i class="fas fa-circle-notch"></i>Remitos</a></li>
            <li class=""><a href="Recibos"><i class="fas fa-circle-notch"></i>Recibos</a></li>

          </ul>
        </li>

        <!--li id="cmrItem" class="treeview">
          <a href="#">
          <i class="fa fa-users"></i>
            <span>Clientes</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li id="cmrItem1" class=""><a href="ClientesAgenda"><i class="fas fa-circle-notch"></i> Agenda</a></li>
            <li id="cmrItem2" class=""><a href="notificaciones"><i class="fas fa-circle-notch"></i> Notificaciones</a></li>
          </ul>
        </li-->

        <!--li id="cmrItem" class="treeview">
          <a href="#">
          <i class="fas fa-file-invoice-dollar"></i>
            <span>Facturacion</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li id="cmrItem1" class=""><a href="/Facturacion/"><i class="fas fa-circle-notch"></i>Crear Factura</a></li>
            <li id="cmrItem1" class=""><a href="/Factura Compra/"><i class="fas fa-circle-notch"></i>Factura compra</a></li>
          </ul>
        </li-->

        <li id="opor" class="treeview">
          <a href="#">
          <i class="fas fa-address-book"></i>
            <span>Oportunidades</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li id="opor1" class=""><a href="Oportunidades"><i class="fas fa-circle-notch"></i> ABM Oportunidades</a></li>

          </ul>
        </li>

        <!--li class="header">Modulo de Gestión</li>
        <li id="gesItem" class="treeview">
          <a href="#">
          <i class="fas fa-clipboard-check"></i>
            <span> Administración</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li id="gesItem2" class=""><a href="Productos"><i class="fas fa-circle-notch"></i> Productos</a></li>
            <li id="gesItem3" class=""><a href="Servicios"><i class="fas fa-circle-notch"></i> Servicios</a></li>
          </ul>
        </li>
        <li id="calen" class="treeview">
          <a href="Calendario">
            <i class="fas fa-thumbtack"></i>
            <span>Tareas</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li id="calen1" class=""><a href="Calendario"><i class="fas fa-circle-notch"></i> Calendario</a></li>
            <li id="calen1" class=""><a href="ListaCalendario"><i class="fas fa-circle-notch"></i> Lista</a></li>
          </ul>
        </li-->


      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
<div class="content-wrapper">
   @yield('wrapper')
</div>
  <!-- Content Wrapper. Contains page content -->

<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="../../public/bower_components/jquery/dist/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="../../public/bower_components/jquery-ui/jquery-ui.min.js"></script>

<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->




 <script src="../../public/bower_components/jquery/dist/jquery.min.js"></script>
 <script src="../../public/bower_components/jquery-ui/jquery-ui.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../../public/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

<script src="../../public/bower_components/autocomplete/jquery.easy-autocomplete.min.js"></script>


<!-- DataTables -->
<script src="../../public/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../../public/bower_components/datatables.net/js/dataTables.select.min.js"></script>
<script src="../../public/bower_components/datatables.net/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="http://cdn.datatables.net/buttons/1.2.1/js/buttons.print.min.js">
</script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>


<script src="../../public/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
  <!--link rel="stylesheet" type="text/css" href="../../public/bower_components/dataTable_2019/datatables.min.css"/-->
<!--script type="text/javascript" src="../../public/bower_components/dataTable_2019/datatables.min.js"></script-->
<!-- SlimScroll -->

<script src="../../public/bower_components/raphael/raphael.min.js"></script>
<script src="../../public/bower_components/morris.js/morris.min.js"></script>

<script src="../../public/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="../../public/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../../public/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../public/dist/js/demo.js"></script>

<script src="../../public/bower_components/moment/moment.js"></script>
<script src="../../public/bower_components/fullcalendar/dist/fullcalendar.min.js"></script>

<script src="../../public/bower_components/select2/dist/js/select2.min.js"></script>
<script src="../../public/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
</body>
</html>
