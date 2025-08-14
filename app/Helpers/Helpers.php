<?php

use App\Models\Produto;
use App\Models\Cupom;

if (!function_exists('getProdutos')) {
    function getProdutos()
    {
        $produtos = Produto::orderBy('created_at', 'desc')->get();

        return $produtos;
    }
}

if (!function_exists('getCupons')) {
    function getCupons()
    {
        $cupons = Cupom::orderBy('created_at', 'desc')->paginate(10);

        return $cupons;
    }
}

