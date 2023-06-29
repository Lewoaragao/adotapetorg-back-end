<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogPostagemTag extends Model
{
    use HasFactory;

    protected $table = 'blog_postagens_tags';
    protected $fillable = [
        'blog_postagens_id',
        'blog_tags_id',
    ];
}