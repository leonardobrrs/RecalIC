<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Administrativo - Sistema de Recall</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        .sidebar {
            background-color: #212529;
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
            background-color: #495057;
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
            width: 80%;
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

        .filter-area {
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 40px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
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
            color: #495057;
        }

        .occurrence-details {
            flex-grow: 1;
        }

        .occurrence-id {
            font-size: 0.9rem;
            color: #6c757d;
        }

        .sort-toggle-btn {
            background: none;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 2px 8px;
            color: #6c757d;
        }
        .sort-toggle-btn .bi {
            font-size: 0.8rem;
            transition: color 0.2s ease-in-out;
        }
        .sort-toggle-btn .bi.active-sort {
            color: #000;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="d-flex">
    <div class="sidebar">
        <div class="profile-avatar">A</div>
        <h5>Administrador</h5>
        <a href="{{ url('/admin/dashboard') }}" class="nav-button">Ocorrências</a>
        <a href="{{ url('/admin/relatorios') }}" class="nav-button">Relatórios</a>
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
        <div class="filter-area">
            <h4 class="mb-4 text-center text-dark">Área de Filtros</h4>
            <form id="filterForm">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="statusFilter" class="form-label text-dark">Status</label>
                        <select id="statusFilter" class="form-select">
                            <option value="">Selecione...</option>
                            <option value="Aberto">Em Aberto</option>
                            <option value="Em análise">Em Análise</option>
                            <option value="Resolvido">Resolvido</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="categoryFilter" class="form-label text-dark">Categoria</label>
                        <select id="categoryFilter" class="form-select">
                            <option value="">Selecione...</option>
                            <option value="Equipamentos">Equipamentos</option>
                            <option value="Infraestrutura">Infraestrutura</option>
                            <option value="Eletrônicos">Eletrônicos</option>
                            <option value="Patrimônio">Patrimônio</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="searchField" class="form-label text-dark">Campo de busca</label>
                        <input type="text" class="form-control" id="searchField" placeholder="Pesquisar por ID, nome...">
                    </div>
                    <div class="col-12 text-end mt-4">
                        <button type="submit" class="btn btn-dark">Filtrar</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Ocorrências</h3>
            <button id="sort-toggle-btn" class="sort-toggle-btn" title="Alternar Ordem por Data">
                <i id="sort-asc" class="bi bi-arrow-up"></i>
                <i id="sort-desc" class="bi bi-arrow-down active-sort"></i>
            </button>
        </div>

        <div class="occurrence-list">
            @forelse ($ocorrencias as $ocorrencia)
                <a href="{{ url('/admin/ocorrencias/' . $ocorrencia->id) }}" class="text-decoration-none">
                    <div class="card occurrence-card">
                        <div class="card-body">
                            <i class="bi bi-list occurrence-icon"></i>
                            <div class="occurrence-details">
                                <strong>Status:</strong> <span class="status">{{ $ocorrencia->status }}</span> <br>
                                <strong>Categoria:</strong> <span class="category">{{ $ocorrencia->categoria }}</span> <br>
                                <strong>Data de abertura:</strong> <span class="date">{{ $ocorrencia->created_at->format('d/m/Y') }}</span> <br>
                                <strong>Relatado por:</strong> {{ $ocorrencia->relator->name ?? 'Usuário anônimo' }}
                            </div>
                            <div class="occurrence-id">ID: {{ str_pad($ocorrencia->id, 4, '0', STR_PAD_LEFT) }}</div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="alert alert-info">
                    Nenhuma ocorrência registrada no sistema ainda.
                </div>
            @endforelse
        </div>
    </div>
</div>

<script>
    // O JavaScript para filtros e ordenação continua o mesmo.
    // Ele continuará funcionando com os cards gerados dinamicamente.
    document.addEventListener('DOMContentLoaded', () => {
        // ... (seu código JS existente)
    });
</script>
</body>
</html>
