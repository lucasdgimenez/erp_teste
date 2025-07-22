<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\EstoqueController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/produto', [ProdutoController::class, 'new'])->name('produtos.novo');
Route::get('/listar-produtos', [ProdutoController::class, 'index'])->name('produtos.listar');
Route::post('/produto', [ProdutoController::class, 'store'])->name('produtos.create');
Route::get('/produto/{produto}', [ProdutoController::class, 'edit'])->name('produtos.editar');
Route::put('/produto', [ProdutoController::class, 'update'])->name('produtos.update');
Route::delete('/produtos/{produto}', [ProdutoController::class, 'destroy'])->name('produtos.destroy');

Route::get('/estoque', [EstoqueController::class, 'index'])->name('estoque.listar');
Route::get('/estoque/{id}', [EstoqueController::class, 'edit'])->name('estoque.editar');
Route::put('/estoque', [EstoqueController::class, 'update'])->name('estoque.update');

