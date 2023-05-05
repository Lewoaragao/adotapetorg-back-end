<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetsFavoritos extends Model
{
    use HasFactory;

        protected $fillable = [
        'user_id',
        'pet_id',
        'flg_ativo',
    ];
}
