<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BlogPostagem;
use App\Models\BlogPostagemTag;
use App\Models\BlogTag;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class BlogPostagemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexPostagens()
    {
        return BlogPostagem::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeTag(Request $request)
    {
        $list_tags_sem_erro = array();
        $list_tags_com_erro = array();

        foreach ($request->tags as $tag) {
            $valida_tag = BlogTag::where('tag', $tag)->first();

            if ($valida_tag != null) {
                array_push($list_tags_com_erro, $valida_tag);
            } else {
                array_push($list_tags_sem_erro, $tag);
                BlogTag::create([
                    'tag' => $tag,
                ]);
            }
        }

        if (empty($list_tags_sem_erro)) {
            return Response(['message' => 'Nenhuma tag cadastrada, pois todas já existem, use as que já estão cadastradas'], Response::HTTP_CONFLICT);
        }

        if (empty($list_tags_com_erro)) {
            return Response(['message' => 'Todas as tags cadastradas com sucesso'], Response::HTTP_OK);
        }

        return Response([
            'message' => 'Tags criadas com sucesso',
            'tags_cadastradas' => [
                'message' => 'A seguir as tags que foram cadastradas',
                'list_tags_sem_erro' => $list_tags_sem_erro
            ],
            'tags_erro' => [
                'message' => 'A seguir as tags que não foram cadastradas por já existirem',
                'list_tags_com_erro' => $list_tags_com_erro
            ]
        ], Response::HTTP_OK);
    }

    public function storePostagem(Request $request)
    {
        $valida_titulo = BlogPostagem::where('titulo', $request->titulo)->first();
        $valida_subtitulo = BlogPostagem::where('subtitulo', $request->subtitulo)->first();

        if ($valida_titulo != null) {
            return Response(['message' => 'Título já cadastrado'], Response::HTTP_CONFLICT);
        }

        if ($valida_subtitulo != null) {
            return Response(['message' => 'Subtítulo já cadastrado'], Response::HTTP_CONFLICT);
        }

        $userAuth = Auth::user();
        $slug = Str::slug($request->titulo);
        $caminhoImagem = "imagens/blog/placeholder-blog.jpg";

        if ($request->hasFile('imagem')) {
            $imagem = $request->file('imagem');
            $nomeImagem = Str::uuid()->toString() . '.' . $imagem->getClientOriginalExtension();
            $imagem->move('api' . '/imagens/blog/', $nomeImagem);
            $caminhoImagem = 'imagens/blog/' . $nomeImagem;
        }

        $postagem = BlogPostagem::create([
            'user_id' => $userAuth->id,
            'titulo' => $request->titulo,
            'subtitulo' => $request->subtitulo,
            'conteudo' => $request->conteudo,
            'slug' => $slug,
            'imagem' => $caminhoImagem,
        ]);

        $idTags = $request->tags;
        $tags = BlogTag::whereIn('id', $idTags)->get();
        $postagem->tags()->attach($tags);

        return Response(['message' => 'Postagem criada com sucesso'], Response::HTTP_OK);
    }

    public function storePostagemTag(Request $request)
    {

        $list_tags_sem_erro = array();
        $list_tags_com_erro = array();

        foreach ($request->blog_tags_id as $tag_id) {
            $valida_tag = BlogPostagemTag::where('blog_tags_id', $tag_id)->first();

            if ($valida_tag != null) {
                array_push($list_tags_com_erro, $tag_id);
            } else {
                array_push($list_tags_sem_erro, $tag_id);
                BlogPostagemTag::create([
                    'blog_postagens_id' => $request->blog_postagens_id,
                    'blog_tags_id' => $tag_id,
                ]);
            }
        }

        if (empty($list_tags_sem_erro)) {
            return Response(['message' => 'Nenhuma tag cadastrada, pois todas já existem na postagem'], Response::HTTP_CONFLICT);
        }

        if (empty($list_tags_com_erro)) {
            return Response(['message' => 'Tags cadastradas para postagem com sucesso'], Response::HTTP_OK);
        }

        $list_tags_sem_erro = BlogTag::whereIn('id', $list_tags_sem_erro)->get();
        $list_tags_com_erro = BlogTag::whereIn('id', $list_tags_com_erro)->get();

        return Response([
            'message' => 'Tags criadas com sucesso',
            'tags_cadastradas' => [
                'message' => 'A seguir as tags que foram cadastradas',
                'list_tags_sem_erro' => $list_tags_sem_erro
            ],
            'tags_erro' => [
                'message' => 'A seguir as tags que não foram cadastradas por já existirem',
                'list_tags_com_erro' => $list_tags_com_erro
            ]
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     */
    public function showPostagemTag(string $slug)
    {
        $postagem = BlogPostagem::where('slug', $slug)->first();

        if($postagem == null) {
            return Response(['message' => 'Postagem não encontrada'], Response::HTTP_NOT_FOUND);
        }

        return Response([
            'postagem' => $postagem,
            'autor' => $postagem->autor()->pluck('primeiro_nome')->first() . ' ' . $postagem->autor()->pluck('sobrenome')->first(),
            'tags' => $postagem->tags()->get()
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updatePostagem(Request $request, string $id)
    {
        $valida_titulo = BlogPostagem::where('titulo', $request->titulo)->first();
        $valida_subtitulo = BlogPostagem::where('subtitulo', $request->subtitulo)->first();

        if ($valida_titulo != null) {
            return Response(['message' => 'Título já cadastrado'], Response::HTTP_CONFLICT);
        }

        if ($valida_subtitulo != null) {
            return Response(['message' => 'Subtítulo já cadastrado'], Response::HTTP_CONFLICT);
        }
        
        $postagem = BlogPostagem::find($id);

        $caminhoImagem = "imagens/blog/placeholder-blog.jpg";

        if ($request->hasFile('imagem')) {
            $caminhoImagemAntiga = 'api/' . $postagem->imagem;

            if (File::exists($caminhoImagemAntiga)) {
                File::delete($caminhoImagemAntiga);
            }

            $imagem = $request->file('imagem');
            $nomeImagem = Str::uuid()->toString() . '.' . $imagem->getClientOriginalExtension();
            $imagem->move('api/imagens/blog/', $nomeImagem);
            $caminhoImagem = '/imagens/blog/' . $nomeImagem;
        }

        $slug = Str::slug($request->titulo);

        $postagem->update([
            'titulo' => $request->titulo,
            'subtitulo' => $request->subtitulo,
            'conteudo' => $request->conteudo,
            'slug' => $slug,
            'imagem' => $caminhoImagem,
        ]);

        $postagem->tags()->detach(BlogPostagemTag::where('blog_postagens_id', $postagem->id)->pluck('blog_tags_id'));
        $idTags = $request->tags;
        $tags = BlogTag::whereIn('id', $idTags)->get();
        $postagem->tags()->attach($tags);

        return Response(['message' => 'Postagem atualizada com sucesso'], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}