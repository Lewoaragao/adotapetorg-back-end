<?php

namespace Database\Seeders;

use App\Models\BlogTag;
use Illuminate\Database\Seeder;

class BlogTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = ['Pet', 'Amor', 'Adoção', 'Cachorro', 'Gato', 'Cuidados'];

        foreach ($tags as $tag) {
            // $validaTag = BlogTag::where('tag', $tag);
            // if ($validaTag == null) {
            BlogTag::create([
                'tag' => $tag
            ]);
            // }
        }
    }
}