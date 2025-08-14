<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Painel')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .header {
            background-color: #fff;
            border-bottom: 1px solid #dee2e6;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            color: #212529;
            text-decoration: none;
            margin: 0;
            font-weight: bold;
        }

        .logo:hover {
            color: #0d6efd;
            text-decoration: none;
        }

        .cart-btn {
            background-color: #0d6efd;
            border: none;
            color: white;
            width: 45px;
            height: 45px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: background-color 0.2s;
        }

        .cart-btn:hover {
            background-color: #0b5ed7;
            color: white;
            text-decoration: none;
        }

        .main-wrapper {
            display: flex;
            flex-grow: 1;
        }

        .sidebar {
            width: 240px;
            background-color: #f8f9fa;
        }

        .sidebar .nav-link.active {
            background-color: #e9ecef;
            font-weight: bold;
        }

        .main-content {
            flex-grow: 1;
            padding: 30px;
            background-color: #fff;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <a href="{{ route('produtos.lista') }}" class="logo">
            <h1>ERP Teste</h1>
        </a>
        <a href="{{ route('carrinho.listar') }}" class="cart-btn" title="Carrinho">
            <i class="bi bi-cart3 fs-5"></i>
        </a>
    </div>

    <!-- Wrapper para sidebar e conteúdo principal -->
    <div class="main-wrapper">
        <!-- Sidebar -->
        <div class="sidebar p-3 border-end">
            <h5 class="mb-4">Menu</h5>
            <div class="list-group">

                <div class="mb-3">
                    <strong class="text-muted">Produto</strong>
                    <a href="{{ route('produtos.novo') }}" class="list-group-item list-group-item-action {{ request()->routeIs('produtos.store') ? 'active' : '' }}">
                        Cadastrar Produto
                    </a>
                    <a href="{{ route('produtos.listar') }}" class="list-group-item list-group-item-action {{ request()->routeIs('produtos.index') ? 'active' : '' }}">
                        Listar Produtos
                    </a>
                </div>

                <div class="mb-3">
                    <strong class="text-muted">Cupom</strong>
                    <a href="{{ route('cupons.novo') }}" class="list-group-item list-group-item-action {{ request()->routeIs('cupom.create') ? 'active' : '' }}">
                        Cadastrar Cupom
                    </a>
                    <a href="{{ route('cupons.listar') }}" class="list-group-item list-group-item-action {{ request()->routeIs('cupom.index') ? 'active' : '' }}">
                        Listar Cupons
                    </a>
                </div>
                {{--
                <div class="mb-3">
                    <strong class="text-muted">Estoque</strong>
                    <a href="{{ route('estoque.update') }}" class="list-group-item list-group-item-action {{ request()->routeIs('estoque.create') ? 'active' : '' }}">
                        Cadastrar Estoque
                    </a>
                    <a href="{{ route('estoque.listar') }}" class="list-group-item list-group-item-action {{ request()->routeIs('estoque.index') ? 'active' : '' }}">
                        Listar Estoque
                    </a>
                </div>--}}

            </div>
        </div>

        <!-- Conteúdo principal -->
        <div class="main-content">
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>