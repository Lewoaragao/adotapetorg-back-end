<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    /**
     * @var product
     */
    private $product;

    public function __construct(Product $product) {
        $this->product = $product;
    }

    public function index(){
        // $products = $this->product->all();
        // COM PAGINAÇÃO
        $products = $this->product->paginate(1);
        return response()->json($products);
    }

    public function show($id){
        $product = $this->product->find($id);
        return response()->json($product);
    }

    public function save(Request $request){
        // teste de campos recebidos
        // return response()->json($request->all());
        $data = $request->all();
        // $product = $this->product->create($data);
        $this->product->create($data);
        // return response()->json($product);
        return response()->json(
            ['data' => [
                'msg' => 'Produto foi gravado com sucesso!'
            ]]
        );
    }

    public function update(Request $request){
        $data = $request->all();
        $product = $this->product->find($data['id']);
        $product->update($data);
        return response()->json($product);
    }

    public function delete($id){
        $product = $this->product->find($id);
        $product->delete();
        return response()->json(
            ['data' => [
                'msg' => 'Produto foi removido com sucesso!'
            ]]
        );
    }
}
