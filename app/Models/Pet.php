<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use HasFactory;

    protected $table = 'pets';
    protected $fillable = [
        'user_id',
        'nome',
        'pet_tipos_id',
        'raca_id',
        'data_nascimento',
        'flg_adotado',
        'imagem',
        'data_adocao',
        'flg_ativo',
        'apelido',
        'tamanho',
        'flg_necessidades_especiais',
        'necessidades_especiais',
        'sexo',
    ];

    public function cores()
    {
        return $this->belongsToMany(Cor::class, 'pet_cores', 'pet_id', 'cor_id');
    }
}
