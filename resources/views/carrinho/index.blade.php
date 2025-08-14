@extends('layouts.layout')

@section('title', 'Meu Carrinho')

@section('content')
<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="container-fluid">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('produtos.lista') }}" class="text-decoration-none">
                    <i class="fas fa-home me-1"></i>Início
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Meu Carrinho</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-12">
            {{-- Título da página --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">
                    <i class="fas fa-shopping-cart me-2 text-primary"></i>
                    Meu Carrinho de Compras
                </h2>
                <a href="{{ route('produtos.listar') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Continuar Comprando
                </a>
            </div>

            {{-- Componente do Carrinho --}}
            <div class="row">
                <div class="col-lg-8">
                    <x-carrinho-component 
                        :show-header="false" 
                        :show-actions="false"
                        container-class="bg-white rounded shadow-sm p-4"
                    />
                </div>
                
                {{-- Resumo da compra --}}
                <div class="col-lg-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-calculator me-2"></i>Resumo da Compra
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span id="resumoSubtotal">R$ 0,00</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Frete:</span>
                                <span id="resumoFrete">Grátis</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-3">
                                <strong>Total:</strong>
                                <strong class="text-success fs-5" id="resumoTotal">R$ 0,00</strong>
                            </div>
                            
                            {{-- Área de cupom --}}
                            <div class="mb-3">
                                <label for="cupomDesconto" class="form-label">
                                    <i class="fas fa-tag me-1"></i>Cupom de Desconto
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="cupomDesconto" placeholder="Digite o cupom">
                                    <button class="btn btn-outline-secondary" type="button" id="aplicarCupom">
                                        Aplicar
                                    </button>
                                </div>
                            </div>

                            {{-- Botões de ação --}}
                            <div class="d-grid gap-2">
                                <button class="btn btn-primary btn-lg" id="finalizarCompraBtn">
                                    <i class="fas fa-credit-card me-2"></i>
                                    Finalizar Compra
                                </button>
                                <button class="btn btn-outline-danger" id="limparCarrinhoBtn">
                                    <i class="fas fa-trash me-2"></i>
                                    Limpar Carrinho
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Informações adicionais --}}
                    <div class="card mt-4 shadow-sm">
                        <div class="card-body">
                            <h6 class="card-title">
                                <i class="fas fa-shield-alt me-2 text-success"></i>
                                Compra Segura
                            </h6>
                            <p class="card-text small text-muted">
                                Seus dados estão protegidos e a transação é segura.
                            </p>
                            
                            <h6 class="card-title mt-3">
                                <i class="fas fa-truck me-2 text-primary"></i>
                                Frete Grátis
                            </h6>
                            <p class="card-text small text-muted">
                                Para compras acima de R$ 200,00 em todo o Brasil.
                            </p>

                            <h6 class="card-title mt-3">
                                <i class="fas fa-undo me-2 text-warning"></i>
                                Troca e Devolução
                            </h6>
                            <p class="card-text small text-muted">
                                7 dias para trocar ou devolver seus produtos.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.container-fluid {
    max-width: 1400px;
}

.card {
    border: none;
    transition: box-shadow 0.3s ease;
}

.card:hover {
    box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
}

.btn {
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

#finalizarCompraBtn:hover {
    box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);
}

.breadcrumb-item a:hover {
    color: #0d6efd !important;
}

@media (max-width: 768px) {
    .d-flex.justify-content-between {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 1rem;
    }
    
    .d-flex.justify-content-between .btn {
        align-self: stretch;
    }
}
</style>

<script>
// Substitua o script no final do index.blade.php por esta versão corrigida:

