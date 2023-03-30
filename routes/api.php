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

// INICIO ROTAS DE TESTE
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
// FIM ROTAS DE TESTE

// INICIO ROTAS DE PRODUCAO
Route::namespace('App\Http\Controllers\Api')->group(function(){

    // ROTA PETS
    Route::prefix('pets')->group(function(){
        Route::get('/', 'PetController@index'); // LISTA TODOS OS PET
        Route::post('/', 'PetController@store'); // SALVA UM PET
        Route::get('/{id}', 'PetController@show'); // MOSTRA UM PET
        Route::put('/{id}', 'PetController@update'); // ATUALIZA UM PET
        Route::delete('/{id}', 'PetController@destroy'); // DELETA UM PET
    });

});
// FIM ROTAS DE PRODUCAO
