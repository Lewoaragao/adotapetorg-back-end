<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\Constants;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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

    /**
     * Login por meio de
     * autenticação de outras
     * plataformas: Google, Facebook, Github...
     */
    public function loginUserExterno(Request $request): Response
    {
        $loginExternoTipo = $request->login_externo_tipo;

        if ($loginExternoTipo == Constants::LOGIN_EXTERNO_TIPO['GOOGLE']) {

            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'google_id' => 'required',
            ]);

            if ($validator->fails()) {
                return Response(['message' => $validator->messagers()], Response::HTTP_UNAUTHORIZED);
            }

            $user = User::where('email', $request->email)->first();

            if ($user && Hash::check($request->google_id, $user->google_id)) {
                $success = $user->createToken('Google')->plainTextToken;
                return Response([
                    'token' => $success,
                    'usuario' => $user
                ], Response::HTTP_OK);
            }
        } elseif ($loginExternoTipo == Constants::LOGIN_EXTERNO_TIPO['FACEBOOK']) {

            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'facebook_id' => 'required',
            ]);

            if ($validator->fails()) {
                return Response(['message' => $validator->messagers()], Response::HTTP_UNAUTHORIZED);
            }

            $user = User::where('email', $request->email)->first();

            if ($user && Hash::check($request->facebook_id, $user->facebook_id)) {
                $success = $user->createToken('Facebook')->plainTextToken;
                return Response([
                    'token' => $success,
                    'usuario' => $user
                ], Response::HTTP_OK);
            }
        } elseif ($loginExternoTipo == Constants::LOGIN_EXTERNO_TIPO['GITHUB']) {

            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'github_id' => 'required',
            ]);

            if ($validator->fails()) {
                return Response(['message' => $validator->messagers()], Response::HTTP_UNAUTHORIZED);
            }

            $user = User::where('email', $request->email)->first();

            if ($user && Hash::check($request->github_id, $user->github_id)) {
                $success = $user->createToken('Github')->plainTextToken;
                return Response([
                    'token' => $success,
                    'usuario' => $user
                ], Response::HTTP_OK);
            }
        }

        return Response(['message' => 'Erro de autenticação'], Response::HTTP_UNAUTHORIZED);
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