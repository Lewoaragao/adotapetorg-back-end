<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use App\Models\PetsFavoritos;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;


class PetController extends Controller
{
    /**
     * @var Pet
     */
    private $pet;

    public function __construct(Pet $pet)
    {
        $this->pet = $pet;
    }

    /**
     * Exibir uma listagem do recurso.
     */
    public function index()
    {
        $pets = Pet::paginate(10);
        return Response($pets);
    }

    /**
     * Armazene um recurso recém-criado no armazenamento.
     */
    public function store(Request $request)
    {
        $caminhoImagem = "imagens/placeholder-pet.jpg";

        if ($request->hasFile('imagem')) {
            $imagem = $request->file('imagem');
            $nomeImagem = Str::uuid()->toString() . '.' . $imagem->getClientOriginalExtension();
            $imagem->move('api/imagens/', $nomeImagem);
            $caminhoImagem = 'imagens/' . $nomeImagem;
        }

        $pet = Pet::create([
            'user_id' => $request->user_id,
            'nome' => $request->nome,
            'raca' => $request->raca,
            'data_nascimento' => $request->data_nascimento,
            'imagem' => $caminhoImagem,
        ]);

        return Response(['message' => 'Pet cadastrado com sucesso', 'pet' => $pet], Response::HTTP_OK);
    }

    /**
     * Exiba o recurso especificado.
     */
    public function show(string $id)
    {
        $pet = Pet::find($id);

        if ($pet == null) {
            return Response(['message' => 'Pet não encontrado'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($pet);
    }

    /**
     * Atualize o recurso especificado no armazenamento.
     */
    public function update(Request $request)
    {
        $data = $request->all();
        $pet = Pet::find($data['id']);

        if ($request->hasFile('imagem')) {
            $caminhoImagem = 'api/' . $pet->imagem;

            if (File::exists($caminhoImagem)) {
                File::delete($caminhoImagem);
            }

            $imagem = $request->file('imagem');
            $nomeImagem = Str::uuid()->toString() . '.' . $imagem->getClientOriginalExtension();
            $imagem->move('api/imagens/', $nomeImagem);
            $caminhoImagem = 'imagens/' . $nomeImagem;
        }

        $pet->update([
            'nome' => $request->nome,
            'raca' => $request->raca,
            'data_nascimento' => $request->data_nascimento,
            'imagem' => $caminhoImagem
        ]);

        return Response(['message' => 'Pet atualizado com sucesso', 'pet' => $pet], Response::HTTP_OK);
    }

    /**
     * Remova o recurso especificado do armazenamento.
     */
    public function destroy(string $id)
    {
        $pet = Pet::find($id);
        $pet->delete();
        return Response(['message' => 'Pet foi removido com sucesso'], Response::HTTP_OK);
    }

    public function favoritar(string $id)
    {
        $pet = Pet::find($id);
        $user = Auth::user();

        if ($pet == null) {
            return Response(['message' => 'Pet não encontrado'], Response::HTTP_NOT_FOUND);
        }

        $listIdPetsFavoritados = DB::table('pets_favoritos')
            ->where('user_id', $user->id)
            ->where('pet_id', $id)
            ->pluck('pet_id');

        $petFavoritadoIsAtivo = DB::table('pets_favoritos')
            ->where('user_id', $user->id)
            ->where('pet_id', $id)
            ->get()
            ->first();

        if ($listIdPetsFavoritados->contains($id)) {
            if ($petFavoritadoIsAtivo->flg_ativo == 1) {
                return Response(['message' => 'Pet já favoritado'], Response::HTTP_CONFLICT);
            } else {
                DB::table('pets_favoritos')
                    ->where('user_id', $user->id)
                    ->where('pet_id', $id)
                    ->update(['flg_ativo' => 1]);

                $petFavoritado = DB::table('pets_favoritos')
                    ->where('user_id', $user->id)
                    ->where('pet_id', $id)
                    ->get()
                    ->first();

                return Response(['message' => 'Pet foi favoritado com sucesso', 'pet' => $petFavoritado], Response::HTTP_OK);
            }
        }

        $petFavoritado = PetsFavoritos::create([
            'user_id' => $user->id,
            'pet_id' => $pet->id
        ]);

        return Response(['message' => 'Pet foi favoritado com sucesso', 'pet' => $petFavoritado], Response::HTTP_OK);
    }

    public function desfavoritar(string $id)
    {
        $pet = Pet::find($id);
        $user = Auth::user();

        if ($pet == null) {
            return Response(['message' => 'Pet não encontrado'], Response::HTTP_NOT_FOUND);
        }

        $listIdPetsFavoritados = DB::table('pets_favoritos')
            ->where('user_id', $user->id)
            ->where('pet_id', $id)
            ->pluck('pet_id');

        $petDesfavoritadoIsAtivo = DB::table('pets_favoritos')
            ->where('user_id', $user->id)
            ->where('pet_id', $id)
            ->get()
            ->first();

        if ($listIdPetsFavoritados->contains($id)) {
            if ($petDesfavoritadoIsAtivo->flg_ativo == 0) {
                return Response(['message' => 'Pet já desfavoritado'], Response::HTTP_CONFLICT);
            } else {
                DB::table('pets_favoritos')
                    ->where('user_id', $user->id)
                    ->where('pet_id', $id)
                    ->update(['flg_ativo' => 0]);

                $petDesfavoritado = DB::table('pets_favoritos')
                    ->where('user_id', $user->id)
                    ->where('pet_id', $id)
                    ->get()
                    ->first();

                return Response(['message' => 'Pet foi desfavoritado com sucesso', 'pet' => $petDesfavoritado], Response::HTTP_OK);
            }
        }

        DB::table('pets_favoritos')
            ->where('user_id', $user->id)
            ->where('pet_id', $id)
            ->update(['flg_ativo' => 0]);

        $petDesfavoritado = DB::table('pets_favoritos')
            ->where('user_id', $user->id)
            ->where('pet_id', $id)
            ->get()
            ->first();

        return Response(['message' => 'Pet foi desfavoritado com sucesso', 'pet' => $petDesfavoritado], Response::HTTP_OK);
    }

    public function petsFavoritosUser(string $id)
    {
        $user = Auth::user();

        $listIdPetsFavoritados = DB::table('pets_favoritos')
            ->where('user_id', $user->id)
            ->where('flg_ativo', 1)
            ->pluck('pet_id');

        $pets = DB::table('pets')
            ->whereIn('id', $listIdPetsFavoritados)
            ->get();

        return Response($pets);
    }
}
