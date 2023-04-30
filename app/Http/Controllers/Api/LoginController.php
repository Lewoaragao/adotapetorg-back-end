<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function loginUser(Request $request): Response
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'senha' => 'required',
        ]);

        if ($validator->fails()) {
            return Response(['message' => $validator->messagers()], Response::HTTP_UNAUTHORIZED);
        }

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->senha, $user->senha)) {
            $success = $user->createToken('MyApp')->plainTextToken;
            return Response([
                'token' => $success,
                'usuario' => $user
            ], Response::HTTP_OK);
        }
        return Response(['message' => 'Email ou senha incorreto'], Response::HTTP_UNAUTHORIZED);
    }

    public function userDetails(): Response
    {
        $user = Auth::user();
        return Response(['usuario' => $user], Response::HTTP_OK);
    }

    public function logout(): Response
    {
        $user = Auth::user();
        $user->currentAccessToken()->delete();
        return Response(['message' => 'Logout do usuário com sucesso'], Response::HTTP_OK);
    }
}
