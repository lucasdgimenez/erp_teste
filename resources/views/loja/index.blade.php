@extends('layouts.layout')

@section('title', 'Produtos')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h2 text-primary">
                    <i class="fas fa-shopping-bag me-2"></i>
                    Nossos Produtos
                </h1>
                <span class="badge bg-secondary fs-6">{{ count($produtos) }} produtos encontrados</span>
            </div>
        </div>
    </div>

    @if($produtos->isEmpty())
        <div class="row">
            <div class="col-12">
                <div class="alert alert-info text-center py-5" role="alert">
                    <i class="fas fa-info-circle fa-3x mb-3"></i>
                    <h4>Nenhum produto encontrado</h4>
                    <p class="mb-0">Não há produtos disponíveis no momento.</p>
                </div>
            </div>
        </div>
    @else
        <div class="row g-4">
            @foreach($produtos as $product)
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                    <div class="card h-100 shadow-sm product-card" 
                         onclick="window.location.href='{{ route('produto.item', ['slug' => $product->slug, 'id_produto' => $product->id]) }}'"
                         style="cursor: pointer; transition: all 0.3s ease;">
                        
                        {{-- Imagem do produto --}}
                        {{--<div class="card-img-top position-relative overflow-hidden" style="height: 200px;">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" 
                                     class="img-fluid w-100 h-100" 
                                     style="object-fit: cover; transition: transform 0.3s ease;"
                                     alt="{{ $product->name }}">
                            @else
                                <div class="d-flex align-items-center justify-content-center h-100 bg-light">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                </div>
                            @endif
                            
                            @if($product->featured ?? false)
                                <span class="position-absolute top-0 start-0 badge bg-warning text-dark m-2">
                                    <i class="fas fa-star me-1"></i>Destaque
                                </span>
                            @endif
                            
                            @if($product->status === 'inactive')
                                <span class="position-absolute top-0 end-0 badge bg-danger m-2">
                                    Indisponível
                                </span>
                            @endif
                        </div>--}}
                        
                        {{-- Corpo do card --}}
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-truncate" title="{{ $product->nome }}">
                                {{ $product->nome }}
                            </h5>
                            
                            @if($product->descricao)
                                <p class="card-text text-muted small flex-grow-1" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    {{ $product->descricao }}
                                </p>
                            @endif
                            
                            <div class="mt-auto">
                                {{-- Preço --}}
                                @if($product->preco_base)
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="h5 text-success mb-0 fw-bold">
                                            R$ {{ number_format($product->preco_base, 2, ',', '.') }}
                                        </span>
                                        @if($product->stock ?? 0 > 0)
                                            <small class="text-success">
                                                <i class="fas fa-check-circle me-1"></i>Em estoque
                                            </small>
                                        @else
                                            <small class="text-danger">
                                                <i class="fas fa-times-circle me-1"></i>Sem estoque
                                            </small>
                                        @endif
                                    </div>
                                @endif
                                
                                {{-- Categoria --}}
                                {{--@if($product->category)
                                    <div class="mt-2">
                                        <span class="badge bg-light text-dark border">
                                            {{ $product->category->name }}
                                        </span>
                                    </div>
                                @endif--}}
                            </div>
                        </div>
                        
                        {{-- Footer do card --}}
                        <div class="card-footer bg-transparent border-top-0">
                            <div class="d-grid">
                                <span class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye me-2"></i>Ver Detalhes
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        {{-- Paginação (se aplicável) --}}
        @if(method_exists($produtos, 'links'))
            <div class="row mt-5">
                <div class="col-12">
                    <div class="d-flex justify-content-center">
                        {{ $produtos->links() }}
                    </div>
                </div>
            </div>
        @endif
    @endif
</div>

{{-- CSS personalizado --}}
<style>
.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.product-card:hover .card-img-top img {
    transform: scale(1.05);
}

.product-card:hover .btn-outline-primary {
    background-color: var(--bs-primary);
    color: white;
}

.card-title {
    font-weight: 600;
    color: #333;
}

.product-card {
    border: 1px solid #e9ecef;
    border-radius: 12px;
}

.product-card .card-img-top {
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
}

@media (max-width: 576px) {
    .container {
        padding-left: 10px;
        padding-right: 10px;
    }
    
    .card {
        margin-bottom: 1rem;
    }
}
</style>

{{-- Script para melhorar a interação --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Adiciona efeito de loading ao clicar no card
    const productCards = document.querySelectorAll('.product-card');
    
    productCards.forEach(card => {
        card.addEventListener('click', function() {
            const btn = this.querySelector('.btn');
            if (btn) {
                btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Carregando...';
                btn.classList.add('disabled');
            }
        });
    });
});
</script>
@endsection