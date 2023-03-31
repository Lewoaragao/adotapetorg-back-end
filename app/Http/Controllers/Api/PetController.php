<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use Illuminate\Http\Request;

class PetController extends Controller
{
    /**
     * @var Pet
     */
    private $pet;

    public function __construct(Pet $pet) {
        $this->pet = $pet;
    }

    /**
     * Exibir uma listagem do recurso.
     */
    public function index()
    {
        $pets = $this->pet->all();
        return response()->json($pets);
    }

    /**
     * Armazene um recurso recém-criado no armazenamento.
     */
    public function store(Request $request)
    {
        // $data = $request->all();
        // $pet = $this->pet->create($data);

        // $pet = Pet::create([
        //     'usuario_id' => $request->usuario_id,
        //     'nome' => $request->nome,
        //     'raca' => $request->raca,
        //     'data_nascimento' => $request->data_nascimento,
        //     'adotado' => $request->adotado,
        // ]);

        // return response()->json($pet);
        return response()->json($request);
    }

    /**
     * Exiba o recurso especificado.
     */
    public function show(string $id)
    {
        $pet = $this->pet->find($id);

        if($pet == null) {
            return response()->json(
                ['data' => [
                    'msg' => 'Pet não encontrado!'
                ]]
            );
        }

        return response()->json($pet);
    }

    /**
     * Atualize o recurso especificado no armazenamento.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remova o recurso especificado do armazenamento.
     */
    public function destroy(string $id)
    {
        $pet = $this->pet->find($id);
        $pet->delete();
        return response()->json(
            ['data' => [
                'msg' => 'Pet foi removido com sucesso!'
            ]]
        );
    }
}
