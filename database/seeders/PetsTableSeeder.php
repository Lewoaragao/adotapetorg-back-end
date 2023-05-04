<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pet;

class PetsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // CRIA 10 PETS ALEATORIOS
        Pet::factory(10)->create();

        // CRIA UM PET COM DADOS DEFINIDOS
        Pet::factory()->create([
            'usuario_id' => 1,
            'nome' => 'Chandelly Caramelo',
            'raca' => 'test@example.com',
            'adotado' => 0,
            'data_nascimento' => '2022-01-12'
        ]);
    }
}