document.addEventListener('DOMContentLoaded', function() {
    // Escutar eventos de atualização do carrinho
    document.addEventListener('carrinhoAtualizado', function(event) {
        console.log('Evento carrinhoAtualizado recebido:', event.detail);
        const { subtotal, frete, total, freteFormatado, subtotalFormatado, totalFormatado } = event.detail;
        updateResumo(subtotalFormatado, freteFormatado, totalFormatado);
    });

    // Função para atualizar resumo
    function updateResumo(subtotalValue, freteValue, totalValue) {
        console.log('Atualizando resumo - Subtotal:', subtotalValue, 'Frete:', freteValue, 'Total:', totalValue);
        
        const resumoSubtotal = document.getElementById('resumoSubtotal');
        const resumoFrete = document.getElementById('resumoFrete');
        const resumoTotal = document.getElementById('resumoTotal');
        
        if (resumoSubtotal) {
            resumoSubtotal.textContent = subtotalValue;
            console.log('Subtotal atualizado para:', subtotalValue);
        }
        
        //if (resumoFrete) {
            resumoFrete.textContent = freteValue;
            // Alterar cor baseada no valor do frete
            if (freteValue === 'Grátis') {
                resumoFrete.className = 'text-success';
            } else {
                resumoFrete.className = 'text-primary';
            }
            console.log('Frete atualizado para:', freteValue);
        //}
        
        if (resumoTotal) {
            resumoTotal.textContent = totalValue;
            console.log('Total atualizado para:', totalValue);
        }
    }
    
    // Aguardar que as funções do componente sejam carregadas
    const waitForComponentFunctions = () => {
        if (window.updateQuantity && window.removeItem && window.renderCart) {
            console.log('Funções do componente carregadas, configurando interceptadores');
            setupInterceptors();
        } else {
            setTimeout(waitForComponentFunctions, 100);
        }
    };
    
    function setupInterceptors() {
        // Interceptar updateQuantity para garantir que o evento seja disparado
        const originalUpdateQuantity = window.updateQuantity;
        window.updateQuantity = function(produtoId, variacaoId, delta) {
            console.log('UpdateQuantity interceptado');
            originalUpdateQuantity(produtoId, variacaoId, delta);
        };
        
        // Interceptar removeItem para garantir que o evento seja disparado  
        const originalRemoveItem = window.removeItem;
        window.removeItem = function(produtoId, variacaoId) {
            console.log('RemoveItem interceptado');
            originalRemoveItem(produtoId, variacaoId);
        };
    }
    
    waitForComponentFunctions();

    // Aplicar cupom
    document.getElementById('aplicarCupom').addEventListener('click', function() {
        const cupom = document.getElementById('cupomDesconto').value.trim();
        if (!cupom) {
            alert('Digite um código de cupom válido.');
            return;
        }

        console.log('Aplicando cupom:', cupom);
        alert('Funcionalidade de cupom em desenvolvimento!');
    });

    // Finalizar compra
    document.getElementById('finalizarCompraBtn').addEventListener('click', function() {
        // Verificar se há itens no carrinho
        const cartItems = document.querySelectorAll('#cartItemsContainer .card');
        if (cartItems.length === 0) {
            alert('Seu carrinho está vazio! Adicione alguns produtos antes de finalizar a compra.');
            return;
        }

        console.log('Finalizando compra...');
        alert('Redirecionando para o checkout...');
    });

    // Limpar carrinho
    document.getElementById('limparCarrinhoBtn').addEventListener('click', function() {
        if (!confirm('Deseja realmente limpar todo o carrinho?')) return;

        fetch("{{ route('carrinho.limpar') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const contentType = response.headers.get("content-type");
            if (!contentType || !contentType.includes("application/json")) {
                throw new TypeError("Resposta não é JSON válido!");
            }
            return response.json();
        })
        .then(data => {
            // A função renderCart do componente já disparará o evento carrinhoAtualizado
            if (window.renderCart) {
                window.renderCart(data.carrinho);
            }
        })
        .catch(err => {
            console.error('Erro detalhado:', err);
            alert('Erro ao limpar carrinho: ' + err.message);
        });
    });

    // CORREÇÃO: Melhor sincronização inicial
    // Aguardar o componente carregar e sincronizar
    const waitForCartLoad = () => {
        if (window.loadCart && typeof window.loadCart === 'function') {
            console.log('Carregando carrinho inicial...');
            // Forçar carregamento do carrinho que automaticamente disparará o evento
            window.loadCart();
        } else {
            // Fallback: tentar sincronizar com o DOM diretamente
            const cartTotal = document.getElementById('cartTotal');
            if (cartTotal && cartTotal.textContent && cartTotal.textContent !== 'R$ 0,00') {
                // Assumir valores padrão se não temos os dados completos
                updateResumo(cartTotal.textContent, 'Grátis', cartTotal.textContent);
                console.log('Sincronização inicial realizada com fallback');
            } else {
                setTimeout(waitForCartLoad, 500);
            }
        }
    };
    
    // Aguardar um pouco mais para o componente carregar
    setTimeout(waitForCartLoad, 1500);
});
</script>

@endsection