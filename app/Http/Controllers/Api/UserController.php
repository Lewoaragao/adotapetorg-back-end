<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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

        $request['senha'] = bcrypt($request['senha']);
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
        $data = $request->all();
        $user = User::find($data['id']);
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
