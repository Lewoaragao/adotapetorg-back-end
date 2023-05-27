<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLink extends Model
{
    use HasFactory;

    protected $table = 'user_links';
    protected $fillable = [
        'user_id',
        'link_tipo_id',
        'imagem',
        'titulo_link',
        'link',
        'flg_ativo',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function linkTipo()
    {
        return $this->hasOne(LinkTipo::class);
    }
}
