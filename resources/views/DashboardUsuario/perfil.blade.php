<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil - Sistema de Recall</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <style>
        /* Estilos do seu dashboard.blade.php - Copiados 1-para-1 */
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
        /* (Fim dos estilos copiados) */

        /* Estilos adicionais para o card de perfil */
        .card {
            border-radius: 15px;
            border: 1px solid #dee2e6;
        }
        .form-label {
            font-weight: 500;
            color: #6c757d; /* Cor de texto silenciada para labels */
        }
        .fs-5 {
             font-weight: 500;
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
        <a href="{{ url('/perfil') }}" class="nav-button active">Meu Perfil</a> <a href="{{ url('/ocorrencias/registrar') }}" class="nav-button">Registrar nova ocorrência</a>
        <div class="sidebar-footer">
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
        <h2 class="mb-4">Meu Perfil</h2>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Opa!</strong> Havia alguns problemas com seus dados:
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="card shadow-sm" id="viewMode">
            <div class="card-body p-4">
                <div class="mb-3">
                    <label class="form-label">Nome</label>
                    <p class="fs-5 text-dark">{{ $user->name }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label">E-mail</label>
                    <p class="fs-5 text-dark">{{ $user->email }}</p>
                </div>
                <button id="btnAlterar" class="btn btn-primary mt-3">
                    <i class="bi bi-pencil-square me-2"></i>Alterar Dados
                </button>
            </div>
        </div>

        <div class="card shadow-sm" id="editMode" style="display: none;">
            <div class="card-body p-4">
                <form action="{{ route('perfil.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Nome:</label>
                        <input type="text" id="name" name="name" class="form-control form-control-lg" value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail:</label>
                        <input type="email" id="email" name="email" class="form-control form-control-lg" value="{{ old('email', $user->email) }}" required>
                    </div>

                    <hr class="my-4">
                    <p class="fs-6"><strong>Alterar Senha</strong><br>(Deixe em branco para não alterar)<br></p>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Nova Senha:</label>
                            <input type="password" id="password" name="password" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Confirmar Nova Senha:</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle me-2"></i>Salvar Alterações
                        </button>
                        <button type="button" id="btnCancelar" class="btn btn-secondary">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
        </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const viewMode = document.getElementById('viewMode');
    const editMode = document.getElementById('editMode');
    const btnAlterar = document.getElementById('btnAlterar');
    const btnCancelar = document.getElementById('btnCancelar');

    btnAlterar.addEventListener('click', function() {
        viewMode.style.display = 'none';
        editMode.style.display = 'block';
    });

    btnCancelar.addEventListener('click', function() {
        viewMode.style.display = 'block';
        editMode.style.display = 'none';
    });
    
    // Se a página for carregada com erros de validação (após um envio falho),
    // o formulário de edição já é exibido.
    @if ($errors->any())
        viewMode.style.display = 'none';
        editMode.style.display = 'block';
    @endif
</script>

</body>
</html>