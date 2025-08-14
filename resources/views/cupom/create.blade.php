<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Cupom - Cadastro</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .preview-card {
            border: 2px dashed #dee2e6;
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-radius: 10px;
        }
        .coupon-preview {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid">
        <!-- Header -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
            <div class="container">
                <a class="navbar-brand" href="#">
                    <i class="fas fa-tags me-2"></i>Sistema de Cupons
                </a>
            </div>
        </nav>

        <div class="container">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="index.html" class="text-decoration-none">Cupons</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Novo Cupom</li>
                </ol>
            </nav>

            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-plus-circle me-2 text-primary"></i>Criar Novo Cupom
                </h1>
                <a href="index.html" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Voltar
                </a>
            </div>

            <div class="row">
                <!-- Formulário -->
                <div class="col-lg-8">
                    <form method="POST" action="{{ route('cupons.store') }}" novalidate>
                        @csrf
                        <!-- Informações do Cupom -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-info-circle me-2"></i>Informações do Cupom
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="couponCode" class="form-label">
                                            Código do Cupom <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="couponCode" name="codigo" placeholder="Ex: DESCONTO20" required>
                                            <button type="button" class="btn btn-outline-secondary" id="generateCode" title="Gerar código aleatório">
                                                <i class="fas fa-magic"></i>
                                            </button>
                                        </div>
                                        <div class="invalid-feedback">
                                            Por favor, insira um código válido.
                                        </div>
                                        <small class="form-text text-muted">Apenas letras maiúsculas e números</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="discountValue" class="form-label">
                                            Desconto (%) <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" id="discountValue" name="valor_desconto" placeholder="0" min="1" max="100" step="1" required>
                                            <span class="input-group-text">%</span>
                                        </div>
                                        <div class="invalid-feedback">
                                            Por favor, insira um valor entre 1 e 100.
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="minValue" class="form-label">Valor Mínimo do Pedido</label>
                                        <div class="input-group">
                                            <span class="input-group-text">R$</span>
                                            <input type="number" class="form-control" id="minValue" name="valor_minimo" placeholder="0,00" min="0" step="0.01">
                                        </div>
                                        <small class="form-text text-muted">Valor mínimo do pedido para usar este cupom. Deixe em branco para não ter limite mínimo.</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Data de Validade -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-calendar-alt me-2"></i>Validade
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="endDate" class="form-label">
                                            Data de Término <span class="text-danger">*</span>
                                        </label>
                                        <input type="datetime-local" class="form-control" id="endDate" name="validade" required>
                                        <div class="invalid-feedback">
                                            Por favor, selecione a data de término.
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="status" class="form-label">Status</label>
                                        <select class="form-select" id="status" name="status">
                                            <option value="1" selected>Ativo</option>
                                            <option value="0">Inativo</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="noExpiration" name="sem_expiracao">
                                    <label class="form-check-label" for="noExpiration">
                                        Este cupom não tem data de expiração
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Botões de Ação -->
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-outline-danger" onclick="window.location.href='index.html'">
                                        <i class="fas fa-times me-2"></i>Cancelar
                                    </button>
                                    <div>
                                        <button type="button" class="btn btn-outline-primary me-2" id="saveDraft">
                                            <i class="fas fa-save me-2"></i>Salvar como Rascunho
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-check me-2"></i>Criar Cupom
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Preview do Cupom -->
                <div class="col-lg-4">
                    <div class="sticky-top" style="top: 20px;">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-eye me-2"></i>Preview do Cupom
                                </h5>
                            </div>
                            <div class="card-body">
                                <div id="couponPreview" class="preview-card">
                                    <div class="coupon-preview">
                                        <h4 class="mb-2" id="previewCode">CÓDIGO</h4>
                                        <div class="h2 mb-2" id="previewDiscount">0% OFF</div>
                                        <small id="previewValidity">Válido até: -</small>
                                        <div class="mt-2">
                                            <small id="previewConditions"></small>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Informações Adicionais -->
                                <div class="mt-3">
                                    <h6>Resumo:</h6>
                                    <ul class="list-unstyled small">
                                        <li><i class="fas fa-percentage text-primary me-2"></i><span id="summaryDiscount">Desconto: -</span></li>
                                        <li><i class="fas fa-shopping-cart text-primary me-2"></i><span id="summaryMinValue">Valor mínimo: -</span></li>
                                        <li><i class="fas fa-calendar text-primary me-2"></i><span id="summaryValidity">Validade: -</span></li>
                                        <li><i class="fas fa-info-circle text-primary me-2"></i><span id="summaryStatus">Status: -</span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Dicas -->
                        <div class="card mt-3">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0">
                                    <i class="fas fa-lightbulb me-2"></i>Dicas
                                </h6>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled small mb-0">
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Use códigos fáceis de lembrar</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Códigos únicos evitam conflitos</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Configure datas adequadas</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Teste antes de ativar</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        // Elementos do formulário
        //const form = document.getElementById('couponForm');
        const couponCode = document.getElementById('couponCode');
        const discountValue = document.getElementById('discountValue');
        const minValue = document.getElementById('minValue');
        const endDate = document.getElementById('endDate');
        const status = document.getElementById('status');
        const noExpirationCheck = document.getElementById('noExpiration');
        const generateCodeBtn = document.getElementById('generateCode');

        // Elementos do preview
        const previewCode = document.getElementById('previewCode');
        const previewDiscount = document.getElementById('previewDiscount');
        const previewValidity = document.getElementById('previewValidity');
        const previewConditions = document.getElementById('previewConditions');
        const summaryDiscount = document.getElementById('summaryDiscount');
        const summaryValidity = document.getElementById('summaryValidity');
        const summaryStatus = document.getElementById('summaryStatus');

        // Configurar data final como 30 dias à frente
        const now = new Date();
        const futureDate = new Date(now.getTime() + 30 * 24 * 60 * 60 * 1000);
        futureDate.setMinutes(futureDate.getMinutes() - futureDate.getTimezoneOffset());
        endDate.value = futureDate.toISOString().slice(0, 16);

        // Função para gerar código aleatório
        generateCodeBtn.addEventListener('click', function() {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            let result = '';
            for (let i = 0; i < 8; i++) {
                result += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            couponCode.value = result;
            updatePreview();
        });

        // Controle de expiração
        noExpirationCheck.addEventListener('change', function() {
            endDate.disabled = this.checked;
            endDate.required = !this.checked;
            if (this.checked) {
                endDate.value = '';
            } else {
                endDate.value = futureDate.toISOString().slice(0, 16);
            }
            updatePreview();
        });

        // Atualizar preview em tempo real
        function updatePreview() {
            // Código
            previewCode.textContent = couponCode.value || 'CÓDIGO';
            
            // Desconto
            const discount = discountValue.value || '0';
            previewDiscount.textContent = `${discount}% OFF`;
            summaryDiscount.textContent = `Desconto: ${discount}%`;
            
            // Valor mínimo
            const minVal = minValue.value || '';
            const summaryMinValueEl = document.getElementById('summaryMinValue');
            if (minVal && parseFloat(minVal) > 0) {
                const formattedMinValue = parseFloat(minVal).toLocaleString('pt-BR', {
                    style: 'currency',
                    currency: 'BRL'
                });
                summaryMinValueEl.textContent = `Valor mínimo: ${formattedMinValue}`;
                previewConditions.textContent = `Compras acima de ${formattedMinValue}`;
            } else {
                summaryMinValueEl.textContent = 'Valor mínimo: Sem limite';
                previewConditions.textContent = 'Válido para qualquer valor';
            }
            
            // Validade
            if (noExpirationCheck.checked) {
                previewValidity.textContent = 'Sem data de expiração';
                summaryValidity.textContent = 'Validade: Sem expiração';
            } else if (endDate.value) {
                const date = new Date(endDate.value);
                const formattedDate = date.toLocaleDateString('pt-BR');
                previewValidity.textContent = `Válido até: ${formattedDate}`;
                summaryValidity.textContent = `Validade: ${formattedDate}`;
            } else {
                previewValidity.textContent = 'Válido até: -';
                summaryValidity.textContent = 'Validade: -';
            }
            
            // Status
            const statusText = status.value === '1' ? 'Ativo' : 'Inativo';
            summaryStatus.textContent = `Status: ${statusText}`;
        }

        // Event listeners para atualizar preview
        couponCode.addEventListener('input', function() {
            // Converter para maiúsculas e remover caracteres especiais
            this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
            updatePreview();
        });

        discountValue.addEventListener('input', updatePreview);
        minValue.addEventListener('input', updatePreview);
        endDate.addEventListener('change', updatePreview);
        status.addEventListener('change', updatePreview);

        // Validação do formulário
        /*form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!form.checkValidity()) {
                e.stopPropagation();
                form.classList.add('was-validated');
                return;
            }
            
            // Validações customizadas
            if (couponCode.value.length < 3) {
                couponCode.setCustomValidity('O código deve ter pelo menos 3 caracteres');
                couponCode.classList.add('is-invalid');
                return;
            } else {
                couponCode.setCustomValidity('');
                couponCode.classList.remove('is-invalid');
            }
            
            if (!noExpirationCheck.checked && new Date(endDate.value) <= new Date()) {
                endDate.setCustomValidity('A data de término deve ser no futuro');
                endDate.classList.add('is-invalid');
                return;
            } else {
                endDate.setCustomValidity('');
                endDate.classList.remove('is-invalid');
            }
            
            // Se chegou até aqui, o formulário é válido
            alert('Cupom criado com sucesso!');
            // Aqui você adicionaria a lógica para enviar os dados para o servidor
        });

        // Salvar rascunho
        document.getElementById('saveDraft').addEventListener('click', function() {
            if (couponCode.value || discountValue.value || minValue.value) {
                alert('Rascunho salvo com sucesso!');
                // Aqui você adicionaria a lógica para salvar o rascunho
            } else {
                alert('Preencha pelo menos um campo para salvar um rascunho.');
            }
        });*/

        // Inicializar preview
        updatePreview();
    </script>
</body>
</html>