<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cupons - Listagem</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container-fluid">
        <!-- Header -->
        

        <div class="container">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Cupons</li>
                </ol>
            </nav>

            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-ticket-alt me-2 text-primary"></i>Gerenciar Cupons
                </h1>
                <a href="{{ route('cupons.novo') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Novo Cupom
                </a>
            </div>

            <!-- Mensagens de Feedback -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('cupons.listar') }}" class="row g-3">
                        <div class="col-md-6">
                            <label for="search_code" class="form-label">Código do Cupom</label>
                            <input type="text" class="form-control" id="search_code" name="search_code" 
                                   value="{{ request('search_code') }}" placeholder="Digite o código...">
                        </div>
                        <div class="col-md-4">
                            <label for="filter_status" class="form-label">Status</label>
                            <select class="form-select" id="filter_status" name="filter_status">
                                <option value="">Todos</option>
                                <option value="ativo" {{ request('filter_status') == 'ativo' ? 'selected' : '' }}>Ativo</option>
                                <option value="inativo" {{ request('filter_status') == 'inativo' ? 'selected' : '' }}>Inativo</option>
                                <option value="expirado" {{ request('filter_status') == 'expirado' ? 'selected' : '' }}>Expirado</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-outline-primary w-100">
                                <i class="fas fa-search me-1"></i>Filtrar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-success">
                        <div class="card-body text-center">
                            <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                            <h5 class="card-title">Cupons Ativos</h5>
                            <h3 class="text-success">{{ $estatisticas['ativos'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-warning">
                        <div class="card-body text-center">
                            <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                            <h5 class="card-title">Próximos ao Vencimento</h5>
                            <h3 class="text-warning">{{ $estatisticas['proximos_vencimento'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-danger">
                        <div class="card-body text-center">
                            <i class="fas fa-times-circle fa-2x text-danger mb-2"></i>
                            <h5 class="card-title">Expirados</h5>
                            <h3 class="text-danger">{{ $estatisticas['expirados'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-info">
                        <div class="card-body text-center">
                            <i class="fas fa-chart-line fa-2x text-info mb-2"></i>
                            <h5 class="card-title">Total de Cupons</h5>
                            <h3 class="text-info">{{ $cupons->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Coupons Table -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Lista de Cupons</h5>
                    <span class="badge bg-secondary">{{ $cupons->count() }} cupons encontrados</span>
                </div>
                <div class="card-body p-0">
                    @if($cupons->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Código</th>
                                        <th scope="col">Desconto</th>
                                        <th scope="col">Valor Mínimo</th>
                                        <th scope="col">Data de Criação</th>
                                        <th scope="col">Validade</th>
                                        <th scope="col">Status</th>
                                        <th scope="col" class="text-center">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cupons as $index => $cupom)
                                        @php
                                            // Definir status do cupom
                                            $now = now();
                                            $badge = ['classe' => 'bg-secondary', 'texto' => 'Indefinido'];
                                            
                                            if ($cupom->validade && $cupom->validade <= $now) {
                                                $badge = ['classe' => 'bg-danger', 'texto' => 'Expirado'];
                                            } elseif ($cupom->validade && $cupom->validade <= $now->copy()->addDays(7)) {
                                                $badge = ['classe' => 'bg-warning text-dark', 'texto' => 'Próximo ao venc.'];
                                            } else {
                                                $badge = ['classe' => 'bg-success', 'texto' => 'Ativo'];
                                            }
                                        @endphp
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td><code class="bg-light p-1 rounded">{{ $cupom->codigo }}</code></td>
                                            <td><span class="badge bg-info">{{ number_format($cupom->valor_desconto, 2, ',', '.') }}%</span></td>
                                            <td>
                                                @if($cupom->valor_minimo)
                                                    R$ {{ number_format($cupom->valor_minimo, 2, ',', '.') }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>{{ $cupom->created_at ? $cupom->created_at->format('d/m/Y') : '-' }}</td>
                                            <td>
                                                @if($cupom->validade)
                                                    {{ \Carbon\Carbon::parse($cupom->validade)->format('d/m/Y H:i') }}
                                                @else
                                                    <span class="text-muted">Sem expiração</span>
                                                @endif
                                            </td>
                                            <td><span class="badge {{ $badge['classe'] }}">{{ $badge['texto'] }}</span></td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-outline-primary" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-info" title="Visualizar">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                                            onclick="confirmarExclusao('{{ $cupom->id }}', '{{ $cupom->codigo }}')" 
                                                            title="Excluir">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Nenhum cupom encontrado</h5>
                            <p class="text-muted">Clique no botão "Novo Cupom" para criar seu primeiro cupom.</p>
                            <a href="{{ route('cupons.novo') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Criar Primeiro Cupom
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        // Confirmação de exclusão
        function confirmarExclusao(cupomId, codigo) {
            if (confirm(`Tem certeza que deseja excluir o cupom "${codigo}"?`)) {
                // Criar formulário para enviar DELETE request
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/cupons/${cupomId}`;
                
                // CSRF Token
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                form.appendChild(csrfInput);
                
                // Method DELETE
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);
                
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Clear filters
        function limparFiltros() {
            window.location.href = '{{ route("cupons.listar") }}';
        }

        // Add clear filters button if there are active filters
        @if(request()->hasAny(['search_code', 'filter_status']))
            document.addEventListener('DOMContentLoaded', function() {
                const filterCard = document.querySelector('.card-body form');
                const clearBtn = document.createElement('div');
                clearBtn.className = 'col-12 text-end mt-2';
                clearBtn.innerHTML = '<button type="button" class="btn btn-outline-secondary btn-sm" onclick="limparFiltros()"><i class="fas fa-times me-1"></i>Limpar Filtros</button>';
                filterCard.appendChild(clearBtn);
            });
        @endif
    </script>
</body>
</html>