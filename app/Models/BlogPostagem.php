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

    public function autor()
    {
        return $this->belongsToMany(User::class, 'blog_postagens', 'id', 'user_id');
    }

    public function tags()
    {
        return $this->belongsToMany(BlogTag::class, 'blog_postagens_tags', 'blog_postagens_id', 'blog_tags_id');
    }
}
