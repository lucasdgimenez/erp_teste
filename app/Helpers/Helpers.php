<?php

use App\Models\Produto;

if (!function_exists('getProdutos')) {
    function getProdutos()
    {
        $produtos = Produto::orderBy('created_at', 'desc')->get();

        return $produtos;
    }
}
