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
        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = User::where('email', $request['email'])->first();

        if ($user != null) {
            return response()->json(
                ['erro' => 'E-mail já cadastrado.'],
                Response::HTTP_CONFLICT
            );
        }

        $request['password'] = bcrypt($request['password']);
        $data = $request->all();
        $user = User::create($data);
        return response()->json($user);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);

        if ($user == null) {
            return response()->json(
                ['erro' => 'Usuário não encontrado'],
                Response::HTTP_NOT_FOUND
            );
        }

        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $data = $request->all();
        $user = User::find($data['id']);
        $user->update($data);
        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        if ($user == null) {
            return response()->json(
                ['data' => [
                    'msg' => 'Usuário não encontrado'
                ]],
                Response::HTTP_NOT_FOUND
            );
        }

        $user->delete();
        return response()->json(
            ['data' => [
                'msg' => 'Usuário foi removido com sucesso!'
            ]]
        );
    }
}
