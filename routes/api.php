<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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
    $response = new Response(json_encode([
        'message' => 'Minha primeira resposta de API',
        'request' => $request
    ]), Response::HTTP_OK);
    $response->header('Content-Type', 'application/json');
    return $response;
});

// ROTAS USUÁRIO AUTENTICADO
Route::group(['namespace' => 'App\Http\Controllers\Api', 'middleware' => 'auth:sanctum'], function () {

    // ROTA USERS
    Route::prefix('users')->group(function () {
        // LISTA TODOS OS USER
        Route::get('/', 'UserController@index');
        // MOSTRA UM USER
        Route::get('/{id}', 'UserController@show');
        // SALVA UM USER
        Route::post('/', 'UserController@store');
        // ATUALIZA UM USER
        Route::post('/atualizar/{id}', 'UserController@update');
        // ATUALIZA A IMAGEM DE PERFIL DO USUÁRIO
        Route::post('/atualizar/imagem/{id}', 'UserController@updateImagemUser');
        // DELETA UM USER
        Route::delete('/{id}', 'UserController@destroy');
        // DELETA A IMAGEM DE PERFIL DO USUÁRIO
        Route::post('/deletar/imagem/{id}', 'UserController@destroyImagemUser');
    });

    // ROTA PETS
    Route::prefix('pets')->group(function () {
        // LISTA TODOS OS PET
        Route::get('/', 'PetController@index');
        // MOSTRA UM PET
        Route::post('/visualizar/{id}', 'PetController@showPet');
        // SALVA UM PET
        Route::post('/', 'PetController@store');
        // ATUALIZA UM PET
        Route::post('/{id}', 'PetController@update');
        // DELETA UM PET
        Route::post('/deletar/{id}', 'PetController@destroy');
        // FAVORITA UM PET
        Route::post('/{id}/favoritar', 'PetController@favoritar');
        // DESFAVORITA UM PET
        Route::post('/{id}/desfavoritar', 'PetController@desfavoritar');
        // BUSCA OS PETS FAVORITOS DO USER
        Route::post('/favoritos/user', 'PetController@petsFavoritosUser');
        // BUSCA OS PETS CADASTRADOS DO USER
        Route::post('/cadastrados/user', 'PetController@petsCadastradosUser');
        // BUSCA AS RAÇAS DO TIPO DE PET SELECIONADO
        Route::post('/racas/{id}', 'PetController@racasPetTipoId');
        // ATUALIZA UMA IMAGEM DE PET
        Route::post('/atualizar/imagem/{id}', 'PetController@updateImagemPet');
        // DELETA UMA IMAGEM DE PET DO USUÁRIO
        Route::post('/deletar/imagem/{id}', 'PetController@destroyImagemPet');
    });

    // ROTA LINKS
    Route::prefix('links')->group(function () {
        // SALVA UM LINK DO USUÁRIO
        Route::post('/', 'LinkController@store');
        // MOSTRA UM LINK
        Route::post('/visualizar/{id}', 'LinkController@show');
        // ATUALIZA UM LINK DO USUÁRIO
        Route::post('/atualizar/{id}', 'LinkController@update');
        // ATUALIZA UMA IMAGEM DE LINK
        Route::post('/atualizar/imagem/{id}', 'LinkController@updateImagemLink');
        // DELETA UM LINK DO USUÁRIO
        Route::post('/deletar/{id}', 'LinkController@destroy');
        // DELETA UMA IMAGEM DE LINK DO USUÁRIO
        Route::post('/deletar/imagem/{id}', 'LinkController@destroyImagemLink');
    });

    // ROTA BLOG
    Route::prefix('blog')->group(function () {
        // MOSTRA UMA POSTAGEM
        Route::post('/{slug}', 'BlogPostagemController@showPostagemTagUserAuth');
        // SALVA UMA TAG DO BLOG
        Route::post('/cadastrar/tag', 'BlogPostagemController@storeTag');
        // SALVA UMA POSTAGEM DO BLOG
        Route::post('/cadastrar/postagem', 'BlogPostagemController@storePostagem');
        // SALVA UMA POSTAGEM DO BLOG
        Route::post('/atualizar/postagem/{id}', 'BlogPostagemController@updatePostagem');
        // ATUALIZA UMA IMAGEM DE POSTAGEM DO BLOG
        Route::post('/atualizar/imagem/{id}', 'BlogPostagemController@updateImagemPostagem');
        // SALVA VÁRIAS TAGS DA POSTAGEM DO BLOG
        Route::post('/cadastrar/postagem/tag', 'BlogPostagemController@storePostagemTag');
        // FAVORITA UMA POSTAGEM
        Route::post('/{id}/favoritar', 'BlogPostagemController@favoritarPostagem');
        // DESFAVORITA UMA POSTAGEM
        Route::post('/{id}/desfavoritar', 'BlogPostagemController@desfavoritarPostagem');
        // BUSCA AS POSTAGENS FAVORITAS DO USER
        Route::post('/postagens/favoritas/user', 'BlogPostagemController@postagensFavoritasUser');
        // BUSCA AS POSTAGENS CADASTRADAS DO USER
        Route::post('/postagens/cadastradas/user', 'BlogPostagemController@postagensCadastradasUser');
        // DELETA UMA POSTAGEM DO USUÁRIO
        Route::post('/deletar/{id}', 'BlogPostagemController@destroyPostagem');
        // DELETA UMA IMAGEM DE POSTAGEM DO USUÁRIO
        Route::post('/deletar/imagem/{id}', 'BlogPostagemController@destroyImagemPostagem');
    });

    // ROTAS AUTH
    // MOSTRA O USUARIO LOGADO
    Route::get('/user', 'LoginController@userDetails');
    // DESLOGA USUARIO DA SESSAO
    Route::get('/logout', 'LoginController@logout');
});

// ROTAS USUÁRIO NÃO AUTENTICADO
Route::group(['namespace' => 'App\Http\Controllers\Api'], function () {

    // ROTA USERS
    Route::prefix('users')->group(function () {
        // SALVA UM USER
        Route::post('/', 'UserController@store');
    });


    // ROTA PETS
    Route::prefix('pets')->group(function () {
        // LISTA TODOS OS PETS
        Route::get('/', 'PetController@index');
        // MOSTRA UM PET
        Route::get('/{id}', 'PetController@show');
    });

    // ROTA LINKS
    Route::prefix('links')->group(function () {
        // LISTA TODOS OS LINKS DO USUÁRIO
        Route::get('/{nomeUser}', 'LinkController@userLinks');
    });

    // ROTA BLOG
    Route::prefix('blog')->group(function () {
        // SALVA UMA TAG DO BLOG
        Route::get('/todas/postagens', 'BlogPostagemController@indexPostagens');
        // MOSTRA UMA POSTAGEM
        Route::get('/{slug}', 'BlogPostagemController@showPostagemTag');
    });

    // ROTA AUTH
    // LOGA USUARIO NA SESSAO
    Route::post('/login', 'LoginController@loginUser');
    // LOGA USUARIO NA SESSAO APÓS AUTENTICAÇÃO EXTERNA: GOOGLE, FACEBOOK, GITHUB...
    Route::post('/login/externo', 'LoginController@loginUserExterno');
});