<div class="carrinho-container {{ $containerClass }}">
    @if($showHeader)
        <div class="carrinho-header mb-4">
            <h4 class="mb-0">
                <i class="fas fa-shopping-cart me-2"></i>Seu Carrinho
            </h4>
        </div>
    @endif

    <div class="carrinho-content">
        <div id="cartItemsContainer">
            <!-- Itens do carrinho aparecerão aqui via JavaScript -->
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Carregando...</span>
                </div>
                <p class="mt-2 text-muted">Carregando carrinho...</p>
            </div>
        </div>
    </div>

    @if($showActions)
        <div class="carrinho-footer mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Total: <span id="cartTotal" class="text-success">R$ 0,00</span></h5>
            </div>
            
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="button" class="btn btn-outline-danger" id="clearCartBtn">
                    <i class="fas fa-trash me-1"></i>Limpar Carrinho
                </button>
                <button type="button" class="btn btn-primary" id="goToCheckoutBtn">
                    <i class="fas fa-credit-card me-1"></i>Finalizar Compra
                </button>
            </div>
        </div>
    @endif
</div>

<style>
.carrinho-container {
    min-height: 200px;
}

.cart-item-actions {
    display: flex;
    align-items: center;
    gap: 10px;
}

.quantity-controls {
    display: flex;
    align-items: center;
    gap: 5px;
}

