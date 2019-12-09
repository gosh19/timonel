<?php

//Rutas a views
Route::get('/', 'HomeController@inicio');
Route::get('Usuarios', 'HomeController@Usuarios')->name('Usuarios');


//fin rutas
//ruta de formulario login
Route::post('login','Auth\LoginController@login')->name('login');
Route::get('logout','Auth\LoginController@logout')->name('logout');
//ruta al dasboard
Route::get('dashboard','HomeController@initsesion')->name('dashboard');

//rutas para google
Route::get('redirectgo', 'Auth\LoginController@redirectToProvider');
Route::get('logdas', 'Auth\LoginController@handleProviderCallback');

//lockscreen
Route::get('lockscreen','HomeController@lockscreen')->name('lockscreen');
//useer
Route::post('agregar_usuario','UsuariosController@agregar_usuario')->name("agregar_usuario");
Route::post('delete_usuario','UsuariosController@delete')->name("delete_usuario");
Route::post('edit_usuario','UsuariosController@edit')->name("edit_usuario");
Route::post('cargarUsers','UsuariosController@cargarUsers')->name('cargarUsers');

//empresa
//aqui tendria que ir el middleware

//empresa
Route::get('empresa','HomeController@empresa')->name('empresa');
Route::post('agregar_empresa','EmpresaController@add')->name('agregar_empresa');
Route::post('edit_empresa','EmpresaController@edit')->name('edit_empresa');
Route::post('delete_usuarioE','EmpresaController@delete')->name('delete_usuarioE');

//usuariosEmpresa
Route::get('usuariosEmpresa','HomeController@UsuariosEmpresa')->name('usuariosEmpresa');
Route::post('agregar_usuarioE','usuarioEmpresa@agregar_usuario')->name('agregar_usuarioE');

//Clientes Agenda

Route::get('ClientesAgenda','HomeController@ClientesAgenda')->name('ClientesAgenda');
Route::post('agregar_cliente','ClientesController@Add')->name('agregar_cliente');
Route::get('PerfilClientes','ClientesController@PerfilClientes')->name('PerfilClientes');
Route::post('upload_profile','ClientesController@upload_profile')->name('upload_profile');
Route::post('getClientes','ClientesController@getClientes')->name('getClientes');

//Telefonos

Route::post('agregar_telefono','TelefonoController@Add')->name('agregar_telefono');

Route::post('agregar_correo','CorreoController@Add')->name('agregar_correo');

//Oportunidades
Route::get('Oportunidades','oportunidadController@ini')->name('Oportunidades');
Route::post('agregar_oportunidad','oportunidadController@add')->name('agregar_oportunidad');
Route::post('delete_oportunidad','oportunidadController@delete')->name('delete_oportunidad');
Route::post('edit_oportunidad','oportunidadController@edit')->name('edit_oportunidad');
//proveedores
Route::get('Proveedores','proveedoresController@ini')->name('Proveedores');
Route::post('agregar_proveedor','proveedoresController@add')->name('agregar_proveedor');
Route::post('delete_proveedor','proveedoresController@delete')->name('delete_proveedor');
Route::post('edit_proveedor','proveedoresController@edit')->name('edit_proveedor');
//Productos
Route::get('Productos','ProductosController@ini')->name('Productos');
Route::post('agregar_producto','ProductosController@add')->name('agregar_producto');
Route::post('delete_producto','ProductosController@delete')->name('delete_producto');
Route::post('edit_producto','ProductosController@edit')->name('edit_producto');
Route::post('facturar_producto','ProductosController@facturar')->name('facturar_producto');
Route::get('getProductos','ProductosController@getProductos')->name('getProductos');
Route::post('getProductos','ProductosController@editProductos')->name('getProductos');


//ServiciosController
Route::get('Servicios','HomeController@Serviciosini')->name('Servicios');
Route::post('agregar_servicio','ServiciosController@agregar')->name('agregar_servicio');
Route::post('editar_servicio','ServiciosController@ver')->name('editar_servicio');
Route::post('getproductos','ServiciosController@getproductos')->name('getproductos');
Route::post('deleteservicio','ServiciosController@deleteservicio')->name('deleteservicio');

//CHATT
Route::get('chat','HomeController@chat')->name('chat');
Route::get('messages', 'ChatsController@fetchMessages')->name('messages');
Route::post('messages', 'ChatsController@sendMessage')->name('messages');

