<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetCor extends Model
{
    use HasFactory;

    protected $table = 'pet_cores';
    protected $fillable = [
        'pet_id',
        'cor_id',
    ];

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }

    public function cor()
    {
        return $this->belongsTo(Cor::class);
    }
}