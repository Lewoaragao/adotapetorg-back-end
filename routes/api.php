<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ROTAS DE TESTE
Route::get('/test', function (Request $request) {

    // dd($request -> headers -> all());
    // dd($request -> headers -> get('Authorization'));

    $response = new \Illuminate\Http\Response(json_encode(['msg' => 'Minha primeira resposta de API']));
    $response -> header('Content-Type', 'application/json');
    return $response;
});

// ROTAS DE PRODUTO
// ANTIGO
// Route::get('/products', function () {
    // return \App\Models\Product::all();
// });

// NOVO
// Route::get('/products', '\App\Http\Controllers\Api\ProductController@index');

// AGRUPANDO
Route::namespace('App\Http\Controllers\Api')->group(function(){

    // ROTA PRODUTOS
    Route::prefix('products')->group(function(){
        Route::get('/', 'ProductController@index');
        Route::get('/{id}', 'ProductController@show');
        Route::post('/', 'ProductController@save');
        Route::put('/', 'ProductController@update');
        Route::patch('/', 'ProductController@update'); // pedaços de atualização de um objeto
        Route::delete('/{id}', 'ProductController@delete');
    });

    Route::resource('/users', 'UserController');

});
