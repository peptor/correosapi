<?php

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

Route::get('/', 'correosController@obrirView');
// Pre producció SOAP
Route::get('/llistaFuncions/{test}', 'correosController@llistaFuncions');
Route::get('/PreRegistroEnvio/{test}', 'correosController@PreRegistroEnvio');
Route::get('/SolicitudEtiquetaOp/{test}', 'correosController@SolicitudEtiqueta');
Route::get('/AnularOp/{test}', 'correosController@AnularOp');

Route::get('/preLocalizadorOficinas', 'correosController@preLocalizadorOficinas');



Route::get('/preCurlPreRegistroEnvio', 'WSCurlCorreosPreRegistroServiceController@preRegistroEnvio');
Route::get('/preCurAnularOp', 'WSSoapCurlCorreosController@preAnularOp');
Route::get('/preCurlAnularOp', 'WSCurlCorreosPreRegistroServiceController@preAnularOp');

Route::get('/preCurlLocalizadorOficinas', 'WSSoapCurlCorreosController@preLocalizadorOficinas');


