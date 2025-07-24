@extends('layouts.layout')

@section('title', 'Listar Produtos')

@section('content')
    <div class="container">
        <h2 class="mb-4">Produtos Cadastrados</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Slug</th>
                    <th>Preço Base</th>
                    <th class="text-center" style="width: 180px;">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($produtos as $produto)
                    <tr>
                        <td>{{ $produto->id }}</td>
                        <td>{{ $produto->nome }}</td>
                        <td>{{ $produto->slug }}</td>
                        <td>R$ {{ number_format($produto->preco_base, 2, ',', '.') }}</td>
                        <td class="text-center">
                            <a href="{{ route('produtos.edit', $produto->id) }}" class="btn btn-sm btn-warning">
                                Editar
                            </a>
                            <form action="{{ route('produtos.destroy', $produto->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir este produto?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Nenhum produto cadastrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
