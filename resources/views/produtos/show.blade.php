@extends('layouts.layout')

@section('title', $produto->nome)

@section('content')
<div class="container my-5">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('produtos.novo') }}" class="text-decoration-none">
                    <i class="fas fa-home me-1"></i>Início
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('produtos.listar') }}" class="text-decoration-none">Produtos</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{ $produto->nome }}</li>
        </ol>
    </nav>

    <div class="row g-5">
        {{-- Coluna da Imagem --}}
        <div class="col-lg-6">
            <div class="product-image-container">
                @if($produto->imagem)
                    <div class="main-image mb-3">
                        <img src="{{ asset('storage/' . $produto->imagem) }}" 
                             class="img-fluid rounded shadow-lg w-100" 
                             alt="{{ $produto->nome }}"
                             style="height: 400px; object-fit: cover;">
                    </div>
                @else
                    <div class="d-flex align-items-center justify-content-center bg-light rounded shadow-lg" 
                         style="height: 400px;">
                        <div class="text-center">
                            <i class="fas fa-image fa-5x text-muted mb-3"></i>
                            <p class="text-muted">Imagem não disponível</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Coluna das Informações --}}
        <div class="col-lg-6">
            <div class="product-details">
                {{-- Título --}}
                <h1 class="display-5 fw-bold text-primary mb-3">{{ $produto->nome }}</h1>

                {{-- Preço Base --}}
                @if($produto->preco_base)
                    <div class="price-section mb-4">
                        <span class="text-muted">Preço base a partir de:</span>
                        <div class="display-4 text-success fw-bold">
                            R$ {{ number_format($produto->preco_base, 2, ',', '.') }}
                        </div>
                    </div>
                @endif

                {{-- Descrição --}}
                @if($produto->descricao)
                    <div class="description mb-4">
                        <h5 class="fw-bold mb-3">
                            <i class="fas fa-info-circle me-2"></i>Descrição
                        </h5>
                        <div class="bg-light p-3 rounded">
                            <p class="mb-0 lh-lg">{{ $produto->descricao }}</p>
                        </div>
                    </div>
                @endif

                {{-- Seleção de Variações --}}
                @if($variacoes->where('variacao_id', '!=', null)->count() > 0)
                    <div class="variations-section mb-4">
                        <h5 class="fw-bold mb-3">
                            <i class="fas fa-palette me-2"></i>Escolha uma variação:
                        </h5>
                        
                        <div class="variation-selector">
                            <div class="row g-3">
                                @foreach($variacoes->where('variacao_id', '!=', null) as $variacao)
                                    <div class="col-md-6">
                                        <div class="variation-card border rounded p-3 h-100" 
                                             data-variation-id="{{ $variacao->variacao_id }}"
                                             data-price="{{ $variacao->preco_variacao }}"
                                             style="cursor: pointer; transition: all 0.3s ease;">
                                            
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="fw-bold mb-1">{{ $variacao->nome_variacao }}</h6>
                                                    <span class="text-success fw-bold fs-5">
                                                        R$ {{ number_format($variacao->preco_variacao, 2, ',', '.') }}
                                                    </span>
                                                </div>
                                                <div class="variation-radio">
                                                    <input type="radio" 
                                                           name="variacao" 
                                                           value="{{ $variacao->variacao_id }}"
                                                           class="form-check-input"
                                                           id="variacao_{{ $variacao->variacao_id }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Preço da variação selecionada --}}
                        <div class="selected-price mt-3" id="selectedPriceContainer" style="display: none;">
                            <div class="alert alert-primary">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><strong>Variação selecionada:</strong> <span id="selectedVariationName"></span></span>
                                    <span class="fs-4 fw-bold" id="selectedPrice">R$ 0,00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Quantidade --}}
                <div class="quantity-section mb-4">
                    <h6 class="fw-bold mb-2">Quantidade:</h6>
                    <div class="input-group" style="max-width: 150px;">
                        <button class="btn btn-outline-secondary" type="button" id="decreaseQty">
                            <i class="fas fa-minus"></i>
                        </button>
                        <input type="number" class="form-control text-center" value="1" min="1" max="10" id="quantity">
                        <button class="btn btn-outline-secondary" type="button" id="increaseQty">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>

                {{-- Botões de Ação --}}
                <div class="action-buttons d-grid gap-2 d-md-flex mb-4">
                    <button class="btn btn-primary btn-lg flex-fill" id="addToCartBtn" disabled>
                        <i class="fas fa-shopping-cart me-2"></i>
                        Adicionar ao Carrinho
                    </button>
                    <button class="btn btn-success btn-lg flex-fill" id="buyNowBtn" disabled>
                        <i class="fas fa-bolt me-2"></i>
                        Comprar Agora
                    </button>
                </div>

                {{-- Lista de todas as variações disponíveis --}}
                @if($variacoes->where('variacao_id', '!=', null)->count() > 0)
                    <div class="variations-list mb-4">
                        <h6 class="fw-bold mb-3">Todas as variações disponíveis:</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Variação</th>
                                        <th>Preço</th>
                                        <th>Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($variacoes->where('variacao_id', '!=', null) as $variacao)
                                        <tr>
                                            <td>
                                                <strong>{{ $variacao->nome_variacao }}</strong>
                                            </td>
                                            <td class="text-success fw-bold">
                                                R$ {{ number_format($variacao->preco_variacao, 2, ',', '.') }}
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary select-variation-btn" 
                                                        data-variation-id="{{ $variacao->variacao_id }}"
                                                        data-variation-name="{{ $variacao->nome_variacao }}"
                                                        data-price="{{ $variacao->preco_variacao }}">
                                                    Selecionar
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

            
            </div>
        </div>
    </div>
