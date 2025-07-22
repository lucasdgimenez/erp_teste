<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Produto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    @extends('layouts.layout')

    <h2 class="mb-4">Editar Produto</h2>

    <form action="{{ route('produtos.update', $produto->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Nome -->
        <div class="mb-3">
            <label for="nome" class="form-label">Nome do Produto</label>
            <input type="text" class="form-control" name="nome" id="nome" value="{{ old('nome', $produto->nome) }}" required>
        </div>

        <!-- Preço base -->
        <div class="mb-4">
            <label for="preco" class="form-label">Preço base (R$)</label>
            <input type="number" step="0.01" class="form-control" name="preco" id="preco" value="{{ old('preco', $produto->preco_base) }}" required>
        </div>

        <!-- Variações -->
        <h5 class="mb-3">Variações</h5>
        <div id="variacoes-container">
            @foreach($produto->variacoes as $i => $variacao)
            <div class="row g-2 mb-3 variacao-item align-items-end">
                <input type="hidden" name="variacoes[{{ $i }}][id]" value="{{ $variacao->id }}">
                <div class="col-md-4">
                    <label class="form-label">Nome</label>
                    <input type="text" name="variacoes[{{ $i }}][nome]" class="form-control" value="{{ $variacao->nome }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Preço</label>
                    <input type="number" step="0.01" name="variacoes[{{ $i }}][preco]" class="form-control" value="{{ $variacao->preco }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Estoque</label>
                    <input type="number" name="variacoes[{{ $i }}][estoque]" class="form-control" value="{{ $variacao->estoque->quantidade ?? 0 }}">
                </div>
                <div class="col-md-2 d-grid">
                    <button type="button" class="btn btn-danger remove-variacao">Remover</button>
                </div>
            </div>
            @endforeach
        </div>

        <button type="button" class="btn btn-outline-secondary mb-4" id="add-variacao">+ Adicionar Variação</button>

        <div>
            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        </div>
    </form>
</div>

<!-- Bootstrap JS + Script -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let index = {{ count($produto->variacoes) }};

    document.getElementById('add-variacao').addEventListener('click', function () {
        const container = document.getElementById('variacoes-container');
        const newItem = document.createElement('div');
        newItem.classList.add('row', 'g-2', 'mb-3', 'variacao-item', 'align-items-end');

        newItem.innerHTML = `
            <div class="col-md-4">
                <label class="form-label">Nome</label>
                <input type="text" name="variacoes[${index}][nome]" class="form-control" placeholder="Nome da variação">
            </div>
            <div class="col-md-3">
                <label class="form-label">Preço</label>
                <input type="number" step="0.01" name="variacoes[${index}][preco]" class="form-control" placeholder="Preço">
            </div>
            <div class="col-md-3">
                <label class="form-label">Estoque</label>
                <input type="number" name="variacoes[${index}][estoque]" class="form-control" placeholder="Estoque">
            </div>
            <div class="col-md-2 d-grid">
                <button type="button" class="btn btn-danger remove-variacao">Remover</button>
            </div>
        `;

        container.appendChild(newItem);
        index++;
    });

    document.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('remove-variacao')) {
            e.target.closest('.variacao-item').remove();
        }
    });
</script>
</body>
</html>
