<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;

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
        return response()->json($pets);
    }

    /**
     * Armazene um recurso recém-criado no armazenamento.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        // $pet = Pet::create($data);

        $foto = $request->foto->store('fotos', 'public');

        $pet = Pet::create([
            'usuario_id' => $request->usuario_id,
            'nome' => $request->nome,
            'raca' => $request->raca,
            'data_nascimento' => $request->data_nascimento,
            'foto' => $foto,
        ]);

        return Response(['message' => 'Pet cadastrado com sucesso', 'pet' => $pet], Response::HTTP_OK);
    }

    /**
     * Exiba o recurso especificado.
     */
    public function show(string $id)
    {
        $pet = $this->pet->find($id);

        if ($pet == null) {
            return Response(['message' => 'Pet não encontrado'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($pet);
    }

    /**
     * Atualize o recurso especificado no armazenamento.
     */
    public function update(Request $request, string $id)
    {
        $pet = Pet::find($id);

        // if ($request->hasFile('foto')) {
        //     $imagePath = 'storage/' . $pet->foto;

        //     if (File::exists($imagePath)) {
        //         File::delete($imagePath);
        //     }

            $foto = $request->foto->store('fotos', 'public');
        // }

        $pet->update([
            'usuario_id' => $request->usuario_id,
            'nome' => $request->nome,
            'raca' => $request->raca,
            'data_nascimento' => $request->data_nascimento,
            'foto' => $foto,
        ]);

        return Response(['message' => 'Pet atualizado com sucesso', 'pet' => $pet], Response::HTTP_OK);
    }

    /**
     * Remova o recurso especificado do armazenamento.
     */
    public function destroy(string $id)
    {
        $pet = $this->pet->find($id);
        $pet->delete();
        return Response(['message' => 'Pet foi removido com sucesso'], Response::HTTP_OK);
    }
}
