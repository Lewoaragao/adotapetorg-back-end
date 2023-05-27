<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkTipo extends Model
{
    use HasFactory;

    protected $table = 'link_tipos';
    protected $fillable = [
        'nome',
        'flg_ativo',
    ];
}