//Calendario Task
Route::get('Calendario','HomeController@calendario')->name('Calendario');
Route::get('ListaCalendario','HomeController@listacalendario')->name('ListaCalendario');
Route::post('get_tarea','tareasController@get_tarea')->name('get_tarea');
Route::post('save_tarea','tareasController@save_tarea')->name('save_tarea');
Route::post('check_tarea','tareasController@check_tarea')->name('check_tarea');
Route::post('delete_tarea','tareasController@delete_tarea')->name('delete_tarea');
//notificaciones
Route::get('notificaciones','HomeController@notificaciones')->name('notificaciones');
Route::post('saveNotification','NotificationController@saveNotification')->name('saveNotification');
Route::post('getNotification','NotificationController@getNotification')->name('getNotification');
Route::post('deleteNotification','NotificationController@deleteNotification')->name('deleteNotification');
Route::post('sendNotification','NotificationController@sendNotification')->name('sendNotification');
//comentarios
Route::post('createComentario','ComentarioController@createComentario')->name('createComentario');
Route::post('actualizarAvance','tareasController@actualizarAvance')->name('actualizarAvance');

//facturacion
Route::get('/Facturacion/','facturacion@index');
Route::get('/Facturar/{details}','facturacion@Create');
Route::get('/Factura Compra/','facturacion@IndexFacturaCompra')->name('/Factura Compra/');
Route::get('/ProviderQuery/{id}','facturacion@QueryProvider')->where('id','[0-9]+');
Route::post('/Crear FacturaCompra','facturacion@CreateFacturaCompra')->name('Crear FacturaCompra');
Route::get('/FacturaCompra/Delete/{id}','facturacion@DeleteFacturaCompra')->where('id','[0-9]+');
Route::post('getFacturas','FacturaController@getFacturas')->name('getFacturas');
Route::post('getFactura','facturacion@getFactura')->name('getFactura');

//Ordenes de Pago

Route::get('Ordenes Pago','orden_de_Pago@index')->name('Ordenes Pago');
Route::get('/BuscarFacturaCompra/{data}','orden_de_Pago@Search');
Route::post('Crear Orden','orden_de_Pago@Create')->name('Crear Orden');
Route::get('Ordenes de compra','orden_de_Pago@indexOrdenCompra')->name('Ordenes de compra');
Route::get('/OrdendeCompra/BuscarProducto/{id}','orden_de_Pago@SearchProc')->where('id','[0-9]+');
Route::post('crear orden de compra','orden_de_Pago@CreateOrdenCompra')->name('crear orden de compra');


//DivisaController

Route::post('addDivisa','Admin\DivisaController@addDivisa0')->name('addDivisa');
Route::post('getDivisa','Admin\DivisaController@getDivisa')->name('getDivisa');
Route::post('updateDivisa','Admin\DivisaController@updateDivisa')->name('updateDivisa');

//ReciboController
Route::post('addRecibo','Admin\ReciboController@addRecibo')->name('addRecibo');
Route::post('getRecibo','Admin\ReciboController@getRecibo')->name('getRecibo');
Route::post('deleteRecibo','Admin\ReciboController@deleteRecibo')->name('deleteRecibo');
Route::get('Recibos','Admin\ReciboController@Recibos')->name('Recibos');

  //PreventaController

Route::post('addPreventa','Admin\PreventaController@addPreventa')->name('addPreventa');
Route::post('getPreventa','Admin\PreventaController@getPreventa')->name('getPreventa');
Route::post('deletePreventa','Admin\PreventaController@deletePreventa')->name('deletePreventa');
Route::post('venderPreventa','Admin\PreventaController@venderPreventa')->name('venderPreventa');
Route::post('facturarPreventa','Admin\PreventaController@facturarPreventa')->name('facturarPreventa');
Route::get('Preventa','Admin\PreventaController@Preventa')->name('Preventa');

//RemitoController
Route::get('Remito','RemitoController@Remito')->name('Remito');
Route::post('addRemito','RemitoController@addRemito')->name('addRemito');
Route::post('getRemito','RemitoController@getRemito')->name('getRemito');
Route::post('venderRemito','RemitoController@venderRemito')->name('venderRemito');
Route::post('facturarRemito','RemitoController@facturarRemito')->name('facturarRemito');

//VentaController

Route::get('addVenta','Admin\VentaController@addVenta')->name('addVenta');
Route::post('getVenta','Admin\VentaController@getVenta')->name('getVenta');
Route::post('deleteVenta','Admin\VentaController@deleteVenta')->name('deleteVenta');
Route::get('Ventas','Admin\VentaController@Ventas')->name('Ventas');
Route::post('getVentas','Admin\VentaController@getVentas')->name('getVentas');

//config
Route::post('poner_foto','UsuariosController@poner_foto')->name('poner_foto');
Route::post('cambio_contrasena','UsuariosController@cambio_contrasena')->name('cambio_contrasena');