.quantity-controls button {
    width: 30px;
    height: 30px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

@media (max-width: 768px) {
    .cart-item-actions {
        flex-direction: column;
        gap: 5px;
    }
    
    .carrinho-footer .d-md-flex {
        flex-direction: column;
    }
    
    .carrinho-footer .btn {
        margin-bottom: 0.5rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Funções para gerenciar o carrinho via controller
    window.updateQuantity = function(produtoId, variacaoId, delta) {
        const currentQty = parseInt(document.querySelector(`[data-produto-id="${produtoId}"][data-variacao-id="${variacaoId}"] .quantity-display`).textContent);
        const newQty = currentQty + delta;
        
        if (newQty < 1) return;

        fetch("{{ route('carrinho.atualizar') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                produto_id: produtoId,
                variacao_id: variacaoId,
                quantidade: newQty
            })
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
            if (data.erro) {
                alert(data.erro);
            } else {
                renderCart(data.carrinho);
                console.log('Quantidade atualizada via componente: ', data.carrinho);
            }
        })
        .catch(err => {
            console.error('Erro detalhado:', err);
            alert('Erro ao atualizar quantidade: ' + err.message);
        });
    };

    window.removeItem = function(produtoId, variacaoId) {
        if (!confirm('Deseja remover este item do carrinho?')) return;

        fetch("{{ route('carrinho.remover') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                produto_id: produtoId,
                variacao_id: variacaoId
            })
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
            if (data.erro) {
                alert(data.erro);
            } else {
                renderCart(data.carrinho);
                console.log('Item removido via componente');
            }
        })
        .catch(err => {
            console.error('Erro detalhado:', err);
            alert('Erro ao remover item: ' + err.message);
        });
    };

    // Limpar carrinho
    const clearCartBtn = document.getElementById('clearCartBtn');
    if (clearCartBtn) {
        clearCartBtn.addEventListener('click', function() {
            if (!confirm('Deseja limpar todo o carrinho?')) return;

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
                renderCart(data.carrinho);
                console.log('Carrinho limpo via componente');
            })
            .catch(err => {
                console.error('Erro detalhado:', err);
                alert('Erro ao limpar carrinho: ' + err.message);
            });
        });
    }

    // Finalizar compra
    const goToCheckoutBtn = document.getElementById('goToCheckoutBtn');
    if (goToCheckoutBtn) {
        goToCheckoutBtn.addEventListener('click', function() {
            window.location.href = "{{ route('carrinho.verCarrinho') }}";
        });
    }

    // Função para renderizar o carrinho
    function renderCart(carrinhoData) {
        const container = document.getElementById('cartItemsContainer');
        const cartTotal = document.getElementById('cartTotal');
        
        if (!carrinhoData || !carrinhoData.itens || carrinhoData.itens.length === 0) {
            container.innerHTML = `
                <div class="text-center py-5">
                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Seu carrinho está vazio</h5>
                    <p class="text-muted">Adicione alguns produtos para começar suas compras!</p>
                </div>
            `;
            if (cartTotal) cartTotal.textContent = 'R$ 0,00';
            
            // Disparar evento para atualizar resumo no index
            const evento = new CustomEvent('carrinhoAtualizado', {
                detail: { 
                    subtotal: 0,
                    frete: 0,
                    total: 0,
                    subtotalFormatado: 'R$ 0,00',
                    freteFormatado: 'Grátis',
                    totalFormatado: 'R$ 0,00',
                    itens: [],
                    quantidade: 0
                }
            });
            document.dispatchEvent(evento);
            return;
        }

        let html = '';
        carrinhoData.itens.forEach(item => {
            html += `
                <div class="card mb-3" data-produto-id="${item.produto_id}" data-variacao-id="${item.variacao_id}">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h6 class="mb-1">${item.nome}</h6> 
                                <small class="text-muted">Variação: ${item.variacao_nome}</small><br>
                                <small class="text-muted">Preço unitário: R$ ${item.preco}</small>
                            </div>
                            <div class="col-md-6">
                                <div class="cart-item-actions">
                                    <div class="quantity-controls">
                                        <button class="btn btn-outline-secondary btn-sm" 
                                                onclick="updateQuantity(${item.produto_id}, ${item.variacao_id}, -1)">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <span class="quantity-display px-2">${item.quantidade}</span>
                                        <button class="btn btn-outline-secondary btn-sm" 
                                                onclick="updateQuantity(${item.produto_id}, ${item.variacao_id}, 1)">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                    <div class="text-center">
                                        <strong>R$ ${item.subtotal.toFixed(2).replace('.', ',')}</strong>
                                    </div>
                                    <button class="btn btn-outline-danger btn-sm" 
                                            onclick="removeItem(${item.produto_id}, ${item.variacao_id})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        container.innerHTML = html;

        const subtotalFormatado = `R$ ${carrinhoData.subtotal.toFixed(2).replace('.', ',')}`;
        const totalFormatado = `R$ ${carrinhoData.total.toFixed(2).replace('.', ',')}`;
        const freteFormatado = carrinhoData.frete_formatado || 'Grátis';

        //const totalFormatted = `R$ ${carrinhoData.total.toFixed(2).replace('.', ',')}`;
        if (cartTotal) cartTotal.textContent = totalFormatted;
        
        // CORREÇÃO PRINCIPAL: Disparar evento customizado para sincronizar com o resumo
        const evento = new CustomEvent('carrinhoAtualizado', {
            detail: { 
                subtotal: carrinhoData.subtotal,
                frete: carrinhoData.frete,
                total: carrinhoData.total,
                subtotalFormatado: subtotalFormatado,
                freteFormatado: freteFormatado,
                totalFormatado: totalFormatado,
                itens: carrinhoData.itens,
                quantidade: carrinhoData.itens.reduce((total, item) => total + item.quantidade, 0),
                freteGratis: carrinhoData.frete_gratis
            }
        });

        document.dispatchEvent(evento);
        console.log('Evento carrinhoAtualizado disparado:', evento.detail);
    }

    // Carregar carrinho automaticamente
    function loadCart() {
        fetch("{{ route('carrinho.obter') }}", {
            method: 'GET',
            headers: {
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
            renderCart(data.carrinho);
        })
        .catch(err => {
            console.error('Erro detalhado:', err);
            document.getElementById('cartItemsContainer').innerHTML = `
                <div class="text-center py-5">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                    <h5 class="text-warning">Erro ao carregar carrinho</h5>
                    <p class="text-muted">${err.message}</p>
                    <button class="btn btn-primary" onclick="loadCart()">Tentar novamente</button>
                </div>
            `;
        });
    }

    // Carregar carrinho ao inicializar
    loadCart();

    // Expor função globalmente para recarregar quando necessário
    window.loadCart = loadCart;
    window.renderCart = renderCart;
});
</script>