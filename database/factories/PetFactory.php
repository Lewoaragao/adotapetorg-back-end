<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pet>
 */
class PetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $flgAdotado = fake()->boolean();
        $flgNecessidadesEspeciais = fake()->boolean();

        return [
            'user_id' => fake()->numberBetween(1, 10),
            'nome' => fake()->name(),
            'data_nascimento' => fake()->date(),
            'flg_adotado' => $flgAdotado,
            'imagem' => 'imagens/pet/placeholder-pet.jpg',
            'data_adocao' => $flgAdotado ? fake()->date() : null,
            'flg_ativo' => fake()->boolean(),
            'apelido' => fake()->name(),
            'tamanho' => 'P',
            'flg_necessidades_especiais' => $flgNecessidadesEspeciais,
            'necessidades_especiais' => $flgNecessidadesEspeciais ? fake()->text() : null,
            'sexo' => 'F',
        ];
    }
}
