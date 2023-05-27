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
                'nome' => 'Leonardo',
                'sobrenome' => 'Aragão',
                'data_nascimento' => date('1900-01-01'),
                'email' => 'lewoaragao@gmail.com',
                'senha' => bcrypt(123),
                'rua_endereco' => 'Coronel',
                'numero_endereco' => '1',
                'bairro_endereco' => 'PK',
                'estado_endereco' => 'CE',
                'cidade_endereco' => 'Fortaleza',
                'user_tipo' => 'admin',
            ]);
        }
    }
}