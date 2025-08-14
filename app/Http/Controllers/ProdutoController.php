<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Produto;  
use App\Models\Variacao;
use App\Models\Estoque;

class ProdutoController extends Controller
{
    public function index() {
        $produtos = getProdutos();
        return view('produtos.index', compact('produtos'));
    }

    public function show(Request $request) {
        $produtos = getProdutos();
        return view('produtos.index', compact('produtos'));
    }

    public function new() {
        return view('produtos.create');
    }

    public function store(Request $request) {
        $this->salvarProduto($request);
        return redirect()->route('produtos.index')->with('success', 'Produto cadastrado com sucesso!');
    }

    public function edit($id) {
        $produto = Produto::where('id', '=', $id)->first();

        return view('produtos.edit', [
            'produto' => $produto->load('variacoes')
        ]);
    }

    public function update(Request $request, Produto $produto)
    {
        $this->salvarProduto($request, $produto);
        return redirect()->route('produtos.index')->with('success', 'Produto atualizado com sucesso!');
    }

    private function salvarProduto(Request $request, Produto $produto = null) {
        $variacoes = $request->variacoes;

        $produto = $produto ?? new Produto();
        $produto->nome = $request->nome;
        $produto->slug = Str::slug($request->nome);
        $produto->descricao = $request->descricao;
        $produto->preco_base = $request->preco;
        $produto->save();

        foreach ($variacoes as $key => $value) {
            if (!empty($value['id'])) {
                $variacao = Variacao::find($value['id']) ?? new Variacao();
            } else {
                $variacao = new Variacao();
            }
            $variacao->nome = $value["nome"];
            $variacao->preco = $value["preco"];
            $variacao->produto_id = $produto->id;
            $variacao->save();

            $estoque = Estoque::where('variacao_id', $variacao->id)->first();
            if (!$estoque) {
                $estoque = new Estoque();
                $estoque->produto_id = $produto->id;
                $estoque->variacao_id = $variacao->id;
            }
            $estoque->quantidade = $value['estoque'];
            $estoque->save();
        }

        dd("Tudo cadastrado");
    }
}
