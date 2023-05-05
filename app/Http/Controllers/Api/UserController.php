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
    public function update(Request $request, string $id)
    {
        $userAuth = Auth::user();
        $user = User::find($id);

        if($userAuth->user_tipo !== "admin" && $userAuth->id !== $user->id) {
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

        $request['imagem'] = $caminhoImagem;
        $data = $request->all();
        $user->update($data);
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
