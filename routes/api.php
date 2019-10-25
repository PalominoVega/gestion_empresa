<?php

use Illuminate\Http\Request;

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

/* login */

Route::post('login', 'LoginController@postLogin')->name('postlogin');
/* reset password */
Route::post('resetPassword', 'UserController@email');
Route::put('newPassword/{token}', 'UserController@new_contrasenia');
/* cambiar contraseña */
Route::put('contraseña', 'UserController@cambiar_contrasenia')->middleware('auth.token');
Route::put('usuario/estado/{id}', 'UserController@cambiar_Estado')->middleware('auth.token');

Route::get('resumen/mes', 'ResumenController@index')->middleware('auth.token');

Route::resource('producto', 'ProductoController')->only([
    'index','store','update','show'
])->middleware('auth.token');
Route::resource('empresa', 'EmpresaController');
Route::resource('compra', 'CompraController')->middleware('auth.token');
Route::get('venta/{id}/facturacion', 'VentaController@facturacion')->middleware('auth.token');
Route::post('venta/{id}/anular', 'VentaController@anular')->middleware('auth.token');
Route::get('venta/{id}/consulta', 'VentaController@consulta_anulacion')->middleware('auth.token');
Route::resource('venta', 'VentaController')->middleware('auth.token');
/**
 * Caja
 */
Route::get('/caja/libre', 'CajaController@libre')->middleware('auth.token');
Route::resource('caja', 'CajaController')->middleware('auth.token');
Route::get('/movimientocaja/actual', 'MovimientoCajaController@actual')->middleware('auth.token');
Route::post('/movimientocaja/cierre', 'MovimientoCajaController@cierre')->middleware('auth.token');
Route::resource('movimientocaja', 'MovimientoCajaController')->middleware('auth.token');

Route::resource('puesto', 'PuestoController')->middleware('auth.token');
Route::resource('colaborador', 'ColaboradorController')->middleware('auth.token');
Route::resource('concepto', 'ConceptoController')->middleware('auth.token');
Route::resource('proveedor', 'ProveedorController')->middleware('auth.token');
Route::get('/usuario/consulta', 'UserController@consulta')->middleware('auth.token');
Route::resource('usuario', 'UserController')->middleware('auth.token');
Route::get('kardex', 'KardexController@index')->middleware('auth.token');
Route::get('stock', 'KardexController@stock')->middleware('auth.token');
Route::get('lote', 'KardexController@lote')->middleware('auth.token');
Route::get('reorden', 'KardexController@reorden')->middleware('auth.token');
Route::get('salidas', 'KardexController@salidas')->middleware('auth.token');
Route::resource('notificacion', 'NotificacionController')->middleware('auth.token');
Route::get('cliente/consulta', 'ClienteController@search')->middleware('auth.token');

Route::get('listarconcepto', 'ConceptoController@listar')->middleware('auth.token');


Route::get('test',function (){
    $empresa_id=1;
    $apiKey="AIzaSyCRRFu54sUpJaRpnWiR13Z5Zce_AzCPPhg";
    $fields=array('to'=>'/topics/fe-taxi-'.$empresa_id,
    'notification'=>array(
        'title'=>"GESTION DE EMPRESA",
        'body'=>"INSUMOS POR VENCER",
        'icon'=>'http://127.0.0.1:8000/img/logo.png',
        'click_action'=>"https://www.conoceatuconductor.com/vencidos"
    ));
    $url='https://fcm.googleapis.com/fcm/send';
    $headers=array('Authorization: key='.$apiKey,'Content-Type: application/json');
    $curl=curl_init();
    curl_setopt($curl,CURLOPT_URL,$url);
    curl_setopt($curl,CURLOPT_POST,true);
    curl_setopt($curl,CURLOPT_HTTPHEADER,$headers);
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);
    curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($fields));
    echo curl_exec($curl);
    curl_close($curl);
});
