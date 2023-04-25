<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Response;

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

// ROTA DE TESTE PARA SABER SE API ESTÁ FUNCIONANDO
Route::get('/test', function (Request $request) {
    $response = new Response(json_encode(['message' => 'Minha primeira resposta de API']));
    $response->header('Content-Type', 'application/json');
    return $response;
});

// ROTAS USUÁRIO AUTENTICADO
// Route::namespace('App\Http\Controllers\Api')->group(function(){
Route::group(['namespace' => 'App\Http\Controllers\Api', 'middleware' => 'auth:sanctum'], function () {

    // ROTA USERS
    Route::prefix('users')->group(function () {
        Route::get('/', 'UserController@index'); // LISTA TODOS OS USER
        Route::post('/', 'UserController@store'); // SALVA UM USER
        Route::get('/{id}', 'UserController@show'); // MOSTRA UM USER
        Route::put('/', 'UserController@update'); // ATUALIZA UM USER
        Route::delete('/{id}', 'UserController@destroy'); // DELETA UM USER
    });

    // ROTA PETS
    Route::prefix('pets')->group(function () {
        Route::get('/', 'PetController@index'); // LISTA TODOS OS PET
        Route::post('/', 'PetController@store'); // SALVA UM PET
        Route::get('/{id}', 'PetController@show'); // MOSTRA UM PET
        Route::put('/', 'PetController@update'); // ATUALIZA UM PET
        Route::delete('/{id}', 'PetController@destroy'); // DELETA UM PET
    });
});

// ROTAS USUÁRIO NÃO AUTENTICADO
Route::group(['namespace' => 'App\Http\Controllers\Api'], function () {

    // ROTA USERS
    Route::prefix('users')->group(function () {
        Route::post('/', 'UserController@store'); // SALVA UM USER
    });

    // ROTA PETS
    Route::prefix('pets')->group(function () {
        Route::get('/', 'PetController@index'); // LISTA TODOS OS PET
        Route::get('/{id}', 'PetController@show'); // MOSTRA UM PET
    });

    Route::post('/login', 'LoginController@loginUser');
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('/user', 'LoginController@userDetails');
        Route::get('/logout', 'LoginController@logout');
    });
});

Route::get('/outroTeste', function (Request $request) {
    $response = new Response(json_encode(['msg' => 'Outro teste']));
    $response->header('Content-Type', 'application/json');
    return $response;
});
