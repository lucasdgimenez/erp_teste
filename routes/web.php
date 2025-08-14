<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\EstoqueController;
use App\Http\Controllers\LojaController;
use App\Http\Controllers\CarrinhoController;
use App\Http\Controllers\CupomController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/produto', [ProdutoController::class, 'new'])->name('produtos.novo');
Route::get('/listar-produtos', [ProdutoController::class, 'index'])->name('produtos.listar');
Route::post('/produto', [ProdutoController::class, 'store'])->name('produtos.create');
Route::get('/produto/{produto}', [ProdutoController::class, 'edit'])->name('produtos.edit');
Route::put('/produto', [ProdutoController::class, 'update'])->name('produtos.update');
Route::delete('/produtos/{produto}', [ProdutoController::class, 'destroy'])->name('produtos.destroy');

Route::get('/estoque', [EstoqueController::class, 'index'])->name('estoque.listar');
Route::get('/estoque/{id}', [EstoqueController::class, 'edit'])->name('estoque.editar');
Route::put('/estoque', [EstoqueController::class, 'update'])->name('estoque.update');

Route::get('/loja', [LojaController::class, 'index'])->name('produtos.lista');

Route::prefix('carrinho')->name('carrinho.')->group(function () {
    Route::post('/adicionar', [CarrinhoController::class, 'adicionar'])->name('adicionar');
    Route::post('/atualizar', [CarrinhoController::class, 'atualizar'])->name('atualizar');
    Route::post('/remover', [CarrinhoController::class, 'remover'])->name('remover');
    Route::post('/limpar', [CarrinhoController::class, 'limpar'])->name('limpar');
    Route::get('/obter', [CarrinhoController::class, 'obterCarrinho'])->name('obter');
    Route::get('/ver', [CarrinhoController::class, 'verCarrinho'])->name('verCarrinho');
    Route::get('/', [CarrinhoController::class, 'index'])->name('listar');
});

Route::get('/cupom', [CupomController::class, 'new'])->name('cupons.novo');
Route::get('/cupons', [CupomController::class, 'index'])->name('cupons.listar');
Route::post('/cupom', [CupomController::class, 'store'])->name('cupons.create');

Route::get('/{slug}/{id_produto}', [LojaController::class, 'show'])->name('produto.item');
