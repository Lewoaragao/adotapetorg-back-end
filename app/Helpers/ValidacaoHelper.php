<?php

namespace App\Helpers;

use App\Models\User;
use App\Support\Constants;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Http\Response;

class ValidacaoHelper
{
    public static function validaPermissao(string $id, string $tipo)
    {
        $user = User::find($id);
        $userAuth = Auth::user();

        if ($tipo == null) {
            $tipo = '';
        }

        if ($user === null) {
            throw new Exception('Usuário não encontrado', Response::HTTP_NOT_FOUND);
        }

        if ($userAuth->user_tipo !== Constants::USER_TIPO['ADMIN'] && $userAuth->id !== $user->id) {
            throw new Exception(
                'Não é possível alterar ' . $tipo . ' de outro usuário', Response::HTTP_UNAUTHORIZED
            );
        }

        return $user;
    }
}