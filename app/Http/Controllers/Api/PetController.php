<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cor;
use App\Models\Pet;
use App\Models\PetCor;
use App\Models\PetFavorito;
use App\Models\PetTipo;
use App\Models\Raca;
use App\Models\User;
use App\Support\Constants;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;


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
        $pets = Pet::paginate(Constants::REGISTROS_PAGINACAO);
        return Response($pets, Response::HTTP_OK);
    }

    /**
     * Armazene um recurso recém-criado no armazenamento.
     */
    public function store(Request $request)
    {
        $caminhoImagem = "imagens/pet/placeholder-pet.jpg";

        if ($request->hasFile('imagem')) {
            $imagem = $request->file('imagem');
            $nomeImagem = Str::uuid()->toString() . '.' . $imagem->getClientOriginalExtension();
            $imagem->move('api' . '/imagens/pet/', $nomeImagem);
            $caminhoImagem = '/imagens/pet/' . $nomeImagem;
        }

        $user = Auth::user();

        $pet = Pet::create([
            'user_id' => $user->id,
            'pet_tipos_id' => $request->pet_tipos_id,
            'raca_id' => $request->raca_id,
            'imagem' => $caminhoImagem,
            'nome' => $request->nome,
            'data_nascimento' => $request->data_nascimento,
            'apelido' => $request->apelido,
            'tamanho' => $request->tamanho,
            'flg_necessidades_especiais' => $request->flg_necessidades_especiais,
            'necessidades_especiais' => $request->flg_necessidades_especiais ? $request->necessidades_especiais : null,
            'sexo' => $request->sexo,
        ]);

        $cores = $request->cores;
        $cores = Cor::whereIn('cor', $cores)->get();
        $pet->cores()->attach($cores);

        return Response(['message' => 'Pet cadastrado com sucesso'], Response::HTTP_OK);
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

        $cores = $pet->cores()->pluck('cor')->all();

        $userCadastrouPet = User::find($pet->user_id);
        $pet_favoritado = false;

        return Response([
            'pet' => $pet,
            'pet_cores' => $cores,
            'user' => $userCadastrouPet,
            'pet_favoritado' => $pet_favoritado
        ]);
    }

    /**
     * Exiba o recurso especificado autenticado.
     */
    public function showPet(string $id)
    {
        $pet = Pet::find($id);

        if ($pet == null) {
            return Response(['message' => 'Pet não encontrado'], Response::HTTP_NOT_FOUND);
        }

        $cores = $pet->cores()->pluck('cor')->all();

        $userCadastrouPet = User::find($pet->user_id);
        $pet_favoritado = false;
        $userFavoritouPet = Auth::user();

        $is_favorito = DB::table('pets_favoritos')
            ->where('user_id', $userFavoritouPet->id)
            ->where('pet_id', $id)
            ->where('flg_ativo', 1)
            ->get();

        if (!$is_favorito->isEmpty()) {
            $pet_favoritado = true;
        }

        return Response([
            'pet' => $pet,
            'pet_cores' => $cores,
            'user' => $userCadastrouPet,
            'pet_favoritado' => $pet_favoritado
        ]);
    }

    /**
     * Atualize o recurso especificado no armazenamento.
     */
    public function update(Request $request, $id)
    {
        $pet = Pet::find($id);

        if ($pet == null) {
            return Response(['message' => 'Pet não encontrado'], Response::HTTP_NOT_FOUND);
        }

        $caminhoImagem = "imagens/pet/placeholder-pet.jpg";

        if ($request->hasFile('imagem')) {
            $caminhoImagemAntiga = 'api/' . $pet->imagem;

            if (File::exists($caminhoImagemAntiga)) {
                File::delete($caminhoImagemAntiga);
            }

            $imagem = $request->file('imagem');
            $nomeImagem = Str::uuid()->toString() . '.' . $imagem->getClientOriginalExtension();
            $imagem->move('api/imagens/pet/', $nomeImagem);
            $caminhoImagem = '/imagens/pet/' . $nomeImagem;
        }

        $pet->cores()->detach(PetCor::where('pet_id', $pet->id)->pluck('cor_id'));

        if ($request->cores != null) {
            $cores = $request->cores;
            $cores = Cor::whereIn('cor', $cores)->get();
            $pet->cores()->attach($cores);
        }

        $pet->update([
            'nome' => $request->nome,
            'raca_id' => $request->raca_id,
            'data_nascimento' => $request->data_nascimento,
            'flg_adotado' => $request->flg_adotado,
            'imagem' => $caminhoImagem,
            'data_adocao' => $request->data_adocao,
            'flg_ativo' => $request->flg_ativo,
            'apelido' => $request->apelido,
            'tamanho' => $request->tamanho,
            'flg_necessidades_especiais' => $request->flg_necessidades_especiais,
            'necessidades_especiais' => $request->flg_necessidades_especiais ? $request->necessidades_especiais : null,
            'sexo' => $request->sexo,
        ]);

        return Response(['message' => 'Pet atualizado com sucesso'], Response::HTTP_OK);
    }

    /**
     * Remova o recurso especificado do armazenamento.
     */
    public function destroy(string $id)
    {
        $pet = Pet::find($id);
        $pet->cores()->detach(PetCor::where('pet_id', $pet->id)->pluck('cor_id'));

        $caminhoImagemPostagem = 'api/' . $pet->imagem;
        if ($caminhoImagemPostagem !== "api/imagens/pet/placeholder-pet.jpg" && File::exists($caminhoImagemPostagem)) {
            File::delete($caminhoImagemPostagem);
        }

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

                return Response([
                    'message' => 'Pet foi favoritado com sucesso',
                    'pet' => $petFavoritado
                ], Response::HTTP_OK);
            }
        }

        $petFavoritado = PetFavorito::create([
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

                return Response([
                    'message' => 'Pet foi desfavoritado com sucesso',
                    'pet' => $petDesfavoritado
                ], Response::HTTP_OK);
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

        return Response([
            'message' => 'Pet foi desfavoritado com sucesso',
            'pet' => $petDesfavoritado
        ], Response::HTTP_OK);
    }

    public function petsFavoritosUser()
    {
        $user = Auth::user();

        $listIdPetsFavoritados = DB::table('pets_favoritos')
            ->where('user_id', $user->id)
            ->where('flg_ativo', 1)
            ->pluck('pet_id');

        $pets = DB::table('pets')
            ->whereIn('id', $listIdPetsFavoritados)
            ->paginate(Constants::REGISTROS_PAGINACAO);

        return $pets->isEmpty()
            ? Response(['message' => 'Nenhum pet favoritado'], Response::HTTP_NOT_FOUND)
            : Response($pets, Response::HTTP_OK);
    }

    public function petsCadastradosUser()
    {
        $user = Auth::user();

        $pets = Pet::with('cores')
            ->where('user_id', $user->id)
            ->paginate(Constants::REGISTROS_PAGINACAO);

        $tipos = PetTipo::all();
        $cores = Cor::all();

        return $pets->isEmpty()
            ? Response([
                'message' => 'Nenhum pet cadastrado',
                'tipos' => $tipos,
                'cores' => $cores,
            ], Response::HTTP_NOT_FOUND)
            : Response([
                'pets' => $pets,
                'tipos' => $tipos,
                'cores' => $cores,
            ], Response::HTTP_OK);
    }

    public function racasPetTipoId(string $id)
    {
        return Response(Raca::where('pet_tipos_id', $id)->get(), Response::HTTP_OK);
    }
}