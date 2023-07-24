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
        $flgAdotado = fake()->boolean();
        $flgNecessidadesEspeciais = fake()->boolean();

        // CRIA 10 PETS ALEATORIOS
        Pet::factory(30)->create();

        // CRIA 3 PETS PARA O USUARIO COM ID 11 lewoaragao@gmail.com
        Pet::factory()->create([
            'user_id' => 11,
            'nome' => fake()->name(),
            'raca_id' => 2,
            'data_nascimento' => fake()->date(),
            'flg_adotado' => $flgAdotado,
            'imagem' => 'imagens/pet/placeholder-pet.jpg',
            'data_adocao' => $flgAdotado ? fake()->date() : null,
            'flg_ativo' => fake()->boolean(),
            'apelido' => fake()->name(),
            'tamanho' => 'M',
            'flg_necessidades_especiais' => $flgNecessidadesEspeciais,
            'necessidades_especiais' => $flgNecessidadesEspeciais ? fake()->text() : null,
            'sexo' => 'M',
        ]);

        Pet::factory()->create([
            'user_id' => 11,
            'nome' => fake()->name(),
            'raca_id' => 3,
            'data_nascimento' => fake()->date(),
            'flg_adotado' => $flgAdotado,
            'imagem' => 'imagens/pet/placeholder-pet.jpg',
            'data_adocao' => $flgAdotado ? fake()->date() : null,
            'flg_ativo' => fake()->boolean(),
            'apelido' => fake()->name(),
            'tamanho' => 'G',
            'flg_necessidades_especiais' => $flgNecessidadesEspeciais,
            'necessidades_especiais' => $flgNecessidadesEspeciais ? fake()->text() : null,
            'sexo' => 'F',
        ]);

        Pet::factory()->create([
            'user_id' => 11,
            'nome' => fake()->name(),
            'raca_id' => 4,
            'data_nascimento' => fake()->date(),
            'flg_adotado' => $flgAdotado,
            'imagem' => 'imagens/pet/placeholder-pet.jpg',
            'data_adocao' => $flgAdotado ? fake()->date() : null,
            'flg_ativo' => fake()->boolean(),
            'apelido' => fake()->name(),
            'tamanho' => 'M',
            'flg_necessidades_especiais' => $flgNecessidadesEspeciais,
            'necessidades_especiais' => $flgNecessidadesEspeciais ? fake()->text() : null,
            'sexo' => 'M',
        ]);
    }
}
