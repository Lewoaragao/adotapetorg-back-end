<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

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
        if(!User::where('email', '=', 'test@example.com')) {
            User::factory()->create([
                'nome' => 'Test User',
                'sobrenome' => 'Sobrenome',
                'email' => 'test@example.com',
                'senha' => bcrypt(123),
            ]);
        }

    }
}
