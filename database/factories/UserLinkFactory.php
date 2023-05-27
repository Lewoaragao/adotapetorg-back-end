<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserLink>
 */
class UserLinkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => 11,
            'link_tipo_id' => fake()->numberBetween(1, 7),
            'imagem' => 'imagens/link/placeholder-link.jpg',
            'titulo_link' => fake()->name(),
            'link' => 'https://adotapet.org',
        ];
    }
}
