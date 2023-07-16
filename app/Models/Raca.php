<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Raca extends Model
{
    use HasFactory;
    protected $table = 'racas';
    protected $fillable = [
        'pet_tipos_id',
        'raca',
    ];
}
