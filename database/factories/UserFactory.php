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
        $fakeFirstName = fake()->firstName();
        $flgWhatsapp = fake()->boolean();

        return [
            'usuario' => $fakeFirstName,
            'primeiro_nome' => fake()->name(),
            'sobrenome' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'imagem' => 'imagens/user/placeholder-user.jpg',
            'email_verified_at' => now(),
            'senha' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'telefone' => fake()->numerify('#########'),
            'flg_telefone_whatsapp' => $flgWhatsapp ? true : false,
            'celular' => fake()->numerify('###########'),
            'flg_celular_whatsapp' => $flgWhatsapp ? false : true,
            'link' => 'https://adotapet.org/link/' . $fakeFirstName,
            'endereco_cidade' => fake()->city(),
            'endereco_estado' => fake()->state(),
            'endereco_pais' => fake()->country(),
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
