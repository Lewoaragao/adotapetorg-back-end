<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\Constants;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::paginate(Constants::REGISTROS_PAGINACAO);
        return Response($users, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = User::where('email', $request['email'])->first();

        if ($user != null) {
            return Response(['message' => 'E-mail já cadastrado'], Response::HTTP_CONFLICT);
        }

        $user = User::where('usuario', $request['usuario'])->first();

        if ($user != null) {
            return Response(['message' => 'Usuário já cadastrado'], Response::HTTP_CONFLICT);
        }

        $caminhoImagem = Constants::CAMINHO_IMAGEM_PLACEHOLDER['USER'];

        if ($request->hasFile('imagem')) {
            $imagem = $request->file('imagem');
            $nomeImagem = Str::uuid()->toString() . '.' . $imagem->getClientOriginalExtension();
            $imagem->move('api' . '/imagens/user/', $nomeImagem);
            $caminhoImagem = 'imagens/user/' . $nomeImagem;
        }

        if ($request->imagem_perfil_externo !== null) {
            $caminhoImagem = $request->imagem_perfil_externo;
        }

        $user = User::create([
            'usuario' => $request->usuario,
            'primeiro_nome' => $request->primeiro_nome,
            'sobrenome' => $request->sobrenome,
            'email' => $request->email,
            'senha' => bcrypt($request->senha),
            'imagem' => $caminhoImagem,
            'link' => 'https://adotapet.org/link/' . $request->usuario,
            'google_id' => $request->google_id == null ? null : bcrypt($request->google_id),
            'facebook_id' => $request->facebook_id == null ? null : bcrypt($request->facebook_id),
            'github_id' => $request->github_id == null ? null : bcrypt($request->github_id),
        ]);

        return Response(['message' => 'Usuário cadastrado com sucesso', 'user' => $user], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);

        if ($user == null) {
            return Response(['message' => 'Usuário não encontrado'], Response::HTTP_NOT_FOUND);
        }

        return Response($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $userAuth = Auth::user();

        $user = User::where('email', $request['email'])->first();

        if ($user != null) {
            return Response(['message' => 'E-mail já cadastrado'], Response::HTTP_CONFLICT);
        }

        $user = User::where('usuario', $request['usuario'])->first();

        if ($user != null) {
            return Response(['message' => 'Usuário já cadastrado'], Response::HTTP_CONFLICT);
        }

        $data = $request->all();
        $user = User::find($data['id']);
        $caminhoImagem = Constants::CAMINHO_IMAGEM_PLACEHOLDER['USER'];

        if ($userAuth->user_tipo !== "admin" && $userAuth->id !== $user->id) {
            return Response(
                [
                    'message' => 'Não é possível alterar os dados de outro usuário'
                ], Response::HTTP_FORBIDDEN
            );
        }

        if ($request->hasFile('imagem')) {
            $caminhoImagemAntiga = 'api/' . $user->imagem;
            $caminhoImagemPlaceholder = 'api/' . $caminhoImagem;

            if (File::exists($caminhoImagemAntiga) && $caminhoImagemAntiga != $caminhoImagemPlaceholder) {
                File::delete($caminhoImagemAntiga);
            }

            $imagem = $request->file('imagem');
            $nomeImagem = Str::uuid()->toString() . '.' . $imagem->getClientOriginalExtension();
            $imagem->move('api/imagens/user/', $nomeImagem);
            $caminhoImagem = '/imagens/user/' . $nomeImagem;
        }

        $user->update([
            'usuario' => $request->usuario,
            'is_pessoa' => $request->is_pessoa,
            'primeiro_nome' => $request->primeiro_nome,
            'sobrenome' => $request->sobrenome,
            'nome_organizacao' => $request->nome_organizacao,
            'sigla_organizacao' => $request->sigla_organizacao,
            'email' => $request->email,
            'senha' => bcrypt($request->senha),
            'flg_ativo' => $request->flg_ativo,
            'imagem' => $caminhoImagem,
            'telefone' => $request->telefone,
            'flg_telefone_whatsapp' => $request->flg_telefone_whatsapp,
            'celular' => $request->celular,
            'flg_celular_whatsapp' => $request->flg_celular_whatsapp,
            'link' => 'https://adotapet.org/link/' . $request->usuario,
            'endereco_cidade' => $request->endereco_cidade,
            'endereco_estado' => $request->endereco_estado,
            'endereco_pais' => $request->endereco_pais,
        ]);

        return Response($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        if ($user == null) {
            return Response(['message' => 'Usuário não encontrado'], Response::HTTP_NOT_FOUND);
        }

        $user->delete();

        return Response(['message' => 'Usuário foi removido com sucesso'], Response::HTTP_OK);
    }
}