<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ValidacaoHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\Constants;
use Exception;
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
        try {
            $users = User::paginate(Constants::REGISTROS_PAGINACAO);
            return Response($users, Response::HTTP_OK);
        } catch (Exception $e) {
            return Response(['message' => $e->getMessage()], $e->getCode());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $this->validaUsuario($request);
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
        } catch (Exception $e) {
            return Response(['message' => $e->getMessage()], $e->getCode());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $user = ValidacaoHelper::validaPermissao($id, null);
            return Response($user);
        } catch (Exception $e) {
            return Response(['message' => $e->getMessage()], $e->getCode());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            $user = User::find($request->id);
            $this->validaUsuario($request);
            $user = ValidacaoHelper::validaPermissao($user->id, 'dados');

            $user->update([
                'usuario' => $request->usuario,
                'is_pessoa' => $request->is_pessoa,
                'primeiro_nome' => $request->primeiro_nome,
                'sobrenome' => $request->sobrenome,
                'nome_organizacao' => $request->nome_organizacao,
                'sigla_organizacao' => $request->sigla_organizacao,
                'email' => $request->email,
                'telefone' => $request->telefone,
                'flg_telefone_whatsapp' => $request->flg_telefone_whatsapp,
                'celular' => $request->celular,
                'flg_celular_whatsapp' => $request->flg_celular_whatsapp,
                'link' => 'https://adotapet.org/link/' . $request->usuario,
                'id_pais' => $request->id_pais,
                'endereco_pais' => $request->endereco_pais,
                'id_estado' => $request->id_estado,
                'endereco_estado' => $request->endereco_estado,
                'id_cidade' => $request->id_cidade,
                'endereco_cidade' => $request->endereco_cidade,
            ]);

            return Response(['message' => 'Perfil atualizado com sucesso', 'user' => $user], Response::HTTP_OK);
        } catch (Exception $e) {
            return Response(['message' => $e->getMessage()], $e->getCode());
        }
    }

    public function updateImagemUser(Request $request, string $id)
    {
        try {
            if ($request->imagem == null) {
                return Response(['message' => 'Necessário envio de imagem'], Response::HTTP_CONFLICT);
            }

            $user = ValidacaoHelper::validaPermissao($id, 'imagem');

            $caminhoImagem = Constants::CAMINHO_IMAGEM_PLACEHOLDER['USER'];

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
                'imagem' => $caminhoImagem,
            ]);

            return Response(
                ['message' => 'Imagem atualizada com sucesso', 'imagem' => $caminhoImagem], Response::HTTP_OK
            );
        } catch (Exception $e) {
            return Response(['message' => $e->getMessage()], $e->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = ValidacaoHelper::validaPermissao($id, null);

            $caminhoImagem = Constants::CAMINHO_IMAGEM_PLACEHOLDER['USER'];
            $caminhoImagemAntiga = 'api/' . $user->imagem;
            $caminhoImagemPlaceholder = 'api/' . $caminhoImagem;

            if ($caminhoImagemAntiga !== $caminhoImagemPlaceholder && File::exists($caminhoImagemAntiga)) {
                File::delete($caminhoImagemAntiga);
            }

            $user->delete();

            return Response(['message' => 'Usuário foi removido com sucesso'], Response::HTTP_OK);
        } catch (Exception $e) {
            return Response(['message' => $e->getMessage()], $e->getCode());
        }
    }

    public function destroyImagemUser(string $id)
    {
        try {
            $user = ValidacaoHelper::validaPermissao($id, 'imagem');

            if ($user->imagem === null) {
                return Response(['message' => 'Não há imagem para ser removida'], Response::HTTP_NOT_FOUND);
            }

            $caminhoImagem = Constants::CAMINHO_IMAGEM_PLACEHOLDER['USER'];
            $caminhoImagemuser = 'api/' . $user->imagem;
            $caminhoImagemPlaceholder = 'api/' . $caminhoImagem;

            if ($caminhoImagemuser == $caminhoImagemPlaceholder) {
                return Response(['message' => 'Não é possível apagar a imagem padrão'], Response::HTTP_BAD_REQUEST);
            }

            if (File::exists($caminhoImagemuser) && $caminhoImagemuser !== $caminhoImagemPlaceholder) {
                File::delete($caminhoImagemuser);
            }

            $user->update([
                'imagem' => $caminhoImagem,
            ]);

            return Response(
                ['message' => 'Imagem removida com sucesso', 'imagem' => $caminhoImagem], Response::HTTP_OK
            );
        } catch (Exception $e) {
            return Response(['message' => $e->getMessage()], $e->getCode());
        }
    }

    public function validaUsuario(Request $request)
    {
        $userAuth = Auth::user();
        $validaEmail = User::where('email', $request->email)->first();
        $validaUsuario = User::where('usuario', $request->usuario)->first();

        if ($userAuth == null) {
            if ($validaEmail !== null) {
                throw new Exception('E-mail já cadastrado', Response::HTTP_CONFLICT);
            }

            if ($validaUsuario !== null) {
                throw new Exception('Usuário já cadastrado', Response::HTTP_CONFLICT);
            }
        } else {
            if ($userAuth->email !== $request->email && $validaEmail !== null) {
                throw new Exception('E-mail já cadastrado', Response::HTTP_CONFLICT);
            }

            if ($userAuth->usuario !== $request->usuario && $validaUsuario !== null) {
                throw new Exception('Usuário já cadastrado', Response::HTTP_CONFLICT);
            }
        }

        return Response::HTTP_OK;
    }
}