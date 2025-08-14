<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Produto;  
use Illuminate\Support\Facades\Log;

class CarrinhoController extends Controller
{
    public function index(Request $request) {
        return view('carrinho.index');
    }

    public function adicionar(Request $request) {
        $produtoId = $request->input('produto_id');
        $variacaoId = $request->input('variacao_id');
        $quantidade = (int) $request->input('quantidade', 1);

        // Carregar produto com variações e estoque
        $produto = Produto::with(['variacoes.estoque'])->findOrFail($produtoId);
        $variacao = $produto->variacoes()->where('id', $variacaoId)->with('estoque')->firstOrFail();

        // Verificar estoque - usar o relacionamento correto
        $estoqueAtual = $variacao->estoque ? $variacao->estoque->quantidade : 0;
        
        if ($quantidade > $estoqueAtual) {
            return response()->json([
                'erro' => "Estoque insuficiente. Disponível: {$estoqueAtual} unidades."
            ], 422);
        }

        $carrinho = session()->get('carrinho', []);
        $chave = $produtoId . '_' . $variacaoId;

        if (isset($carrinho[$chave])) {
            $novaQuantidade = $carrinho[$chave]['quantidade'] + $quantidade;
            if ($novaQuantidade > $estoqueAtual) {
                return response()->json([
                    'erro' => "Estoque insuficiente. Disponível: {$estoqueAtual} unidades."
                ], 422);
            }
            $carrinho[$chave]['quantidade'] = $novaQuantidade;
        } else {
            $carrinho[$chave] = [
                'produto_id' => $produtoId,
                'nome' => $produto->nome,
                'variacao_id' => $variacaoId,
                'variacao_nome' => $variacao->nome,
                'quantidade' => $quantidade,
                'preco' => $variacao->preco,
                'estoque_disponivel' => $estoqueAtual
            ];
        }

        session()->put('carrinho', $carrinho);

        return response()->json([
            'mensagem' => 'Produto adicionado ao carrinho com sucesso!',
            'carrinho' => $this->formatarCarrinhoParaModal()
        ]);
    }

    public function atualizar(Request $request) {
        $produtoId = $request->input('produto_id');
        $variacaoId = $request->input('variacao_id');
        $novaQuantidade = (int) $request->input('quantidade');

        $produto = Produto::findOrFail($produtoId);
        $variacao = $produto->variacoes()->where('id', $variacaoId)->with('estoque')->firstOrFail();

        $estoqueAtual = $variacao->estoque ? $variacao->estoque->quantidade : 0;

        if ($novaQuantidade > $estoqueAtual) {
            return response()->json(['erro' => 'Estoque insuficiente.'], 422);
        }

        $carrinho = session()->get('carrinho', []);
        $chave = $produtoId . '_' . $variacaoId;

        if (isset($carrinho[$chave])) {
            $carrinho[$chave]['quantidade'] = $novaQuantidade;
            session()->put('carrinho', $carrinho);

            return response()->json([
                'mensagem' => 'Quantidade atualizada.',
                'carrinho' => $this->formatarCarrinhoParaModal()
            ]);
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

        return response()->json(['mensagem' => 'Produto removido do carrinho.', 
        'carrinho' => $this->formatarCarrinhoParaModal()]);
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

        return view('carrinho.ver', compact('carrinho', 'subtotal', 'frete', 'total'));
    }

    public function obterCarrinho() {
        return response()->json([
            'carrinho' => $this->formatarCarrinhoParaModal(),
            'mensagem' => 'ok'
        ]);
    }

    private function formatarCarrinhoParaModal() {
        $carrinho = session('carrinho', []);
        $total = 0;
        $itens = [];

        foreach ($carrinho as $chave => $item) {
            $subtotal = $item['quantidade'] * $item['preco'];
            $total += $subtotal;
            
            $itens[] = [
                'chave' => $chave,
                'produto_id' => $item['produto_id'],
                'nome' => $item['nome'],
                'variacao_id' => $item['variacao_id'],
                'variacao_nome' => $item['variacao_nome'],
                'quantidade' => $item['quantidade'],
                'preco' => $item['preco'],
                'subtotal' => $subtotal
            ];
        }

        $frete = $this->calcularFrete($subtotal);
        $totalFinal = $subtotal + $frete;

        return [
            'itens' => $itens,
            'subtotal' => $subtotal,           // Subtotal dos produtos
            'frete' => $frete,                 // Valor do frete
            'total' => $totalFinal,            // Total final (subtotal + frete)
            'frete_gratis' => $frete === 0.0,  // Flag para indicar se frete é grátis
            'frete_formatado' => $frete === 0.0 ? 'Grátis' : 'R$ ' . number_format($frete, 2, ',', '.')
        ];
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
