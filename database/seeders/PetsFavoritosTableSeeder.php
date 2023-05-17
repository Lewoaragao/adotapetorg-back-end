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
    }
}