</div>

{{-- CSS Personalizado --}}
<style>
.variation-card {
    transition: all 0.3s ease;
    border: 2px solid #dee2e6 !important;
}

.variation-card:hover {
    border-color: #0d6efd !important;
    background-color: #f8f9ff;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(13, 110, 253, 0.1);
}

.variation-card.selected {
    border-color: #0d6efd !important;
    background-color: #e7f3ff;
}

.variation-card.selected .variation-radio input {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.price-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 1rem;
    border-radius: 10px;
    border-left: 4px solid #28a745;
}

.product-details .btn {
    transition: all 0.3s ease;
}

.product-details .btn:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.additional-info i {
    transition: color 0.3s ease;
}

.additional-info i:hover {
    color: #0d6efd !important;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

@media (max-width: 768px) {
    .display-5 {
        font-size: 1.8rem;
    }
    
    .display-4 {
        font-size: 2rem;
    }
    
    .action-buttons {
        margin-top: 2rem;
    }
    
    .variation-card {
        margin-bottom: 1rem;
    }
}
</style>

{{-- JavaScript --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    let selectedVariation = null;
    let selectedPrice = 0;
    let selectedVariationName = '';
    let cart = [];

    // Elementos DOM
    const variationCards = document.querySelectorAll('.variation-card');
    const variationRadios = document.querySelectorAll('input[name="variacao"]');
    const addToCartBtn = document.getElementById('addToCartBtn');
    const buyNowBtn = document.getElementById('buyNowBtn');
    const selectedPriceContainer = document.getElementById('selectedPriceContainer');
    const selectedPriceElement = document.getElementById('selectedPrice');
    const selectedVariationNameElement = document.getElementById('selectedVariationName');
    const quantityInput = document.getElementById('quantity');

    // Seleção de variação pelos cards
    variationCards.forEach(card => {
        card.addEventListener('click', function() {
            const variationId = this.dataset.variationId;
            const price = parseFloat(this.dataset.price);
            const variationName = this.querySelector('h6').textContent;
            
            selectVariation(variationId, price, variationName, this);
        });
    });

    // Seleção de variação pelos botões da tabela
    document.querySelectorAll('.select-variation-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const variationId = this.dataset.variationId;
            const price = parseFloat(this.dataset.price);
            const variationName = this.dataset.variationName;
            
            // Encontrar o card correspondente
            const correspondingCard = document.querySelector(`[data-variation-id="${variationId}"]`);
            
            selectVariation(variationId, price, variationName, correspondingCard);
        });
    });

    // Função para selecionar variação
    function selectVariation(variationId, price, variationName, cardElement) {
        selectedVariation = variationId;
        selectedPrice = price;
        selectedVariationName = variationName;

        // Remover seleção anterior
        document.querySelectorAll('.variation-card').forEach(c => c.classList.remove('selected'));
        document.querySelectorAll('input[name="variacao"]').forEach(r => r.checked = false);

        // Adicionar seleção atual
        if (cardElement) {
            cardElement.classList.add('selected');
            const radio = cardElement.querySelector('input[name="variacao"]');
            if (radio) radio.checked = true;
        }

        // Atualizar preço selecionado
        selectedPriceElement.textContent = `R$ ${price.toLocaleString('pt-BR', {minimumFractionDigits: 2})}`;
        selectedVariationNameElement.textContent = variationName;
        selectedPriceContainer.style.display = 'block';

        // Habilitar botões
        addToCartBtn.disabled = false;
        buyNowBtn.disabled = false;

        // Atualizar texto dos botões da tabela
        document.querySelectorAll('.select-variation-btn').forEach(btn => {
            if (btn.dataset.variationId === variationId) {
                btn.textContent = 'Selecionado';
                btn.classList.remove('btn-outline-primary');
                btn.classList.add('btn-primary');
            } else {
                btn.textContent = 'Selecionar';
                btn.classList.remove('btn-primary');
                btn.classList.add('btn-outline-primary');
            }
        });
    }

    // Controle de quantidade
    document.getElementById('decreaseQty').addEventListener('click', function() {
        const current = parseInt(quantityInput.value);
        if (current > 1) {
            quantityInput.value = current - 1;
        }
    });

    document.getElementById('increaseQty').addEventListener('click', function() {
        const current = parseInt(quantityInput.value);
        const max = parseInt(quantityInput.getAttribute('max'));
        if (current < max) {
            quantityInput.value = current + 1;
        }
    });

    // Adicionar ao carrinho
    /*addToCartBtn.addEventListener('click', function () {
        if (!selectedVariation) {
            alert('Por favor, selecione uma variação primeiro.');
            return;
        }

        const quantity = parseInt(quantityInput.value);

        // Adicionar item ao array do carrinho
        cart.push({
            produto_id: {{ $produto->id }},
            nome: '{{ $produto->nome }}',
            variacao_id: selectedVariation,
            variacao_nome: selectedVariationName,
            quantidade: quantity,
            preco: selectedPrice
        });

        // Atualizar modal e abrir
        renderCart();
        const cartModal = new bootstrap.Modal(document.getElementById('cartModal'));
        cartModal.show();
    });*/

    addToCartBtn.addEventListener('click', function () {
        if (!selectedVariation) {
            alert('Por favor, selecione uma variação primeiro.');
            return;
        }

        const quantity = parseInt(quantityInput.value);

        fetch("{{ route('carrinho.adicionar') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                produto_id: {{ $produto->id }},
                variacao_id: selectedVariation,
                quantidade: quantity
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.erro) {
                alert(data.erro);
            } else {
                alert(data.mensagem);
                // Atualiza o carrinho visual, se quiser
            }
        })
        .catch(err => {
            console.error(err);
            alert('Erro ao adicionar ao carrinho.');
        });
    });



    // Comprar agora
    if (buyNowBtn) {
        buyNowBtn.addEventListener('click', function() {
            if (!selectedVariation) {
                alert('Por favor, selecione uma variação primeiro.');
                return;
            }
            
            // Redirecionar para checkout ou processar compra
            console.log('Comprando agora:', {
                produto_id: {{ $produto->id }},
                variacao_id: selectedVariation,
                quantidade: parseInt(quantityInput.value),
                preco: selectedPrice
            });
        });
    }

    // Lista de desejos
    const wishlistBtn = document.getElementById('wishlistBtn');
    if (wishlistBtn) {
        wishlistBtn.addEventListener('click', function() {
            const icon = this.querySelector('i');
            if (icon.classList.contains('far')) {
                icon.classList.remove('far');
                icon.classList.add('fas');
                this.classList.remove('btn-outline-danger');
                this.classList.add('btn-danger');
                this.innerHTML = '<i class="fas fa-heart me-1"></i>Na Lista';
            } else {
                icon.classList.remove('fas');
                icon.classList.add('far');
                this.classList.remove('btn-danger');
                this.classList.add('btn-outline-danger');
                this.innerHTML = '<i class="far fa-heart me-1"></i>Lista de Desejos';
            }
        });
    }

    // Copiar URL
    const copyUrlBtn = document.getElementById('copyUrl');
    if (copyUrlBtn) {
        copyUrlBtn.addEventListener('click', function() {
            const urlInput = document.getElementById('productUrl');
            urlInput.select();
            document.execCommand('copy');
            
            this.innerHTML = '<i class="fas fa-check"></i>';
            setTimeout(() => {
                this.innerHTML = '<i class="fas fa-copy"></i>';
            }, 2000);
        });
    }

    // Compartilhamento social
    const productName = '{{ $produto->nome }}';
    const productUrl = '{{ url()->current() }}';

    window.updateQuantity = function(index, delta) {
        cart[index].quantidade += delta;
        if (cart[index].quantidade < 1) {
            cart[index].quantidade = 1;
        }
        renderCart();
    };

    window.removeItem = function(index) {
        cart.splice(index, 1);
        renderCart();
    };

    
    function renderCart() {
        const container = document.getElementById('cartItemsContainer');
        const cartTotal = document.getElementById('cartTotal');
        container.innerHTML = '';
        let total = 0;

        if (cart.length === 0) {
            container.innerHTML = '<p class="text-center text-muted">Seu carrinho está vazio.</p>';
            cartTotal.textContent = 'R$ 0,00';
            return;
        }

        cart.forEach((item, index) => {
            const subtotal = item.quantidade * item.preco;
            total += subtotal;

            const itemHtml = `
                <div class="card mb-3">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">${item.nome}</h6>
                            <small class="text-muted">Variação: ${item.variacao_nome}</small><br>
                            <small class="text-muted">Preço unitário: R$ ${item.preco.toFixed(2).replace('.', ',')}</small>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <button class="btn btn-outline-secondary btn-sm" onclick="updateQuantity(${index}, -1)">-</button>
                            <span>${item.quantidade}</span>
                            <button class="btn btn-outline-secondary btn-sm" onclick="updateQuantity(${index}, 1)">+</button>
                            <button class="btn btn-outline-danger btn-sm" onclick="removeItem(${index})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            container.innerHTML += itemHtml;
        });

        cartTotal.textContent = `R$ ${total.toFixed(2).replace('.', ',')}`;
    }

    


});


</script>

{{-- Modal do Carrinho --}}
<div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="cartModalLabel">
          <i class="fas fa-shopping-cart me-2"></i>Seu Carrinho
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div id="cartItemsContainer">
          <!-- Itens do carrinho aparecerão aqui -->
        </div>
      </div>
      <div class="modal-footer justify-content-between">
        <h5 class="mb-0">Total: <span id="cartTotal" class="text-success">R$ 0,00</span></h5>
        <button type="button" class="btn btn-primary" id="goToCheckoutBtn">
          Finalizar Compra
        </button>
      </div>
    </div>
  </div>
</div>

@endsection