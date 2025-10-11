<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard do Usuário - Sistema de Recall</title>
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
    </style>
</head>
<body>
    <div class="d-flex">
        <div class="sidebar">
            <div class="profile-avatar">
                U
            </div>
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
            <h2 class="mb-4">Meus Relatos de Ocorrências</h2>

            <div class="occurrence-list">
                <a href="{{ url('/ocorrencias/relato') }}" class="text-decoration-none">
                    <div class="card">
                        <div class="card-body">
                            <i class="bi bi-list occurrence-icon"></i>
                            <div class="occurrence-details">
                                <strong>Status:</strong> Em análise <br>
                                <strong>Categoria:</strong> Infraestrutura <br>
                                <strong>Data de abertura:</strong> 22/08/2025
                            </div>
                            <div class="occurrence-id">ID: 0002</div>
                        </div>
                    </div>
                </a>

                <a href="{{ url('/ocorrencias/relato') }}" class="text-decoration-none">
                    <div class="card">
                        <div class="card-body">
                            <i class="bi bi-list occurrence-icon"></i>
                            <div class="occurrence-details">
                                <strong>Status:</strong> Resolvido <br>
                                <strong>Categoria:</strong> Móveis <br>
                                <strong>Data de abertura:</strong> 01/07/2025
                            </div>
                            <div class="occurrence-id">ID: 0005</div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
