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
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt(123),
        ]);
    }
}
