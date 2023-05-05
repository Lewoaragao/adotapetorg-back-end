<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome' => fake()->name(),
            'sobrenome' => fake()->name(),
            'data_nascimento' => fake()->date(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'senha' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'rua_endereco' => fake()->streetName(),
            'numero_endereco' => fake()->buildingNumber(),
            'bairro_endereco' => fake()->streetAddress(),
            'estado_endereco' => fake()->country(),
            'cidade_endereco' => fake()->city(),
            'cpf' => fake()->numerify('###-###-####'),
            'telefone' => fake()->numerify('###-###-####'),
            'user_tipo' => "user",
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
