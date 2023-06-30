<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogPostagemFavorita extends Model
{
    use HasFactory;

    protected $table = 'blog_postagens_favoritas';
    protected $fillable = [
        'user_id',
        'blog_postagem_id',
        'flg_ativo',
    ];
}