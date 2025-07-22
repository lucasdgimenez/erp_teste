<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Painel')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            display: flex;
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

    <!-- Sidebar -->
    <div class="sidebar p-3 border-end">
        <h5 class="mb-4">Menu</h5>
        <div class="list-group">

            <div class="mb-3">
                <strong class="text-muted">Produto</strong>
                <a href="{{ route('produtos.novo') }}" class="list-group-item list-group-item-action {{ request()->routeIs('produtos.create') ? 'active' : '' }}">
                    Cadastrar Produto
                </a>
                <a href="{{ route('produtos.listar') }}" class="list-group-item list-group-item-action {{ request()->routeIs('produtos.index') ? 'active' : '' }}">
                    Listar Produtos
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

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
