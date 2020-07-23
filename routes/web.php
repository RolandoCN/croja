<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('home');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/restaurarCont','RestarurarContraseniaController@index')->name('restaurarCont');

//RUTA PARA CAMBIAR LA CONTRASEÃ‘A
Route::post('/cambiocontrasena', 'RegistrarUsuarioController@cambiarcontrasena')->name('cambiocontrasena');
Route::post('/cambiarContraseÃ±aperdida', 'RestarurarContraseniaController@cambiarContraseÃ±aperdida')->name('cambiarContraseÃ±aperdida');
Route::get('/restaurarCont','RestarurarContraseniaController@index')->name('restaurarCont');
//=======================================================================
// para validar que tipo de usuario despues de loguearce
Route::get('/validarTipoUsuario','ValidarController@verificarUsuario');
Route::get('/loginTipoFP','ValidarController@loginTipoFP')->name('loginTipoFP');

Route::resource('/registrarUsuario','RegistrarController');


//=============== RUTAS PARA LA ADMINISTRACION DE PERMISOS DE RUTAS ================
Route::get('/gestionPermisos','GestionPermisosController@index')->name('gestionPermisos');
Route::resource('/gestionMenu','MenuController');
Route::resource('/gestionGestion','GestionController');
Route::resource('/gestionTipoFP','TipoFPController');
Route::resource('/asignarGestionTipo','TipoFPGestionController');
Route::post('/asignarTipoFPFuncionario','Regis_UserController@asignarTipoFPFuncionario');
Route::delete('/eliminarTipoFPFuncionario/{idus001_tipofp}','Regis_UserController@eliminarTipoFPFuncionario');	
Route::get('/asignarTipoFPFuncionarioMostrar/{idus001?}','Regis_UserController@asignarTipoFPFuncionarioMostrar');



///**************************GESTION PROVINCIA***********************************************///////////////////////////

//RUTAS PARA LA GESTION DE RESIDENCIA
Route::prefix('gestionResidencia')->group(function(){
    Route::resource('/canton','CantonController');
    Route::resource('/provincia','ProvinciaController')->middleware('auth');

});


//**********************************GESTION PERSONA ****************************************/////////


//RUTAS PARA LA GESTION PERSONA
Route::prefix('gestionPersona')->group(function(){
    Route::resource('/registro','PersonaController')->middleware('auth');
   // Route::resource('/provincia','ProvinciaController')->middleware('auth');

});

//**********************************GESTION USUARIO ****************************************/////////


//RUTAS PARA LA GESTION TIPOUSUARIO
Route::prefix('gestionUsuario')->group(function(){
    Route::resource('/tipo','TipoUsuarioController')->middleware('auth');
   // Route::resource('/provincia','ProvinciaController')->middleware('auth');

});

//RUTAS PARA LA GESTION USUARIO
Route::prefix('gestionUsuario')->group(function(){
    Route::resource('/registro','RegistrarUsuarioController')->middleware('auth');
   // Route::resource('/provincia','ProvinciaController')->middleware('auth');

});



//**********************************GESTION ACCESOS ****************************************/////////


//RUTAS PARA LA GESTION RUTAS
Route::prefix('gestionAccesos')->group(function(){
    Route::resource('/ruta','RutaController')->middleware('auth');
   // Route::resource('/provincia','ProvinciaController')->middleware('auth');

});

//RUTAS PARA LA GESTION RUTAS POR TIPOUSUARIO
Route::prefix('gestionAccesos')->group(function(){
    Route::resource('/opciones','OpcionesController')->middleware('auth');
   // Route::resource('/provincia','ProvinciaController')->middleware('auth');

});

//RUTAS PARA LA GESTION MENU
Route::prefix('gestionAccesos')->group(function(){
    Route::resource('/menu','MenuController')->middleware('auth');
   // Route::resource('/provincia','ProvinciaController')->middleware('auth');

});


/////////////////////////pruebas del menu ///////////////////////////
//ruta de prueba(/////////////////)
Route::prefix('gestionAccesos')->group(function(){
    Route::resource('/pruebamenu','PruebaMenuController')->middleware('auth');
   // Route::resource('/provincia','ProvinciaController')->middleware('auth');

});


//***********************************GESTION SERVICIOS ******************************************////////
//RUTAS PARA LA GESTION DE SERVICIOS
Route::prefix('gestionServicios')->group(function(){
    Route::resource('/registro','ServicioController');
    Route::resource('/detalle','DetalleServicioController')->middleware('auth');
    Route::resource('/servicio','EmisionController')->middleware('auth');

   Route::get('reportecarnet/{idpersona}', 'EmisionController@reportecarnet')->middleware('auth');


});


//***********************************GESTION VALIDACION ******************************************////////
//RUTAS PARA LA GESTION DE SERVICIOS
Route::prefix('validacion')->group(function(){
    Route::resource('/carnet','ValidacionController');

    Route::get('carnet/{idemision}', 'ValidacionController@carnet')->middleware('auth');
   Route::get('reportecarnet/{idpersona}', 'ValidacionController@reportecarnet');
  Route::get('/{busqueda?}/codigo','ValidacionController@codigo');



});