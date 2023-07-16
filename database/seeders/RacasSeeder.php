<?php

namespace Database\Seeders;

use App\Models\Raca;
use Illuminate\Database\Seeder;

class RacasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $racas = '[
            {"tipo": 1, "raca":"Sem Raça Definida"},
            {"tipo": 1, "raca":"Labrador Retriever"},
            {"tipo": 1, "raca":"Golden Retriever"},
            {"tipo": 1, "raca":"Bulldog Francês"},
            {"tipo": 1, "raca":"Poodle"},
            {"tipo": 1, "raca":"Beagle"},
            {"tipo": 1, "raca":"Boxer"},
            {"tipo": 1, "raca":"Pastor Alemão"},
            {"tipo": 1, "raca":"Yorkshire Terrier"},
            {"tipo": 1, "raca":"Dachshund"},
            {"tipo": 1, "raca":"Chihuahua"},
            {"tipo": 1, "raca":"Shih Tzu"},
            {"tipo": 1, "raca":"Rottweiler"},
            {"tipo": 1, "raca":"Border Collie"},
            {"tipo": 1, "raca":"Bichon Frisé"},
            {"tipo": 1, "raca":"Pug"},
            {"tipo": 1, "raca":"Schnauzer"},
            {"tipo": 1, "raca":"Husky Siberiano"},
            {"tipo": 1, "raca":"Dalmatian"},
            {"tipo": 1, "raca":"Doberman"},
            {"tipo": 1, "raca":"Maltese"},
            {"tipo": 2, "raca":"Sem Raça Definida"},
            {"tipo": 2, "raca":"Siamês"},
            {"tipo": 2, "raca":"Persa"},
            {"tipo": 2, "raca":"Bengal"},
            {"tipo": 2, "raca":"Maine Coon"},
            {"tipo": 2, "raca":"Sphynx"},
            {"tipo": 2, "raca":"Ragdoll"},
            {"tipo": 2, "raca":"British Shorthair"},
            {"tipo": 2, "raca":"Scottish Fold"},
            {"tipo": 2, "raca":"Burmese"},
            {"tipo": 2, "raca":"Abissínio"},
            {"tipo": 2, "raca":"Siberiano"},
            {"tipo": 2, "raca":"Norueguês da Floresta"},
            {"tipo": 2, "raca":"Birmanês"},
            {"tipo": 2, "raca":"Burmês"},
            {"tipo": 2, "raca":"Rex"},
            {"tipo": 2, "raca":"Angorá"},
            {"tipo": 2, "raca":"Himalaio"},
            {"tipo": 2, "raca":"Tonquinês"},
            {"tipo": 2, "raca":"Exótico"},
            {"tipo": 3, "raca":"Sem Raça Definida"},
            {"tipo": 3, "raca":"Coelho Angorá"},
            {"tipo": 3, "raca":"Coelho Rex"},
            {"tipo": 3, "raca":"Coelho Holandês"},
            {"tipo": 3, "raca":"Coelho Mini Rex"},
            {"tipo": 3, "raca":"Coelho Lion Head"},
            {"tipo": 3, "raca":"Coelho Mini Lop"},
            {"tipo": 3, "raca":"Coelho Dutch"},
            {"tipo": 3, "raca":"Coelho Mini Lion Head"},
            {"tipo": 3, "raca":"Coelho Hotot"},
            {"tipo": 3, "raca":"Coelho Himalaio"},
            {"tipo": 3, "raca":"Coelho Californiano"},
            {"tipo": 3, "raca":"Coelho Polonês"},
            {"tipo": 3, "raca":"Coelho Flemish Giant"},
            {"tipo": 3, "raca":"Coelho Mini Plush Lop"},
            {"tipo": 3, "raca":"Coelho Teddy Dwerg"},
            {"tipo": 3, "raca":"Coelho Belier"},
            {"tipo": 3, "raca":"Coelho Cashmere Lop"},
            {"tipo": 3, "raca":"Coelho Harlequin"},
            {"tipo": 3, "raca":"Coelho Silver Fox"}
        ]';

        $racas = json_decode($racas, true);

        foreach ($racas as $raca) {
            Raca::create([
                'pet_tipos_id' => $raca['tipo'],
                'raca' => $raca['raca']
            ]);
        }
    }
}
