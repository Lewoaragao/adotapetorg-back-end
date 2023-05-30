<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aqui é onde você pode registrar rotas de API para seu aplicativo. Esses
| as rotas são carregadas pelo RouteServiceProvider e todas elas serão
| ser atribuído ao grupo de middleware "api". Faça algo ótimo!
|
*/

// ROTA DE TESTE PARA SABER SE API ESTÁ FUNCIONANDO
Route::get('/test', function (Request $request) {
    $response = new Response(json_encode(['message' => 'Minha primeira resposta de API']));
    $response->header('Content-Type', 'application/json');
    return $response;
});

// ROTAS USUÁRIO AUTENTICADO
Route::group(['namespace' => 'App\Http\Controllers\Api', 'middleware' => 'auth:sanctum'], function () {

    // ROTA USERS
    Route::prefix('users')->group(function () {
        Route::get('/', 'UserController@index'); // LISTA TODOS OS USER
        Route::get('/{id}', 'UserController@show'); // MOSTRA UM USER
        Route::post('/', 'UserController@store'); // SALVA UM USER
        Route::post('/atualizar', 'UserController@update'); // ATUALIZA UM USER
        Route::delete('/{id}', 'UserController@destroy'); // DELETA UM USER
    });

    // ROTA PETS
    Route::prefix('pets')->group(function () {
        Route::get('/', 'PetController@index'); // LISTA TODOS OS PET
        Route::get('/{id}', 'PetController@show'); // MOSTRA UM PET
        Route::post('/', 'PetController@store'); // SALVA UM PET
        Route::post('/{id}', 'PetController@update'); // ATUALIZA UM PET
        Route::delete('/{id}', 'PetController@destroy'); // DELETA UM PET
        Route::post('/{id}/favoritar', 'PetController@favoritar'); // FAVORITA UM PET
        Route::post('/{id}/desfavoritar', 'PetController@desfavoritar'); // DESFAVORITA UM PET
        Route::post('/favoritos/user', 'PetController@petsFavoritosUser'); // BUSCA OS PETS FAVORITOS DO USER
        Route::post('/cadastrados/user', 'PetController@petsCadastradosUser'); // BUSCA OS PETS CADASTRADOS DO USER
    });

    // ROA LINKS
    Route::prefix('links')->group(function () {
        Route::post('/', 'LinkController@store'); // SALVA UM LINK DO USUÁRIO
        Route::post('/visualizar/{id}', 'LinkController@show'); // MOSTRA UM LINK
        Route::post('/atualizar/{id}', 'LinkController@update'); // ATUALIZA UM LINK DO USUÁRIO
        Route::post('/deletar/{id}', 'LinkController@destroy'); // DELETA UM LINK DO USUÁRIO
    });

    // ROTAS AUTH
    Route::get('/user', 'LoginController@userDetails'); // MOSTRA O USUARIO LOGADO
    Route::get('/logout', 'LoginController@logout'); // DESLOGA USUARIO DA SESSAO
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

    // ROA LINKS
    Route::prefix('links')->group(function () {
        Route::get('/{nomeUser}', 'LinkController@userLinks'); // LISTA TODOS OS LINKS DO USUÁRIO
    });

    // ROTA AUTH
    Route::post('/login', 'LoginController@loginUser'); // LOGA USUARIO NA SESSAO
});
