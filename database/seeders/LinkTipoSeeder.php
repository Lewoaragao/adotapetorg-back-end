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
            'tipo' => 'Externo',
        ]);

        LinkTipo::factory()->create([
            'tipo' => 'Instagram',
        ]);

        LinkTipo::factory()->create([
            'tipo' => 'TikTok',
        ]);

        LinkTipo::factory()->create([
            'tipo' => 'LinkedIn',
        ]);

        LinkTipo::factory()->create([
            'tipo' => 'GitHub',
        ]);

        LinkTipo::factory()->create([
            'tipo' => 'Facebook',
        ]);

        LinkTipo::factory()->create([
            'tipo' => 'YouTube',
        ]);
    }
}
