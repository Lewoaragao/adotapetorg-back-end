<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'users';
    protected $fillable = [
        'usuario',
        'is_pessoa',
        'primeiro_nome',
        'nome_organizacao',
        'sigla_organizacao',
        'sobrenome',
        'email',
        'senha',
        'flg_ativo',
        'imagem',
        'telefone',
        'flg_telefone_whatsapp',
        'celular',
        'flg_celular_whatsapp',
        'link',
        'id_pais',
        'endereco_pais',
        'id_estado',
        'endereco_estado',
        'id_cidade',
        'endereco_cidade',
        'google_id',
        'facebook_id',
        'github_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'senha',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function links()
    {
        return $this->hasMany(UserLink::class);
    }
}