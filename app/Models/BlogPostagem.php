<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogPostagem extends Model
{
    use HasFactory;

    protected $table = 'blog_postagens';
    protected $fillable = [
        'user_id',
        'titulo',
        'subtitulo',
        'conteudo',
        'slug',
        'flg_ativo',
        'imagem',
    ];
}