<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // CRIA 10 USERS ALEATORIOS
        User::factory(10)->create();

        // CRIA UM USER COM DADOS DEFINIDOS
        if (User::where('email', 'lewoaragao@gmail.com')->first() == null) {
            User::factory()->create([
                'usuario' => 'lewoaragao',
                'primeiro_nome' => 'Leonardo',
                'email' => 'lewoaragao@gmail.com',
                'senha' => bcrypt(123),
                'user_tipo' => 'admin',
                'link' => config('constantes.url_base_link_bio') . 'lewoaragao',
                'endereco_cidade' => 'Fortaleza',
                'endereco_estado' => 'Ceará',
                'endereco_pais' => 'Brasil',
            ]);
        }
    }
}
