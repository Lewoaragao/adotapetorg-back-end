<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetFavorito extends Model
{
    use HasFactory;

    protected $table = 'pets_favoritos';
    protected $fillable = [
        'user_id',
        'pet_id',
        'flg_ativo',
    ];
}
