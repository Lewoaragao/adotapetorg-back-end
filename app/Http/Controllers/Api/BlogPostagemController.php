<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BlogPostagem;
use App\Models\BlogPostagemFavorita;
use App\Models\BlogPostagemTag;
use App\Models\BlogTag;
use App\Support\Constants;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class BlogPostagemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexPostagens()
    {
        $postagens = BlogPostagem::paginate(Constants::REGISTROS_PAGINACAO);
        return Response($postagens, Response::HTTP_OK);
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
            return Response(
                [
                    'message' => 'Nenhuma tag cadastrada, pois todas já existem, use as que já estão cadastradas'
                ], Response::HTTP_CONFLICT
            );
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

        if ($valida_titulo != null) {
            return Response(['message' => 'Título já cadastrado'], Response::HTTP_CONFLICT);
        }

        $userAuth = Auth::user();
        $slug = Str::slug($request->titulo);
        $caminhoImagem = Constants::CAMINHO_IMAGEM_PLACEHOLDER['BLOG'];

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

        if ($request->tags !== null && !empty($request->tags)) {
            $tags = $request->tags;
            $tags = BlogTag::whereIn('tag', $tags)->get();
            $postagem->tags()->attach($tags);
        }

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
            return Response(
                [
                    'message' => 'Nenhuma tag cadastrada, pois todas já existem na postagem'
                ], Response::HTTP_CONFLICT
            );
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

        if ($postagem == null) {
            return Response(['message' => 'Postagem não encontrada'], Response::HTTP_NOT_FOUND);
        }

        $postagem_favoritada = false;

        return Response([
            'postagem' => $postagem,
            'autor' => $postagem->autor()->pluck('primeiro_nome')->first() . ' ' . $postagem->autor()->pluck('sobrenome')->first(),
            'tags' => $postagem->tags()->get(),
            'postagem_favoritada' => $postagem_favoritada
        ], Response::HTTP_OK);
    }

    public function showPostagemTagUserAuth(string $slug)
    {
        $postagem = BlogPostagem::where('slug', $slug)->first();

        if ($postagem == null) {
            return Response(['message' => 'Postagem não encontrada'], Response::HTTP_NOT_FOUND);
        }

        $postagem_favoritada = false;
        $userFavoritouPostagem = Auth::user();

        $is_favorito = DB::table('blog_postagens_favoritas')
            ->where('user_id', $userFavoritouPostagem->id)
            ->where('blog_postagem_id', $postagem->id)
            ->where('flg_ativo', 1)
            ->get();

        if (!$is_favorito->isEmpty()) {
            $postagem_favoritada = true;
        }

        $primeiroNomeAutor = $postagem->autor()->pluck('primeiro_nome')->first();
        $sobrenomeAutor = $postagem->autor()->pluck('sobrenome')->first();

        return Response([
            'postagem' => $postagem,
            'autor' => $primeiroNomeAutor . ' ' . $sobrenomeAutor,
            'tags' => $postagem->tags()->get(),
            'postagem_favoritada' => $postagem_favoritada
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updatePostagem(Request $request, string $id)
    {
        $valida_request = BlogPostagem::where('titulo', $request->titulo)->first();

        if ($valida_request != null && $valida_request->id != $id) {
            return Response(['message' => 'Título já cadastrado'], Response::HTTP_CONFLICT);
        }

        $postagem = BlogPostagem::find($id);
        $slug = Str::slug($request->titulo);

        $postagem->update([
            'titulo' => $request->titulo,
            'subtitulo' => $request->subtitulo,
            'conteudo' => $request->conteudo,
            'slug' => $slug,
        ]);

        if ($request->tags != null) {
            $postagem->tags()->detach(BlogPostagemTag::where('blog_postagens_id', $postagem->id)->pluck('blog_tags_id'));
            $tags = $request->tags;
            $tags = BlogTag::whereIn('tag', $tags)->get();
            $postagem->tags()->attach($tags);
        }

        return Response(['message' => 'Postagem atualizada com sucesso'], Response::HTTP_OK);
    }

    public function updateImagemPostagem(Request $request, string $id)
    {

        if ($request->imagem == null) {
            return Response(['message' => 'Necessário envio de imagem'], Response::HTTP_CONFLICT);
        }

        $postagem = BlogPostagem::find($id);

        $caminhoImagem = Constants::CAMINHO_IMAGEM_PLACEHOLDER['BLOG'];

        if ($request->hasFile('imagem')) {
            $caminhoImagemAntiga = 'api/' . $postagem->imagem;
            $caminhoImagemPlaceholder = 'api/' . $caminhoImagem;

            if (File::exists($caminhoImagemAntiga) && $caminhoImagemAntiga != $caminhoImagemPlaceholder) {
                File::delete($caminhoImagemAntiga);
            }

            $imagem = $request->file('imagem');
            $nomeImagem = Str::uuid()->toString() . '.' . $imagem->getClientOriginalExtension();
            $imagem->move('api/imagens/blog/', $nomeImagem);
            $caminhoImagem = '/imagens/blog/' . $nomeImagem;
        }

        $postagem->update([
            'imagem' => $caminhoImagem,
        ]);

        return Response(['message' => 'Imagem atualizada com sucesso'], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyPostagem(string $id)
    {
        $postagem = BlogPostagem::find($id);
        $userAuth = Auth::user();

        if ($postagem == null) {
            return Response(['message' => 'Postagem não encontrada'], Response::HTTP_NOT_FOUND);
        }

        if ($userAuth->user_tipo !== "admin" && $userAuth->id !== $postagem->user_id) {
            return Response(['message' => 'Não é possível alterar a postagem de outro usuário'], Response::HTTP_FORBIDDEN);
        }

        $tags = BlogPostagemTag::where('blog_postagens_id', $id);

        if (!empty($tags)) {
            BlogPostagemTag::where('blog_postagens_id', $id)->delete();
        }

        $favoritada = BlogPostagemFavorita::where('blog_postagem_id', $id);

        if ($favoritada !== null) {
            BlogPostagemFavorita::where('blog_postagem_id', $id)->delete();
        }

        $caminhoImagemPostagem = 'api/' . $postagem->imagem;
        if ($caminhoImagemPostagem !== "api/imagens/blog/placeholder-blog.jpg" && File::exists($caminhoImagemPostagem)) {
            File::delete($caminhoImagemPostagem);
        }

        $postagem->delete();

        return Response(['message' => 'Postagem removida com sucesso'], Response::HTTP_OK);
    }

    public function destroyImagemPostagem(string $id)
    {
        $postagem = BlogPostagem::find($id);
        $userAuth = Auth::user();

        if ($postagem == null) {
            return Response(['message' => 'Postagem não encontrada'], Response::HTTP_NOT_FOUND);
        }

        if ($userAuth->user_tipo !== "admin" && $userAuth->id !== $postagem->user_id) {
            return Response(
                [
                    'message' => 'Não é possível alterar a postagem de outro usuário'
                ], Response::HTTP_FORBIDDEN
            );
        }

        $caminhoImagem = Constants::CAMINHO_IMAGEM_PLACEHOLDER['BLOG'];
        $caminhoImagemPostagem = 'api/' . $postagem->imagem;
        $caminhoImagemPlaceholder = 'api/' . $caminhoImagem;

        if ($caminhoImagemPostagem == $caminhoImagemPlaceholder) {
            return Response(['message' => 'Não é possível apagar a imagem de placeholder'], Response::HTTP_BAD_REQUEST);
        }

        if (File::exists($caminhoImagemPostagem) && $caminhoImagemPostagem !== $caminhoImagemPlaceholder) {
            File::delete($caminhoImagemPostagem);
        }

        $postagem->update([
            'imagem' => $caminhoImagem,
        ]);

        return Response(['message' => 'Imagem removida com sucesso'], Response::HTTP_OK);
    }

    public function postagensCadastradasUser()
    {
        $user = Auth::user();

        $postagens = BlogPostagem::where('user_id', $user->id)
            ->with('tags')
            ->paginate(Constants::REGISTROS_PAGINACAO);

        $tags = BlogTag::all();

        return $postagens->isEmpty()
            ? Response(['message' => 'Nenhuma postagem cadastrada', 'tags' => $tags], Response::HTTP_NOT_FOUND)
            : Response(['postagens' => $postagens, 'tags' => $tags], Response::HTTP_OK);
    }

    public function postagensFavoritasUser()
    {
        $user = Auth::user();

        $listIdPostagensFavoritadas = DB::table('blog_postagens_favoritas')
            ->where('user_id', $user->id)
            ->where('flg_ativo', 1)
            ->pluck('blog_postagem_id');

        $postagens = DB::table('blog_postagens')
            ->whereIn('id', $listIdPostagensFavoritadas)
            ->paginate(Constants::REGISTROS_PAGINACAO);

        return $postagens->isEmpty()
            ? Response(['message' => 'Nenhuma postagem favoritada'], Response::HTTP_NOT_FOUND)
            : Response($postagens, Response::HTTP_OK);
    }

    public function favoritarPostagem(string $id)
    {
        $postagem = BlogPostagem::find($id);
        $user = Auth::user();

        if ($postagem == null) {
            return Response(['message' => 'Postagem não encontrada'], Response::HTTP_NOT_FOUND);
        }

        $listIdPostagensFavoritadas = DB::table('blog_postagens_favoritas')
            ->where('user_id', $user->id)
            ->where('blog_postagem_id', $id)
            ->pluck('blog_postagem_id');

        $postagemFavoritadoIsAtivo = DB::table('blog_postagens_favoritas')
            ->where('user_id', $user->id)
            ->where('blog_postagem_id', $id)
            ->get()
            ->first();

        if ($listIdPostagensFavoritadas->contains($id)) {
            if ($postagemFavoritadoIsAtivo->flg_ativo == 1) {
                return Response(['message' => 'Postagem já favoritada'], Response::HTTP_CONFLICT);
            } else {
                DB::table('blog_postagens_favoritas')
                    ->where('user_id', $user->id)
                    ->where('blog_postagem_id', $id)
                    ->update(['flg_ativo' => 1]);

                $postagemFavoritada = DB::table('blog_postagens_favoritas')
                    ->where('user_id', $user->id)
                    ->where('blog_postagem_id', $id)
                    ->get()
                    ->first();

                return Response([
                    'message' => 'Postagem favoritada com sucesso',
                    'postagem' => $postagemFavoritada
                ], Response::HTTP_OK);
            }
        }

        $postagemFavoritada = BlogPostagemFavorita::create([
            'user_id' => $user->id,
            'blog_postagem_id' => $postagem->id
        ]);

        return Response(
            [
                'message' => 'Postagem favoritada com sucesso',
                'postagem' => $postagemFavoritada
            ], Response::HTTP_OK
        );
    }

    public function desfavoritarPostagem(string $id)
    {
        $postagem = BlogPostagem::find($id);
        $user = Auth::user();

        if ($postagem == null) {
            return Response(['message' => 'Postagem não encontrada'], Response::HTTP_NOT_FOUND);
        }

        $listIdPostagensFavoritadas = DB::table('blog_postagens_favoritas')
            ->where('user_id', $user->id)
            ->where('blog_postagem_id', $id)
            ->pluck('blog_postagem_id');

        $postagemDesfavoritadoIsAtivo = DB::table('blog_postagens_favoritas')
            ->where('user_id', $user->id)
            ->where('blog_postagem_id', $id)
            ->get()
            ->first();

        if ($listIdPostagensFavoritadas->contains($id)) {
            if ($postagemDesfavoritadoIsAtivo->flg_ativo == 0) {
                return Response(['message' => 'Postagem já desfavoritada'], Response::HTTP_CONFLICT);
            } else {
                DB::table('blog_postagens_favoritas')
                    ->where('user_id', $user->id)
                    ->where('blog_postagem_id', $id)
                    ->update(['flg_ativo' => 0]);

                $postagemDesfavoritada = DB::table('blog_postagens_favoritas')
                    ->where('user_id', $user->id)
                    ->where('blog_postagem_id', $id)
                    ->get()
                    ->first();

                return Response(
                    [
                        'message' => 'Postagem desfavoritada com sucesso',
                        'postagem' => $postagemDesfavoritada
                    ], Response::HTTP_OK
                );
            }
        }

        DB::table('blog_postagens_favoritas')
            ->where('user_id', $user->id)
            ->where('blog_postagem_id', $id)
            ->update(['flg_ativo' => 0]);

        $postagemDesfavoritada = DB::table('blog_postagens_favoritas')
            ->where('user_id', $user->id)
            ->where('blog_postagem_id', $id)
            ->get()
            ->first();

        return Response(
            ['message' => 'Postagem desfavoritada com sucesso', 'postagem' => $postagemDesfavoritada], Response::HTTP_OK
        );
    }
}