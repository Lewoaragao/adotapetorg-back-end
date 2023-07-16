<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cor extends Model
{
    use HasFactory;

    protected $table = 'cores';
    protected $fillable = [
        'cor',
        'flg_ativo',
    ];

    public function pets()
    {
        return $this->belongsToMany(Pet::class, 'pet_cores', 'cor_id', 'pet_id');
    }
}
