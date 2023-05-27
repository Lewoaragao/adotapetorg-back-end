<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UsersTableSeeder::class,
            PetsTableSeeder::class,
            PetsFavoritosTableSeeder::class,
            CoresTableSeeder::class,
            LinkTipoSeeder::class,
            UserLinkSeeder::class,
        ]);
    }
}
