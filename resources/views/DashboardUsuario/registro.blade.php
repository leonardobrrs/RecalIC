<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro de Ocorrências</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="d-flex justify-content-center align-items-center vh-100 bg-light">
<div class="card col-md-6 shadow-sm">
    <div class="card-header text-center bg-primary text-white">
        <h5 class="card-title fs-2 mb-0">REGISTRO DE OCORRÊNCIAS</h5>
    </div>
    <div class="card-body">

        @error('limite')
        <div class="alert alert-warning">
            {{ $message }}
        </div>
        @enderror

        @if(Auth::user()->reputation_score <= 0)
            <div class="alert alert-danger text-center">
                <i class="bi bi-x-octagon-fill"></i>
                <strong>Sua conta está bloqueada.</strong>
                <p class="mb-0">Você não pode registrar novas ocorrências devido a um histórico de relatos inválidos.</p>
            </div>
        @endif

        <form id="ocorrenciaForm" action="{{ url('/ocorrencias/registrar') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <fieldset @if(Auth::user()->reputation_score <= 0) disabled @endif>
                <div class="row mb-3 align-items-center">
                    <div class="col-sm-4">
                        <label class="form-label fw-semibold">Localização</label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" name="localizacao" class="form-control" placeholder="Ex: Laboratório 1, Bloco A" value="{{ old('localizacao') }}" required>
                    </div>
                </div>

                <div class="row mb-3 align-items-center">
                    <div class="col-sm-4">
                        <label for="categoryFilter" class="form-label fw-semibold">Categoria</label>
                    </div>
                    <div class="col-sm-8">
                        <select id="categoryFilter" name="categoria" class="form-select" required>
                            <option value="" disabled {{ old('categoria') ? '' : 'selected' }}>Selecione...</option>
                            <option value="Equipamentos" {{ old('categoria') == 'Equipamentos' ? 'selected' : '' }}>Equipamentos</option>
                            <option value="Infraestrutura" {{ old('categoria') == 'Infraestrutura' ? 'selected' : '' }}>Infraestrutura</option>
                            <option value="Eletrônicos" {{ old('categoria') == 'Eletrônicos' ? 'selected' : '' }}>Eletrônicos</option>
                            <option value="Patrimônio" {{ old('categoria') == 'Patrimônio' ? 'selected' : '' }}>Patrimônio</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3 align-items-center">
                    <div class="col-sm-4">
                        <label class="form-label fw-semibold">Nº de Patrimônio</label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" name="patrimonio_id" class="form-control" placeholder="(Opcional)" value="{{ old('patrimonio_id') }}">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4">
                        <label class="form-label fw-semibold">Descrição Detalhada</label>
                    </div>
                    <div class="col-sm-8">
                        <textarea name="descricao" class="form-control" rows="3" placeholder="Descreva o problema em detalhes..." required>{{ old('descricao') }}</textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-4"><label class="form-label fw-semibold">Anexar Fotos</label></div>
                    <div class="col-sm-8">
                        <input type="file" name="anexos[]" class="form-control" multiple>
                        <small class="form-text text-muted">Você pode selecionar várias imagens.</small>
                    </div>
                </div>
            </fieldset> <div class="row mt-4 pt-3 border-top">
                <div class="col d-flex justify-content-between align-items-center">
                    <button type="button" class="btn btn-outline-secondary" onclick="window.location.href='{{ url('/dashboard') }}'">
                        <i class="bi bi-arrow-left"></i> Voltar
                    </button>

                    <button class="btn btn-primary" type="submit" @if(Auth::user()->reputation_score <= 0) disabled @endif>
                        <i class="bi bi-check-circle"></i> Registrar
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
