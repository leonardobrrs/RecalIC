<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico do Relato - Sistema de Recall</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <style>
        body, html { height: 100%; margin: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8f9fa; }
        .sidebar { background-color: #0d6efd; color: white; padding: 20px; display: flex; flex-direction: column; align-items: center; height: 100vh; position: fixed; width: 280px; }
        .sidebar-footer { margin-top: auto; width: 100%; padding-bottom: 20px; }
        .logout-button { display: flex; align-items: center; justify-content: center; gap: 10px; background: none; border: none; color: white; font-size: 1.1rem; cursor: pointer; opacity: 0.8; transition: opacity 0.3s ease; text-decoration: none; width: 100%; }
        .logout-button:hover { opacity: 1; color: white; }
        .profile-avatar { width: 150px; height: 150px; border-radius: 50%; background-color: #0a58ca; display: flex; align-items: center; justify-content: center; font-size: 70px; font-weight: bold; color: white; margin-bottom: 20px; }
        .sidebar h5 { margin-bottom: 40px; }
        .sidebar .nav-button { background-color: #f8f9fa; color: #343a40; border: none; border-radius: 20px; padding: 10px 20px; width: 90%; text-align: center; margin-bottom: 15px; text-decoration: none; font-weight: 500; transition: background-color 0.3s ease; }
        .sidebar .nav-button:hover { background-color: #e2e6ea; }
        .main-content { margin-left: 280px; padding: 40px; }
    </style>
</head>
<body>
    <div class="d-flex">
        <div class="sidebar">
            <div class="profile-avatar">U</div>
            <h5>Usuário</h5>
            <a href="{{ url('/dashboard') }}" class="nav-button">Meus Relatos</a>
            <a href="{{ url('/ocorrencias/registrar') }}" class="nav-button">Registrar nova ocorrência</a>
            <div class="sidebar-footer">
                <a href="{{ url('/') }}" class="logout-button">
                    <i class="bi bi-power" style="font-size: 1.5rem;"></i>
                    <span>Sair</span>
                </a>
            </div>
        </div>

        <div class="main-content flex-grow-1">
            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <h2 class="card-title mb-4">Histórico da Ocorrência (ID: 0002)</h2>

                    <p><strong>Status:</strong></p>

                    <div class="progress mb-4" style="height: 30px; font-size: 1rem;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 33.33%" aria-valuenow="33.33" aria-valuemin="0" aria-valuemax="100">
                            Aberto
                        </div>
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 33.33%" aria-valuenow="33.33" aria-valuemin="0" aria-valuemax="100">
                            Em Análise
                        </div>
                    </div>

                    <p><strong>Atualizações:</strong></p>
                    <ul class="list-group">
                        <li class="list-group-item"><strong>22/08/2025:</strong> Status alterado para "Em Análise".</li>
                        <li class="list-group-item"><strong>22/08/2025:</strong> Relato recebido e registrado com o status "Aberto".</li>
                    </ul>
                </div>
            </div>
            <a href="{{ url('/ocorrencias/relato') }}" class="btn btn-secondary mt-4">
                <i class="bi bi-arrow-left"></i> Voltar para os Detalhes
            </a>
        </div>
    </div>
</body>
</html>
