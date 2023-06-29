<?php

namespace Database\Seeders;

use App\Models\BlogPostagem;
use Illuminate\Database\Seeder;

class BlogPostagemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // CRIA 10 POSTAGENS ALEATORIAS
        BlogPostagem::factory(10)->create();

        $postagem = BlogPostagem::factory()->create([
            'user_id' => 11,
            'titulo' => 'O melhor título',
            'subtitulo' => 'O melhor subtítulo',
            'conteudo' => '<p>Adotar um pet pode ser uma das decisões mais gratificantes da sua vida. Além de encontrar um companheiro leal e carinhoso, você também experimentará inúmeros benefícios emocionais e transformações positivas na sua rotina diária. Neste artigo, vamos destacar os 10 motivos pelos quais adotar um pet vai transformar sua vida.</p><p>Amor incondicional: Os pets adotados são conhecidos por oferecerem amor incondicional. Eles serão seus fiéis companheiros, sempre prontos para confortar, brincar e alegrar seus dias. Independentemente do seu estado de espírito, eles estarão lá para lhe dar amor e apoio.</p><p>Companheirismo: Ter um pet em casa significa ter uma companhia constante. Eles são ótimos ouvintes, estão sempre presentes nos momentos bons e ruins, e trazem alegria para seu dia a dia. Você nunca se sentirá sozinho com um pet ao seu lado, pois eles estão sempre dispostos a compartilhar momentos especiais com você.</p><p>Bem-estar emocional: A presença de um pet pode ajudar a reduzir o estresse e a ansiedade. Eles proporcionam conforto emocional e são verdadeiros aliados no combate à solidão e à tristeza. Acariciar um pet e receber seu carinho pode ter um efeito calmante e relaxante, melhorando seu bem-estar emocional e promovendo uma sensação de felicidade.</p><p>Estímulo à atividade física: Cuidar de um pet envolve atividades como passeios, brincadeiras e exercícios. Isso estimula uma vida mais ativa, beneficiando tanto a saúde do pet quanto a sua própria. A oportunidade de se exercitar com seu pet fortalece o vínculo entre vocês, além de contribuir para sua saúde e condicionamento físico.</p><p>Senso de responsabilidade: Adotar um pet é uma grande responsabilidade e uma oportunidade de aprender a cuidar de outro ser vivo. Isso desenvolve o senso de responsabilidade e empatia, além de promover um maior senso de propósito na vida. Ao assumir o compromisso de cuidar de um pet, você aprende a ser responsável por suas necessidades básicas, como alimentação, higiene e saúde.</p>',
            'slug' => 'o-melhor-título',
            'flg_ativo' => 1,
            'imagem' => 'imagens/blog/placeholder-blog.jpg',
        ]);

        $tags = [1, 2, 3];
        $postagem->tags()->attach($tags);
    }
}