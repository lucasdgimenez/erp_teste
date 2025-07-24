<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Produto;  

class LojaController extends Controller
{
    public function show(Request $request, $slug, $id_produto) {
        $variacoes = DB::table('produtos')
        ->leftJoin('variacoes', 'produtos.id', '=', 'variacoes.produto_id')
        ->select(
            'produtos.id',
            'produtos.nome as nome_produto',
            'produtos.descricao',
            'produtos.slug',
            'produtos.preco_base',
            'variacoes.id as variacao_id',
            'variacoes.nome as nome_variacao',
            'variacoes.preco as preco_variacao',
        )
        ->where('produtos.id', $id_produto)
        ->where('produtos.slug', $slug)
        ->get(); // Use get() para pegar todas as variações

        $produto = Produto::where('id', '=', $id_produto)->first();

        if ($variacoes->isEmpty()) {
            abort(404, 'Produto não encontrado');
        }
    
        return view('produtos.show', ['variacoes' => $variacoes, 'produto' => $produto]);
    }
}
