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

function limpiarForm(){
  $('#name').val('');
  $('#email').val('');
  $('#password').val('');
  $('#categoria').val('0');
  $('#descripcion_puesto').val('');
  $('#empresa').val('0');

}
function validarcampos(){
       var validar=""
        if($('#name').val()==''){
           validar+="Ingrese Nombre \n";
        }
        if($('#email').val()==''){
          validar+="Ingrese Email \n";
        }

        if($('#categoria').val()=='0'){
          validar+="Ingrese Categoria \n";
        }
        if($('#descripcion_puesto').val()==''){
          validar+="Ingrese Descripción del Cargo \n";
        }
        if($('#empresa').val()=='0'){
           validar+="Seleccione una Empresa";
        }
        if(validar==''){
          validar="OK";
        }
        return validar;
    }
    function table(){
      $('#tableUsuarios').DataTable({
          'paging'      : true,
      'lengthChange': true,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : true
       }

        );
    }
    $(document).ready(function(){



      $('.treeview').removeClass('active');
        $('#SU').addClass('active');
        $('#SU1').addClass('active');
        table();
          $('#saveUser').click(function (){

              var validacion=validarcampos();
              if(validacion!="OK"){
                  swal("Error!", validacion, "error");
              }else{


                var name=$('#name').val();
                var email=$('#email').val()
                var password=$('#password').val();
                var id_categoria=$('#categoria').val();
                var token=$('#token').val();
                var control=$('#control').val();
                var descripcion_puesto=$('#descripcion_puesto').val();
                var empresa=$('#empresa').val();
                var data="name="+name+"&email="+email+"&password="+password+"&role_id="+id_categoria+'&control='+control+'&descripcion_puesto='+descripcion_puesto+'&empresa='+empresa;


                $.ajaxSetup({
                   headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                     }
                 });


                $.ajax({
                    method: "post",
                    data: data,
                    url: "<?php echo route('agregar_usuarioE')?>",
                    success: function(response){ // What to do if we succeed
                      swal("Buen Trabajo!", response, "success")
                      .then((value) => {
                         location.reload();
                            });
                          }

                      });

            }
        });










    });
    function editUser(id){
       limpiarForm();
       $.ajaxSetup({
                   headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                     }
                 });

                   data="id="+id;
                $.ajax({
                    method: "post",
                    data: data,
                    url: "<?php echo route('edit_usuario')?>",
                    success: function(response){ // What to do if we succeed
                    loadData(response);


                          }
                      });

    }
    function loadData(userRequest){


         $('#modal-default').modal('show');
         $('#name').val(userRequest.name);
         $('#email').val(userRequest.email);
         $('#categoria').val(userRequest.categoria);
         $('#control').val(userRequest.id);
         $('#descripcion_puesto').val(userRequest.descripcion_puesto);
         $('#empresa').val(userRequest.empresa_id);
    }
  function deleteUser(id){
      swal({
    title: "Estas Seguro?",
    text: "Una vez eliminado el usuario no tendra mas acceso al sistema!",
    icon: "warning",
    buttons: ["Cancelar", "Eliminar Usuario!"],
    dangerMode: true,
     })
  .then((willDelete) => {
    if (willDelete) {
      $.ajaxSetup({
          headers: {
           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

          data="id="+id;
       $.ajax({
           method: "post",
           data: data,
           url: "<?php echo route('delete_usuario')?>",
           success: function(response){ // What to do if we succeed
             swal("Buen Trabajo!", "Usuario Eliminado!", "success")
             .then((value) => {
                location.reload();
                   });


                 }
             });

     } else {
       swal("Usuario a Salvo!");
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
        <small>Gestión de Usuarios</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-laptop"></i>Super Usuario</a></li>
        <li class="active">Gestión de Usuarios</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
                <br>
              <h3 class="box-title">Registro de Usuarios Activos</h3>
            </div>

            <!-- /.box-header -->
            <div class="box-body">
            <button type="button" onClick="limpiarForm();" class="btn btn-default" data-toggle="modal" data-target="#modal-default">
                Ingresar Usuario
              </button>
              <br>
              <br>
              <table id="tableUsuarios" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>#id</th>
                  <th>Nombre</th>
                  <th>Correo Electronico</th>
                  <th>Cargo</th>
                  <th>Categoria</th>
                  <th>Empresa</th>
                  <th>Perfil</th>
                  <th>Accion</th>
                </tr>
                </thead>
                <tbody>
                   @foreach($users as $item)
                     <tr>

                        <th>{{$item->id}}</th>
                        <th>{{$item->name}}</th>
                        <th>{{$item->email}}</th>
                        <th>{{$item->descripcion_puesto}}</th>
                        <th>{{$item->role->name}}</th>
                        <th>{{$item->empresa->razon_social}}</th>
                        <th> <img src="{{$item->profile_image}}" class="img-circle" alt="User Image" style="width:40px;"></th>
                        <th><a href="javascript:editUser({{$item->id}});"><button class="btn btn-info"><i class="fa fa-edit"></i></button></a>
                        <a href="javascript:deleteUser({{$item->id}});"><button class="btn btn-danger"><i class="fa fa-eraser"></i></button></a>

                        </th>

                     </tr>
                   @endforeach
                </tbody>
                <tfoot>
                <tr>
                   <th>#id</th>
                  <th>Nombre</th>
                  <th>Correo Electronico</th>
                  <th>Cargo</th>
                  <th>Categoria</th>
                  <th>Empresa</th>
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
          <div class="modal-dialog bs-example-modal modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">ABM Usuarios</h4>
              <div class="modal-body">
                 <form role="form" id="formuser">
                 {{csrf_field()}}
                    <div class="box-body">
                       <div class="row">
                        <div class="form-group col-lg-6 col-xl-12">
                        <input id="control" type="hidden" value="0">
                        <input id="token" type="hidden" value="{{csrf_token()}}">
                            <label for="exampleInputEmail1">Nombre</label>
                            <input type="text" class="form-control" id="name" placeholder="Ingrese Nombre">
                        </div>
                        <div class="form-group col-lg-6 col-xl-12">
                            <label for="exampleInputEmail1">Email</label>
                            <input type="email" class="form-control" id="email" placeholder="Ingrese Correo">
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-group col-lg-6 col-xl-12">
                            <label for="exampleInputPassword1">Contraseña</label>
                            <input type="password" class="form-control" id="password" placeholder="Contraseña">
                        </div>
                        <div class="form-group col-lg-6 col-xl-12">
                            <label for="descripcion">Descripción del Cargo</label>
                            <input type="text" class="form-control" id="descripcion_puesto" placeholder="Descripción del Cargo">
                        </div>
                      </div>

                      <div class="row">
                        <div class="form-group col-lg-6 col-xl-12">
                            <label>Categoria</label>
                            <select class="form-control" id="categoria">
                            <option value="0">--Seleccionar--</option>
                            @foreach($roles as $itemr)
                                  <option value="{{$itemr->id}}">{{$itemr->name}}</option>
                            @endforeach
                            </select>
                        </div>
                        <div class="form-group col-lg-6 col-xl-12">
                            <label>Empresa</label>
                            <select class="form-control" id="empresa">
                            <option value="0">--Seleccionar--</option>
                            @foreach($empresas as $itemr)
                                  <option value="{{$itemr->id}}">{{$itemr->razon_social}}</option>
                            @endforeach
                            </select>
                        </div>
                      </div>
                        <!--div class="form-group">
                           <label for="exampleInputFile">Imagen de Perfil</label>
                           <input type="file" id="profile_image">
                           <p class="help-block">Imagen que no supere 2 MB.</p>
                        </div-->

                    </div>
                    </div>
                    </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                <button type="button" id="saveUser" class="btn btn-primary">Guardar Cambios</button>
              </div>
              </form>
            </div>
          </div>
    </div>

@endsection
