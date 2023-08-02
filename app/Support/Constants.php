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
        'EXTERNO' => 1,
    ];

    public const LOGIN_EXTERNO_TIPO = [
        'GOOGLE' => 1,
        'FACEBOOK' => 2,
        'GITHUB' => 3,
    ];

    public const CAMINHO_IMAGEM_PLACEHOLDER = [
        'USER' => 'imagens/user/placeholder-user.jpg',
        'PET' => 'imagens/pet/placeholder-pet.jpg',
        'BLOG' => 'imagens/blog/placeholder-blog.jpg',
        'LINK' => 'imagens/link/placeholder-link.jpg',
    ];
}