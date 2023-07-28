<?php

namespace App\Support;

/**
 * Class Constants
 */
final class Constants
{

    public const REGISTROS_PAGINACAO = 12;

    public const USER_TIPO = [
        'USER',
        'ADMIN'
    ];

    public const LINK_TIPO = [
        'EXTERNO' => 9,
    ];

    public const LOGIN_EXTERNO_TIPO = [
        'GOOGLE' => 1,
        'FACEBOOK' => 2,
        'GITHUB' => 3,
    ];
}
