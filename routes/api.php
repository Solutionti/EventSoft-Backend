<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ServiciosController; 
use App\Http\Controllers\IniciarsesionController; 

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(ServiciosController::class)->group(function () {
    
    Route::get('getDepartamentos', 'getDepartamentos');  
    Route::get('getServiciosInicio', 'getServiciosInicio');  
    Route::get('getServicioId', 'getServicioId');  
    Route::post('CrearInscripcionesDetalle', 'CrearInscripcionesDetalle');
    // administracion
    Route::get('getUsuariosAdministrador', 'getUsuariosAdministrador'); 
    Route::get('getUsuariosDeportistas', 'getUsuariosDeportistas'); 
    Route::get('codigosPromocionales', 'codigosPromocionales');  
    Route::get('getPatrocinios', 'getPatrocinios');
    Route::get('getInscripciones', 'getInscripciones');
    Route::get('getDetalleInscripciones', 'getDetalleInscripciones');
    Route::get('getDatosInscripcion', 'getDatosInscripcion');
    // 
    Route::post('crearRegalo', 'crearRegalo');
    Route::post('crearServicio', 'crearServicio');
    Route::post('crearPatrocinio', 'crearPatrocinio');
    Route::post('EntregaKits', 'EntregaKits');
    Route::post('cambiarEstadoPedido', 'cambiarEstadoPedido');
    Route::get('canjearCodigoPromocional', 'canjearCodigoPromocional');
    Route::post('crearUsuario', 'crearUsuario');
    // 
    Route::get('getPedidoDeportista', 'getPedidoDeportista');

});

Route::controller(IniciarsesionController::class)->group(function () {
    Route::post('iniciarsesion', 'login');
});