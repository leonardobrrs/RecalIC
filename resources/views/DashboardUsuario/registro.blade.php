<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Ocorrências</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        .sidebar {
            background-color: #0d6efd;
            color: white;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100vh;
            position: fixed;
            width: 280px;
        }

        .sidebar-footer {
            margin-top: auto;
            width: 100%;
            padding-bottom: 20px;
        }

        .logout-button {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            background: none;
            border: none;
            color: white;
            font-size: 1.1rem;
            cursor: pointer;
            opacity: 0.8;
            transition: opacity 0.3s ease;
            text-decoration: none;
            width: 100%;
        }

        .logout-button:hover {
            opacity: 1;
            color: white;
        }

        .profile-avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background-color: #0a58ca;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 70px;
            font-weight: bold;
            color: white;
            margin-bottom: 20px;
        }

        .sidebar h5 {
            margin-bottom: 5px;
        }
        
        /* Classe adicionada para controlar a margem e a exibição responsiva */
        .sidebar .reputation-text {
             margin-bottom: 40px;
        }

        .sidebar .nav-button {
            background-color: #f8f9fa;
            color: #343a40;
            border: none;
            border-radius: 20px;
            padding: 10px 20px;
            width: 90%;
            text-align: center;
            margin-bottom: 15px;
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }

        .sidebar .nav-button:hover {
            background-color: #e2e6ea;
        }

        .main-content {
            margin-left: 280px;
            padding: 40px;
        }
        
        /* Estilos de Ocorrência (opcionais para esta página, mas mantidos para consistência) */
        .occurrence-list .card {
            background-color: #ffffff;
            color: #212529;
            border-radius: 15px;
            border: 1px solid #dee2e6;
            margin-bottom: 20px;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .occurrence-list .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }

        .occurrence-list .card-body {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .occurrence-icon {
            font-size: 2.5rem;
            margin-right: 20px;
            color: #0d6efd;
        }

        .occurrence-details {
            flex-grow: 1;
        }

        .occurrence-id {
            font-size: 0.9rem;
            color: #6c757d;
        }

        /* === INÍCIO DAS REGRAS DE RESPONSIVIDADE (IDÊNTICAS AO DASHBOARD DO USUÁRIO) === */
        
        @media (max-width: 991.98px) {
            .d-flex {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                height: auto;
                position: relative; /* Remove a fixação */
                flex-direction: row; /* Itens em linha */
                justify-content: flex-start; /* Alinha à esquerda */
                align-items: center; 
                padding: 10px 15px;
            }
            
            .sidebar .profile-avatar {
                width: 50px;
                height: 50px;
                font-size: 24px;
                margin-bottom: 0;
            }
            
            .sidebar h5 {
                display: block; 
                margin-bottom: 0;
                font-size: 1.1rem; 
                margin-left: 10px; 
            }

            .sidebar .reputation-text {
                display: none; /* Oculta reputação no modo tablet */
                margin-bottom: 0;
            }

            .sidebar .nav-button {
                width: auto;
                padding: 8px 12px;
                margin-bottom: 0;
                margin-left: 10px;
                font-size: 0.9rem;
            }

            .sidebar .sidebar-footer {
                margin-top: 0;
                width: auto;
                padding-bottom: 0;
                margin-left: auto; /* Empurra o "Sair" para a direita */
            }

            .sidebar .logout-button span {
                display: none; /* Oculta texto "Sair" */
            }
            .sidebar .logout-button {
                padding: 5px;
                gap: 0;
                justify-content: center;
            }
            .sidebar .logout-button .bi {
                font-size: 1.3rem;
                margin-right: 0;
            }

            .main-content {
                margin-left: 0;
                padding: 20px;
            }
        }

        @media (max-width: 576px) {
            .sidebar {
                flex-wrap: wrap; /* Permite que os botões quebrem linha */
                justify-content: center;
                gap: 10px;
            }
            .sidebar h5 {
                 width: 100%; 
                 text-align: center; 
                 margin-left: 0;
                 margin-right: 0;
                 margin-bottom: 10px; 
                 order: -1; /* Coloca o nome no topo */
            }
            .sidebar .profile-avatar {
                display: none; /* Oculta avatar em telas muito pequenas */
            }
            .sidebar .reputation-text {
                display: block; /* Re-exibe a reputação */
                width: 100%;
                text-align: center;
                margin-bottom: 10px;
                order: -1; /* Coloca junto ao nome */
            }
            .sidebar .nav-button {
                width: 90%; /* Botões ocupam a largura toda */
                margin-left: 0;
                text-align: center;
            }
            .sidebar .sidebar-footer {
                width: 100%;
                text-align: center;
                margin-top: 10px;
                margin-left: 0; /* Reseta o margin-left */
            }
        }
        /* === FIM DAS REGRAS DE RESPONSIVIDADE === */

    </style>
</head>
<body>
<div class="d-flex">
    
    <div class="sidebar">
        <div class="profile-avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
        <h5>{{ explode(' ', auth()->user()->name)[0] }}</h5>

        @php
            $score = auth()->user()->reputation_score;

            if ($score <= 0) {
                $reputacaoTexto = 'Bloqueado';
                $colorClass = 'badge bg-danger'; // Vermelho
            } elseif ($score < 50) {
                $reputacaoTexto = 'Ruim';
                $colorClass = 'badge bg-danger';  // Vermelho
            } elseif ($score < 75) {
                $reputacaoTexto = 'Média';
                $colorClass = 'badge bg-warning text-dark'; // Amarelo
            } else {
                $reputacaoTexto = 'Boa';
                $colorClass = 'badge bg-success'; // Verde
            }
        @endphp
        <p class="text-white mb-4 reputation-text">Reputação: <span class="{{ $colorClass }}">{{ $reputacaoTexto }}</span></p>
        <a href="{{ url('/dashboard') }}" class="nav-button">Meus Relatos</a>
        <a href="{{ url('/perfil') }}" class="nav-button">Meu Perfil</a>
        <a href="{{ url('/ocorrencias/registrar') }}" class="nav-button active">Registrar nova ocorrência</a> <div class="sidebar-footer">
            <form action="{{ url('/logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-button">
                    <i class="bi bi-power" style="font-size: 1.5rem;"></i>
                    <span>Sair</span>
                </button>
            </form>
        </div>
    </div>

    <div class="main-content flex-grow-1">

        <div class="card shadow-sm">
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
                                @php
                                    $locais = [
                                        'Sala de Aula 01', 'Sala de Aula 02', 'Sala de Aula 03', 'Mini-sala 01',
                                        'Mini-auditório', 'Sala de Reuniões', 'Laboratório de Robótica',
                                        'Laboratório de Graduação 01', 'Laboratório de Graduação 02',
                                        'Laboratório de Graduação 03', 'Laboratório de Circuitos Elétricos e Eletrônicos',
                                        'Auditório CEPETEC', 'Hall', 'Secretaria', 'Outro'
                                    ];
                                @endphp
                                <select id="localizacaoSelect" name="localizacao" class="form-select" required>
                                    <option value="" disabled {{ old('localizacao') ? '' : 'selected' }}>Selecione um local...</option>
                                    @foreach($locais as $local)
                                        <option value="{{ $local }}" {{ old('localizacao') == $local ? 'selected' : '' }}>{{ $local == 'Outro' ? 'Outro (descreva a localização)' : $local }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center" id="outroLocalContainer" style="display: none;">
                            <div class="col-sm-4">
                                <label for="localizacao_outra" class="form-label fw-semibold">Qual Local?</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" id="localizacao_outra" name="localizacao_outra" class="form-control" placeholder="Descreva a localização exata" value="{{ old('localizacao_outra') }}">
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
                                    <option value="Outro" {{ old('categoria') == 'Outro' ? 'selected' : '' }}>Outro</option>
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
                    </fieldset>

                    <div class="row mt-4 pt-3 border-top">
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
        </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const localSelect = document.getElementById('localizacaoSelect');
        const outroLocalContainer = document.getElementById('outroLocalContainer');
        const outroLocalInput = document.getElementById('localizacao_outra');

        function toggleOutroLocal(selectedValue) {
            if (selectedValue === 'Outro') {
                outroLocalContainer.style.display = 'flex'; // 'flex' para manter o alinhamento do bootstrap
                outroLocalInput.required = true;
            } else {
                outroLocalContainer.style.display = 'none';
                outroLocalInput.required = false;
                outroLocalInput.value = ''; // Limpa o campo se o usuário mudar de ideia
            }
        }

        // Verifica o estado inicial (caso a página recarregue com erro de validação)
        toggleOutroLocal(localSelect.value);

        // Adiciona o listener para mudanças
        localSelect.addEventListener('change', function() {
            toggleOutroLocal(this.value);
        });
    });
</script>
</body>
</html>