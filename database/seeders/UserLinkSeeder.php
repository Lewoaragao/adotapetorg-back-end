<?php

namespace Database\Seeders;

use App\Models\UserLink;
use Illuminate\Database\Seeder;

class UserLinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // CRIA 10 LINK ALEATORIOS PRO USUARIO COM ID 11
        UserLink::factory(4)->create();
    }
}
