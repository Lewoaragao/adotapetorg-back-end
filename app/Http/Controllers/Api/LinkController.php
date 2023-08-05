<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LinkTipo;
use App\Models\User;
use App\Models\UserLink;
use App\Support\Constants;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class LinkController extends Controller
{
    /**
     * Armazene um recurso recém-criado no armazenamento.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $caminhoImagem = null;

        $userLinks = UserLink::where(['user_id' => $user->id])->get();

        foreach ($userLinks as $userLink) {
            if (
                $request->link_tipo_id != Constants::LINK_TIPO['EXTERNO']
                && $userLink->link_tipo_id == $request->link_tipo_id
            ) {
                return Response(['message' => 'Tipo de link já cadastrado'], Response::HTTP_CONFLICT);
            }
        }

        if ($request->hasFile('imagem')) {
            $imagem = $request->file('imagem');
            $nomeImagem = Str::uuid()->toString() . '.' . $imagem->getClientOriginalExtension();
            $imagem->move('api' . '/imagens/link/', $nomeImagem);
            $caminhoImagem = '/imagens/link/' . $nomeImagem;
        }

        UserLink::create([
            'user_id' => $user->id,
            'link_tipo_id' => $request->link_tipo_id,
            'imagem' => $caminhoImagem,
            'titulo_link' => $request->titulo_link,
            'link' => $request->link,
        ]);

        return Response(['message' => 'Link cadastrado com sucesso'], Response::HTTP_OK);
    }

    public function show(string $id)
    {
        $user = Auth::user();
        $userLink = UserLink::find($id);

        if ($userLink == null) {
            return Response(['message' => 'Link não encontrado'], Response::HTTP_NOT_FOUND);
        }

        if ($userLink->user_id != $user->id) {
            return Response(
                ['message' => 'Não é possível editar o link de outro usuário'], Response::HTTP_BAD_REQUEST
            );
        }

        return Response($userLink);
    }

    public function userLinks(string $nomeUser)
    {
        $user = User::where(['usuario' => $nomeUser])->get()->first();

        if ($user == null) {
            return Response([
                'flg_user_cadastrado' => false,
                'message' => 'Nenhum usuário cadastrado com esse nome'
            ], Response::HTTP_NOT_FOUND);
        }

        $userLinks = $user->links;
        $linkTipos = LinkTipo::all();

        return $userLinks->isEmpty()
            ? Response([
                'flg_user_cadastrado' => true,
                'link_tipos' => $linkTipos,
                'message' => 'Nenhum link cadastrado',
                'user_imagem' => $user->imagem
            ], Response::HTTP_NOT_FOUND)
            : Response([
                'flg_user_cadastrado' => true,
                'user_links' => $userLinks,
                'link_tipos' => $linkTipos,
                'user_imagem' => $user->imagem
            ], Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {
        $userLink = UserLink::find($id);

        if ($userLink == null) {
            return Response(['message' => 'Link não encontrado'], Response::HTTP_NOT_FOUND);
        }

        $userLink->update([
            'link_tipo_id' => $request->link_tipo_id,
            'titulo_link' => $request->titulo_link,
            'link' => $request->link,
        ]);

        return Response(['message' => 'Link atualizado com sucesso'], Response::HTTP_OK);
    }

    public function updateImagemLink(Request $request, string $id)
    {
        if ($request->imagem == null) {
            return Response(['message' => 'Necessário envio de imagem'], Response::HTTP_CONFLICT);
        }

        $link = UserLink::find($id);
        $userAuth = Auth::user();

        if ($link == null) {
            return Response(['message' => 'Link não encontrado'], Response::HTTP_NOT_FOUND);
        }

        if ($userAuth->user_tipo !== "admin" && $userAuth->id !== $link->user_id) {
            return Response(
                [
                    'message' => 'Não é possível alterar o link de outro usuário'
                ], Response::HTTP_UNAUTHORIZED
            );
        }

        $caminhoImagem = Constants::CAMINHO_IMAGEM_PLACEHOLDER['LINK'];

        if ($request->hasFile('imagem')) {
            $caminhoImagemAntiga = 'api/' . $link->imagem;
            $caminhoImagemPlaceholder = 'api/' . $caminhoImagem;

            if (File::exists($caminhoImagemAntiga) && $caminhoImagemAntiga != $caminhoImagemPlaceholder) {
                File::delete($caminhoImagemAntiga);
            }

            $imagem = $request->file('imagem');
            $nomeImagem = Str::uuid()->toString() . '.' . $imagem->getClientOriginalExtension();
            $imagem->move('api/imagens/link/', $nomeImagem);
            $caminhoImagem = '/imagens/link/' . $nomeImagem;
        }

        $link->update([
            'imagem' => $caminhoImagem,
        ]);

        return Response(['message' => 'Imagem atualizada com sucesso'], Response::HTTP_OK);
    }


    public function destroy(string $id)
    {
        $userLink = UserLink::find($id);

        $caminhoImagem = Constants::CAMINHO_IMAGEM_PLACEHOLDER['LINK'];
        $caminhoImagemAntiga = 'api/' . $userLink->imagem;
        $caminhoImagemPlaceholder = 'api/' . $caminhoImagem;
        if ($caminhoImagemAntiga !== $caminhoImagemPlaceholder && File::exists($caminhoImagemAntiga)) {
            File::delete($caminhoImagemAntiga);
        }

        $userLink->delete();

        return Response(['message' => 'Link foi removido com sucesso'], Response::HTTP_OK);
    }

    public function destroyImagemLink(string $id)
    {
        $link = UserLink::find($id);
        $userAuth = Auth::user();

        if ($link === null) {
            return Response(['message' => 'Link não encontrado'], Response::HTTP_NOT_FOUND);
        }

        if ($link->imagem === "") {
            return Response(['message' => 'Não há imagem para ser removida'], Response::HTTP_NOT_FOUND);
        }

        if ($userAuth->user_tipo !== "admin" && $userAuth->id !== $link->user_id) {
            return Response(
                [
                    'message' => 'Não é possível alterar o link de outro usuário'
                ], Response::HTTP_UNAUTHORIZED
            );
        }

        $caminhoImagem = Constants::CAMINHO_IMAGEM_PLACEHOLDER['LINK'];
        $caminhoImagemLink = 'api/' . $link->imagem;
        $caminhoImagemPlaceholder = 'api/' . $caminhoImagem;

        if ($caminhoImagemLink == $caminhoImagemPlaceholder) {
            return Response(['message' => 'Não é possível apagar a imagem padrão'], Response::HTTP_BAD_REQUEST);
        }

        if (File::exists($caminhoImagemLink) && $caminhoImagemLink !== $caminhoImagemPlaceholder) {
            File::delete($caminhoImagemLink);
        }

        $link->update([
            'imagem' => null,
        ]);

        return Response(['message' => 'Imagem removida com sucesso'], Response::HTTP_OK);
    }
}