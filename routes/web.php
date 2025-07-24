<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\EstoqueController;
use App\Http\Controllers\LojaController;
use App\Http\Controllers\CarrinhoController;

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
Route::get('/{slug}/{id_produto}', [LojaController::class, 'show'])->name('produto.item');

Route::post('/carrinho', [CarrinhoController::class, 'adicionar'])->name('carrinho.adicionar');
Route::delete('/carrinho', [CarrinhoController::class, 'remover'])->name('carrinho.remover');
Route::put('/carrinho', [CarrinhoController::class, 'atualizar'])->name('carrinho.atualizar');
Route::get('/carrinho', [CarrinhoController::class, 'verCarrinho'])->name('carrinho.ver');
Route::post('/carrinho/limpar', [CarrinhoController::class, 'limpar'])->name('carrinho.limpar');

