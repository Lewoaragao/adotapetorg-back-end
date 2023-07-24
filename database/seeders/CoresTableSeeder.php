<?php

namespace Database\Seeders;

use App\Models\Cor;
use Illuminate\Database\Seeder;

class CoresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cores = ['Caramelo', 'Branco', 'Preto', 'Marrom', 'Cinza'];

        foreach ($cores as $cor) {
            // $validaCor = Cor::where('cor', $cor);
            // if ($validaCor == null) {
            Cor::create([
                'cor' => $cor,
            ]);
            // }
        }
    }
}