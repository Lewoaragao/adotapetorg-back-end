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
        Route::post('/visualizar/{id}', 'PetController@showPet'); // MOSTRA UM PET
        Route::post('/', 'PetController@store'); // SALVA UM PET
        Route::post('/{id}', 'PetController@update'); // ATUALIZA UM PET
        Route::delete('/{id}', 'PetController@destroy'); // DELETA UM PET
        Route::post('/{id}/favoritar', 'PetController@favoritar'); // FAVORITA UM PET
        Route::post('/{id}/desfavoritar', 'PetController@desfavoritar'); // DESFAVORITA UM PET
        Route::post('/favoritos/user', 'PetController@petsFavoritosUser'); // BUSCA OS PETS FAVORITOS DO USER
        Route::post('/cadastrados/user', 'PetController@petsCadastradosUser'); // BUSCA OS PETS CADASTRADOS DO USER
    });

    // ROTA LINKS
    Route::prefix('links')->group(function () {
        Route::post('/', 'LinkController@store'); // SALVA UM LINK DO USUÁRIO
        Route::post('/visualizar/{id}', 'LinkController@show'); // MOSTRA UM LINK
        Route::post('/atualizar/{id}', 'LinkController@update'); // ATUALIZA UM LINK DO USUÁRIO
        Route::post('/deletar/{id}', 'LinkController@destroy'); // DELETA UM LINK DO USUÁRIO
    });

    // ROTA BLOG
    Route::prefix('blog')->group(function () {
        Route::post('/{slug}', 'BlogPostagemController@showPostagemTagUserAuth'); // MOSTRA UMA POSTAGEM
        Route::post('/cadastrar/tag', 'BlogPostagemController@storeTag'); // SALVA UMA TAG DO BLOG
        Route::post('/cadastrar/postagem', 'BlogPostagemController@storePostagem'); // SALVA UMA POSTAGEM DO BLOG
        Route::post('/atualizar/postagem/{id}', 'BlogPostagemController@updatePostagem'); // SALVA UMA POSTAGEM DO BLOG
        Route::post('/cadastrar/postagem/tag', 'BlogPostagemController@storePostagemTag'); // SALVA VÁRIAS TAGS DA POSTAGEM DO BLOG
        Route::post('/{id}/favoritar', 'BlogPostagemController@favoritarPostagem'); // FAVORITA UMA POSTAGEM
        Route::post('/{id}/desfavoritar', 'BlogPostagemController@desfavoritarPostagem'); // DESFAVORITA UMA POSTAGEM
        Route::post('/postagens/favoritas/user', 'BlogPostagemController@postagensFavoritasUser'); // BUSCA AS POSTAGENS FAVORITAS DO USER
        Route::post('/postagens/cadastradas/user', 'BlogPostagemController@postagensCadastradasUser'); // BUSCA AS POSTAGENS CADASTRADAS DO USER
        Route::post('/deletar/{id}', 'BlogPostagemController@destroyPostagem'); // DELETA UMA POSTAGEM DO USUÁRIO
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

    // ROTA LINKS
    Route::prefix('links')->group(function () {
        Route::get('/{nomeUser}', 'LinkController@userLinks'); // LISTA TODOS OS LINKS DO USUÁRIO
    });

    // ROTA BLOG
    Route::prefix('blog')->group(function () {
        Route::get('/todas/postagens', 'BlogPostagemController@indexPostagens'); // SALVA UMA TAG DO BLOG
        Route::get('/{slug}', 'BlogPostagemController@showPostagemTag'); // MOSTRA UMA POSTAGEM
    });

    // ROTA AUTH
    Route::post('/login', 'LoginController@loginUser'); // LOGA USUARIO NA SESSAO
});