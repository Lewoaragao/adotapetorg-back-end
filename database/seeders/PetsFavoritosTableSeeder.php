<?php

namespace Database\Seeders;

use App\Models\PetFavorito;
use Illuminate\Database\Seeder;

class PetsFavoritosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // CRIA 10 PETS ALEATORIOS
        PetFavorito::factory(10)->create();

        // CRIA 3 PETS FAVORITOS PARA O USUARIO COM ID 11
        PetFavorito::factory()->create([
            'user_id' => 11,
            'pet_id' => 1,
            'flg_ativo' => 1,
        ]);

        PetFavorito::factory()->create([
            'user_id' => 11,
            'pet_id' => 2,
            'flg_ativo' => 1,
        ]);

        PetFavorito::factory()->create([
            'user_id' => 11,
            'pet_id' => 3,
            'flg_ativo' => 1,
        ]);
    }
}
