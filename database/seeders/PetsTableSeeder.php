<?php

namespace Database\Seeders;

use App\Models\Pet;
use Illuminate\Database\Seeder;

class PetsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // CRIA 10 PETS ALEATORIOS
        Pet::factory(30)->create();

        // CRIA 3 PETS PARA O USUARIO COM ID 11
        Pet::factory()->create([
            'user_id' => 11,
            'nome' => fake()->name(),
            'raca' => fake()->name(),
            'data_nascimento' => fake()->date(),
            'flg_adotado' => fake()->boolean(),
            'imagem' => 'imagens/pet/placeholder-pet.jpg'
        ]);

        Pet::factory()->create([
            'user_id' => 11,
            'nome' => fake()->name(),
            'raca' => fake()->name(),
            'data_nascimento' => fake()->date(),
            'flg_adotado' => fake()->boolean(),
            'imagem' => 'imagens/pet/placeholder-pet.jpg'
        ]);

        Pet::factory()->create([
            'user_id' => 11,
            'nome' => fake()->name(),
            'raca' => fake()->name(),
            'data_nascimento' => fake()->date(),
            'flg_adotado' => fake()->boolean(),
            'imagem' => 'imagens/pet/placeholder-pet.jpg'
        ]);
    }
}
