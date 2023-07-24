<?php

namespace Database\Seeders;

use App\Models\PetTipo;
use Illuminate\Database\Seeder;

class PetsTiposSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $animais = '[
            { "animal": "Cachorro", "exotico": 0 },
            { "animal": "Gato", "exotico": 0 },
            { "animal": "Coelho", "exotico": 0 },
            { "animal": "Hamster", "exotico": 0 },
            { "animal": "Cavalo", "exotico": 0 },
            { "animal": "Pássaro", "exotico": 0 },
            { "animal": "Peixe", "exotico": 0 },
            { "animal": "Tartaruga", "exotico": 0 },
            { "animal": "Cobra", "exotico": 1 },
            { "animal": "Rato", "exotico": 0 },
            { "animal": "Porco-da-índia", "exotico": 0 },
            { "animal": "Furão", "exotico": 1 },
            { "animal": "Periquito", "exotico": 0 },
            { "animal": "Gerbil", "exotico": 0 },
            { "animal": "Iguana", "exotico": 1 },
            { "animal": "Coelho Anão", "exotico": 0 },
            { "animal": "Chinchila", "exotico": 0 },
            { "animal": "Tarântula", "exotico": 1 },
            { "animal": "Rato do Deserto", "exotico": 1 },
            { "animal": "Salamandra", "exotico": 0 }
        ]';

        $animais = json_decode($animais, true);

        foreach ($animais as $animal) {
            PetTipo::create([
                'tipo' => $animal['animal'],
                'flg_exotico' => $animal['exotico']
            ]);
        }
    }
}
