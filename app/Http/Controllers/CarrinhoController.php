<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Produto;  

class CarrinhoController extends Controller
{
    public function adicionar(Request $request) {

        $produtoId = $request->input('produto_id');
        $variacaoId = $request->input('variacao_id');
        $quantidade = (int) $request->input('quantidade', 1);

        $produto = Produto::findOrFail($produtoId);
        $variacao = $produto->variacoes()->where('id', $variacaoId)->firstOrFail();

        if ($quantidade > $variacao->estoque) {
            return response()->json(['erro' => 'Estoque insuficiente.'], 422);
        }

        $carrinho = session()->get('carrinho', []);

        $chave = $produtoId . '_' . $variacaoId;


        if (isset($carrinho[$chave])) {
            $novaQuantidade = $carrinho[$chave]['quantidade'] + $quantidade;
            if ($novaQuantidade > $variacao->estoque) {
                return response()->json(['erro' => 'Estoque insuficiente.'], 422);
            }
            $carrinho[$chave]['quantidade'] = $novaQuantidade;
        } else {
            $carrinho[$chave] = [
                'produto_id' => $produtoId,
                'nome' => $produto->nome,
                'variacao_id' => $variacaoId,
                'variacao_nome' => $variacao->nome,
                'quantidade' => $quantidade,
                'preco' => $variacao->preco_variacao,
            ];
        }

        session()->put('carrinho', $carrinho);

        return response()->json(['mensagem' => 'Produto adicionado ao carrinho com sucesso!']);
    }

    public function atualizar(Request $request) {
        $produtoId = $request->input('produto_id');
        $variacaoId = $request->input('variacao_id');
        $novaQuantidade = (int) $request->input('quantidade');

        $produto = Produto::findOrFail($produtoId);
        $variacao = $produto->variacoes()->where('id', $variacaoId)->firstOrFail();

        if ($novaQuantidade > $variacao->estoque) {
            return response()->json(['erro' => 'Estoque insuficiente.'], 422);
        }

        $carrinho = session()->get('carrinho', []);
        $chave = $produtoId . '_' . $variacaoId;

        if (isset($carrinho[$chave])) {
            $carrinho[$chave]['quantidade'] = $novaQuantidade;
            session()->put('carrinho', $carrinho);
            return response()->json(['mensagem' => 'Quantidade atualizada.']);
        }

        return response()->json(['erro' => 'Produto não encontrado no carrinho.'], 404);
    }

    public function remover(Request $request) {
        $produtoId = $request->input('produto_id');
        $variacaoId = $request->input('variacao_id');
        $chave = $produtoId . '_' . $variacaoId;

        $carrinho = session()->get('carrinho', []);

        if (isset($carrinho[$chave])) {
            unset($carrinho[$chave]);
            session()->put('carrinho', $carrinho);
        }

        return response()->json(['mensagem' => 'Produto removido do carrinho.']);
    }

    public function limpar() {
        session()->forget('carrinho');
        return response()->json(['mensagem' => 'Carrinho limpo.']);
    }

    public function verCarrinho() {
        $carrinho = session('carrinho', []);
        $subtotal = 0;

        foreach ($carrinho as $item) {
            $subtotal += $item['quantidade'] * $item['preco'];
        }

        $frete = $this->calcularFrete($subtotal);
        $total = $subtotal + $frete;

        return view('carrinho.index', compact('carrinho', 'subtotal', 'frete', 'total'));
    }


    private function calcularFrete(float $subtotal): float
    {
        if ($subtotal > 200.00) {
            return 0.00; // Frete grátis
        } elseif ($subtotal >= 52.00 && $subtotal <= 166.59) {
            return 15.00;
        } else {
            return 20.00;
        }
    }

}
