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
     * @var User
     */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

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

        $caminhoImagem = "imagens/user/placeholder-user.jpg";

        if ($request->hasFile('imagem')) {
            $imagem = $request->file('imagem');
            $nomeImagem = Str::uuid()->toString() . '.' . $imagem->getClientOriginalExtension();
            $imagem->move('api' . '/imagens/user/', $nomeImagem);
            $caminhoImagem = 'imagens/user/' . $nomeImagem;
        }

        $user = User::create([
            'usuario' => $request->usuario,
            'primeiro_nome' => $request->primeiro_nome,
            'email' => $request->email,
            'sobrenome' => $request->sobrenome,
            'senha' => bcrypt($request->senha),
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

        if ($userAuth->user_tipo !== "admin" && $userAuth->id !== $user->id) {
            return Response(['message' => 'Não é possível alterar os dados de outros usuário'], Response::HTTP_FORBIDDEN);
        }

        if ($request->hasFile('imagem')) {
            $caminhoImagemAntiga = 'api/' . $user->imagem;

            if (File::exists($caminhoImagemAntiga)) {
                File::delete($caminhoImagemAntiga);
            }

            $imagem = $request->file('imagem');
            $nomeImagem = Str::uuid()->toString() . '.' . $imagem->getClientOriginalExtension();
            $imagem->move('api/imagens/user/', $nomeImagem);
            $caminhoImagem = '/imagens/user/' . $nomeImagem;
        }

        $user->update([
            'usuario' => $request->usuario,
            'primeiro_nome' => $request->primeiro_nome,
            'sobrenome' => $request->sobrenome,
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
