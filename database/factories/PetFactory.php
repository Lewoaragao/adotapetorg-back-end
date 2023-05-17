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
        return [
            'user_id' => fake()->numberBetween(1, 3),
            'nome' => fake()->name(),
            'raca' => fake()->name(),
            'data_nascimento' => fake()->date(),
            'adotado' => fake()->boolean(),
            'imagem' => 'imagens/placeholder-pet.jpg'
        ];
    }
}
