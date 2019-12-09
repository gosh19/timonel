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

        function limpiarForm() {
            $("#razon_social").val('');
            $("#usuariosPermitidos").val('');
            $("#cuit").val('');
            $("#direccion").val('');
            $("#telefono").val('');
            $("#responsable").val('');
            $("#telefono_responsable").val('');
            $("#correo").val('');
            $("#clave_en").val('');
            $("#cert").val('');
            $("#clave").val('');

            $("#condicion_fiscal").val('');

            $(".nav-tabs-custom>ul>li").removeClass('active');
            $(".tab-pane").removeClass('active');
            $('#activity').addClass('active');
            $('#firstli').addClass('active');
        }

        function validarcampos() {
            var respuesta = '';

            if ($("#razon_social").val() == '') {
                respuesta += '';
            }
            if ($("#usuariosPermitidos").val() == '') {
                respuesta += '';
            }
            if ($("#cuit").val() == '') {
                respuesta += '';
            }
            if ($("#direccion").val() == '') {
                respuesta += '';
            }
            if ($("#telefono").val() == '') {
                respuesta += '';
            }
            if ($("#responsable").val() == '') {
                respuesta += '';
            }
            if ($("#telefono_responsable").val() == '') {
                respuesta += '';
            }
            if ($("#correo").val() == '') {
                respuesta += '';
            }
            if ($("#clave_en").val() == '') {
                respuesta += '';
            }
            if ($("#cert").val() == '') {
                respuesta += '';
            }
            if ($("#clave").val() == '') {
                respuesta += '';
            }
            if ($("#condicion_fiscal").val() == '') {
                respuesta += '';
            }
            if (respuesta == '') {
                respuesta = "OK";
            }
            return respuesta;
        }


        $(document).ready(function () {


            $('.sidebar-menu>li').removeClass('active');
            $('.sidebar-menu>li>ul').removeClass('active');
            $('.sidebar-menu>li>ul>li').removeClass('active');
            $('#SU').addClass('active');
            $('#SU2').addClass('active');


            $('#example1').DataTable()
            $('#example2').DataTable({
                'paging': true,
                'lengthChange': true,
                'searching': true,
                'ordering': true,
                'info': true,
                'autoWidth': true
            })
        });

        function editEmpresa(id) {
            limpiarForm();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            data = "id=" + id;
            $.ajax({
                method: "post",
                data: data,
                url: "<?php echo route('edit_empresa')?>",
                success: function (response) { // What to do if we succeed
                    loadData(response);


                }
            });

        }

        function loadData(empresa) {
            limpiarForm();
            $('#modal-default').modal('show');
            $("#razon_social").val(empresa.razon_social);
            $("#usuariosPermitidos").val(empresa.usuariosPermitidos);
            $("#cuit").val(empresa.cuit);
            $("#direccion").val(empresa.direccion);
            $("#telefono").val(empresa.telefono);
            $("#responsable").val(empresa.responsable);
            $("#telefono_responsable").val(empresa.telefono_responsable);
            $("#correo").val(empresa.correo);
            $("#clave_en").val('');
            $("#cert").val('');
            $("#clave").val('');
            $("#condicion_fiscal").val(empresa.condicion_fiscal);
            $("#control").val(empresa.id);
            $("#inicio_actividades").val(empresa.inicio_actividades);
            $('#produccion').val(empresa.produccion);
            $('#punto_venta').val(empresa.punto_venta);
        }

        function deleteEmpresa(id) {
            swal({
                title: "Estas Seguro?",
                text: "Si eliminas la empresa niegas el acceso al sistema a todos sus usuarios y ademas se perderan todos los registros contables!",
                icon: "warning",
                buttons: ["Cancelar", "Eliminar Empresa!"],
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });

                        data = "id=" + id;
                        $.ajax({
                            method: "post",
                            data: data,
                            url: "<?php echo route('delete_usuarioE')?>",
                            success: function (response) { // What to do if we succeed
                                swal("Buen Trabajo!", response, "success")
                                    .then((value) => {
                                        location.reload();
                                    });
                            }
                        });

                    } else {
                        swal("La Empresa esta a salvo!");
                    }
                });

        }
    </script>

    <div class="callout callout-warning">
        <h4>Recuerda!</h4>
        Ante cualquier error contactar inmediatamente al Administrador del Sistema, el CMR esta en fase de desarrollo.

    </div>
    <section class="content-header">
        <h1>
            Super Usuario
            <small>Gestión de Empresas</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-laptop"></i>Super Usuario</a></li>
            <li class="active">Gestión de Empresas</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <br>
                        <h3 class="box-title">Registro de Empresas Activas</h3>
                    </div>

                    <!-- /.box-header -->
                    <div class="box-body">
                        <button type="button" onClick="limpiarForm();" class="btn btn-default" data-toggle="modal"
                                data-target="#modal-default">
                            Ingresar Empresa
                        </button>
                        <br>
                        <br>
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>#id</th>
                                <th>Razon Social</th>
                                <th>Dirección</th>
                                <th>Telefono</th>
                                <th>Condición Fiscal</th>
                                <th>Usuarios Permitidos</th>
                                <th>Perfil</th>
                                <th>Accion</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($empresas as $item)
                                <tr>
                                    <th>{{$item->id}}</th>
                                    <th>{{$item->razon_social}}</th>
                                    <th>{{$item->direccion}}</th>
                                    <th>{{$item->telefono}}</th>
                                    <th>{{$item->condicion_fiscal}}</th>
                                    <th>{{$item->usuariosPermitidos}}</th>
                                    <th><img src="../../public/{{$item->profile_image}}" class="img-circle"
                                             alt="User Image" style="width:40px;"></th>
                                    <th>
                                        <a href="javascript:editEmpresa({{$item->id}});">
                                            <button class="btn btn-info"><i class="fa fa-edit"></i></button>
                                        </a>
                                        <a href="javascript:deleteEmpresa({{$item->id}});">
                                            <button class="btn btn-danger"><i class="fa fa-eraser"></i></button>
                                        </a>
                                    </th>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>#id</th>
                                <th>Razon Social</th>
                                <th>Dirección</th>
                                <th>Telefono</th>
                                <th>Condición Fiscal</th>
                                <th>Usuarios Permitidos</th>
                                <th>Perfil</th>
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
    <!-- /.content -->
    <div class="modal fade" id="modal-default">
        <div class="modal-dialog bs-example-modal-lg modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">ABM Empresas</h4>
                </div>
                <div class="modal-body">
                    <form role="form" id="formuser" method="post" action="agregar_empresa"
                          enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="box-body">
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li id="firstli" class="active"><a href="#activity" data-toggle="tab">Datos #1</a>
                                    </li>
                                    <li class=""><a href="#timeline" data-toggle="tab">Datos #2</a></li>
                                    <li class=""><a href="#settings" data-toggle="tab">Archivos Para Facturación</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="active tab-pane" id="activity">
                                        <div class="row">
                                            <div class="form-group col-lg-6 col-sm-12">
                                                <input id="control" name="control" type="hidden" value="0">
                                                <input id="token" type="hidden" value="{{csrf_token()}}">
                                                <label for="exampleInputEmail1">Razón Social</label>
                                                <input type="text" class="form-control" id="razon_social"
                                                       name="razon_social" placeholder="Ingrese Razón Social">
                                            </div>
                                            <div class="form-group col-lg-6 col-sm-12">
                                                <label for="exampleInputEmail1">Usuarios Permitidos</label>
                                                <input type="number" class="form-control" id="usuariosPermitidos"
                                                       name="usuariosPermitidos" placeholder="Usuarios Permitidos">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-lg-6 col-sm-12">
                                                <label for="exampleInputEmail1">C.U.I.T</label>
                                                <input type="number" class="form-control" id="cuit" name="cuit"
                                                       placeholder="Ingrese C.U.I.T">
                                            </div>
                                            <div class="form-group col-lg-6 col-sm-12">
                                                <label for="exampleInputEmail1">Dirección</label>
                                                <input type="text" class="form-control" id="direccion" name="direccion"
                                                       placeholder="Ingrese Dirección">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="timeline">
                                        <div class="row">
                                            <div class="form-group col-lg-6 col-sm-12">
                                                <label for="exampleInputEmail1">Telefono</label>
                                                <input type="text" class="form-control" name="telefono" id="telefono"
                                                       placeholder="Ingrese Telefono">
                                            </div>
                                            <div class="form-group col-lg-6 col-sm-12">
                                                <label for="exampleInputEmail1">Persona de Contacto</label>
                                                <input type="text" class="form-control" name="responsable"
                                                       id="responsable" placeholder="Ingrese el Nombre">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-lg-6 col-sm-12">
                                                <label for="exampleInputEmail1">Telefono de Contacto</label>
                                                <input type="text" class="form-control" id="telefono_responsable"
                                                       name="telefono_responsable" placeholder="Ingrese el Telefono">
                                            </div>
                                            <div class="form-group col-lg-6 col-sm-12">
                                                <label for="exampleInputEmail1">Correo Electronico</label>
                                                <input type="text" class="form-control" name="correo" id="correo"
                                                       placeholder="Ingrese Correo Electronico">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-lg-6 col-sm-12">
                                                <label>Punto de venta Autorizado</label>
                                                <input type="number" class="form-control" id="punto_venta" name="punto_venta">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="settings">
                                        <div class="row">
                                            <div class="form-group col-lg-6 col-sm-12">
                                                <label for="exampleInputFile">Clave Encriptada</label>
                                                <input type="file" name="clave_en" id="clave_en">
                                                <p class="help-block">Clave en Formato #Pk12 nombre "key".</p>
                                            </div>
                                            <div class="form-group col-lg-6 col-sm-12">
                                                <label for="exampleInputFile">Certificado AFIP</label>
                                                <input type="file" name="cert" id="cert">
                                                <p class="help-block">Certificado en Formato CRT.</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-lg-6 col-sm-12">
                                                <label for="exampleInputFile">Imagen de Perfil</label>
                                                <input type="file" name="profile_image" id="profile_image">
                                                <p class="help-block">Formato PNG Sin Fondo Para Factura Digital</p>
                                            </div>
                                            <div class="form-group col-lg-6 col-sm-12">
                                                <label for="exampleInputEmail1">Clave Privada</label>
                                                <input type="file" id="clave" name="clave">
                                                <p class="help-block">Archivo de Clave</p>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="form-group col-lg-6 col-sm-12">
                                                <label for="exampleInputEmail1">Condición Fiscal</label>
                                                <select type="text" class="form-control" id="condicion_fiscal"
                                                        name="condicion_fiscal">
                                                    <option>Responsable Inscripto</option>
                                                    <option>Monotributista</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-lg-6 col-sm-12">
                                                <label>Inicio de Actividades</label>
                                                <input class="form-control" type="date" name="inicio_actividades" id="inicio_actividades">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-lg-6 col-sm-12">
                                                <label>Estado de Facturación</label>
                                                <select id ="produccion" name="produccion" class="form-control">
                                                    <option value="FALSE">Certificados WSAS</option>
                                                    <option value="TRUE">Certificados de Producción</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


@endsection
