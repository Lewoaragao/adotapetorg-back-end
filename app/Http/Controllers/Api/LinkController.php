<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LinkTipo;
use App\Models\User;
use App\Models\UserLink;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class LinkController extends Controller
{
    /**
     * @var UserLink
     */
    private $userLink;

    public function __construct(UserLink $userLink)
    {
        $this->userLink = $userLink;
    }

    /**
     * Armazene um recurso recém-criado no armazenamento.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $caminhoImagem = "";

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
        $userLink = UserLink::find($id);

        if ($userLink == null) {
            return Response(['message' => 'Link não encontrado'], Response::HTTP_NOT_FOUND);
        }

        return Response($userLink);
    }

    public function userLinks(string $nomeUser)
    {
        $user = User::where(['usuario' => $nomeUser])->get()->first();
        $userLinks = $user->links;
        $linkTipos = LinkTipo::all();

        return $userLinks->isEmpty()
            ? Response(['message' => 'Nenhum link cadastrado'], Response::HTTP_NOT_FOUND)
            : Response(['user_links' => $userLinks, 'link_tipos' => $linkTipos], Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {
        $userLink = UserLink::find($id);
        $caminhoImagem = "";

        if ($request->hasFile('imagem')) {
            $caminhoImagemAntiga = 'api/' . $userLink->imagem;

            if (File::exists($caminhoImagemAntiga)) {
                File::delete($caminhoImagemAntiga);
            }

            $imagem = $request->file('imagem');
            $nomeImagem = Str::uuid()->toString() . '.' . $imagem->getClientOriginalExtension();
            $imagem->move('api/imagens/link/', $nomeImagem);
            $caminhoImagem = '/imagens/link/' . $nomeImagem;
        }

        $userLink->update([
            'link_tipo_id' => $request->link_tipo_id,
            'imagem' => $caminhoImagem,
            'titulo_link' => $request->titulo_link,
            'link' => $request->link,
        ]);

        return Response(['message' => 'Link atualizado com sucesso'], Response::HTTP_OK);
    }

    public function destroy(string $id)
    {
        $userLink = UserLink::find($id);
        $userLink->delete();

        return Response(['message' => 'Link foi removido com sucesso'], Response::HTTP_OK);
    }
}
