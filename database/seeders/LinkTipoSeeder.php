<?php

namespace Database\Seeders;

use App\Models\LinkTipo;
use Illuminate\Database\Seeder;

class LinkTipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LinkTipo::factory()->create([
            'nome' => 'Externo',
        ]);

        LinkTipo::factory()->create([
            'nome' => 'Instagram',
        ]);

        LinkTipo::factory()->create([
            'nome' => 'TikTok',
        ]);

        LinkTipo::factory()->create([
            'nome' => 'LinkedIn',
        ]);

        LinkTipo::factory()->create([
            'nome' => 'GitHub',
        ]);

        LinkTipo::factory()->create([
            'nome' => 'Facebook',
        ]);

        LinkTipo::factory()->create([
            'nome' => 'YouTube',
        ]);
    }
}
