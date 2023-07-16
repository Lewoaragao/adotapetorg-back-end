<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetTipo extends Model
{
    use HasFactory;
    protected $table = 'pet_tipos';
    protected $fillable = [
        'tipo',
        'flg_exotico'
    ];
}
