<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::paginate(10);
        return Response($users);
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

        $caminhoImagem = "imagens/placeholder-user.jpg";

        if ($request->hasFile('imagem')) {
            $imagem = $request->file('imagem');
            $nomeImagem = Str::uuid()->toString() . '.' . $imagem->getClientOriginalExtension();
            $imagem->move('api/imagens/', $nomeImagem);
            $caminhoImagem = 'imagens/' . $nomeImagem;
        }

        $request['senha'] = bcrypt($request['senha']);
        $request['imagem'] = $caminhoImagem;
        $data = $request->all();
        $user = User::create($data);
        return Response(['message' => 'Usuário cadastrado com sucesso'], Response::HTTP_OK);
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
        $verificaEmail = User::where('email', $request['email'])->first();

        if ($verificaEmail != null) {
            return Response(['message' => 'E-mail já cadastrado'], Response::HTTP_CONFLICT);
        }

        $data = $request->all();
        $user = User::find($data['id']);
        $userAuth = Auth::user();

        if ($userAuth->user_tipo !== "admin" && $userAuth->id !== $user->id) {
            return Response(['message' => 'Não é possível alterar os dados de outros usuário'], Response::HTTP_FORBIDDEN);
        }

        $caminhoImagem = "imagens/placeholder-user.jpg";

        if ($request->hasFile('imagem')) {
            $caminhoImagem = 'api/' . $user->imagem;

            if (File::exists($caminhoImagem)) {
                File::delete($caminhoImagem);
            }

            $imagem = $request->file('imagem');
            $nomeImagem = Str::uuid()->toString() . '.' . $imagem->getClientOriginalExtension();
            $imagem->move('api/imagens/', $nomeImagem);
            $caminhoImagem = 'imagens/' . $nomeImagem;
        }

        $user->update([
            'nome' => $request->nome,
            'sobrenome' => $request->sobrenome,
            'data_nascimento' => $request->data_nascimento,
            'email' => $request->email,
            'imagem' => $caminhoImagem,
            'rua_endereco' => $request->rua_endereco,
            'numero_endereco' => $request->numero_endereco,
            'complemento_endereco' => $request->complemento_endereco,
            'bairro_endereco' => $request->bairro_endereco,
            'estado_endereco' => $request->estado_endereco,
            'cidade_endereco' => $request->cidade_endereco,
            'cpf' => $request->cpf,
            'cnpj' => $request->cnpj,
            'telefone' => $request->telefone,
            'telefone_is_whatsapp' => $request->telefone_is_whatsapp,
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
