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
        // CRIA 10 CORES ALEATORIAS
        Cor::factory(10)->create();
    }
}
