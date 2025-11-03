<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios - Sistema de Recall</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Seu CSS (sem alterações) */
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
        .sidebar-footer { margin-top: auto; width: 100%; padding-bottom: 20px; }
        .logout-button { display: flex; align-items: center; justify-content: center; gap: 10px; background: none; border: none; color: white; font-size: 1.1rem; cursor: pointer; opacity: 0.8; transition: opacity 0.3s ease; text-decoration: none; width: 100%; }
        .logout-button:hover { opacity: 1; color: white; }
        .profile-avatar { width: 150px; height: 150px; border-radius: 50%; background-color: #495057; display: flex; align-items: center; justify-content: center; font-size: 70px; font-weight: bold; color: white; margin-bottom: 20px; }
        .sidebar h5 { margin-bottom: 40px; }
        .sidebar .nav-button { background-color: #f8f9fa; color: #343a40; border: none; border-radius: 20px; padding: 10px 20px; width: 80%; text-align: center; margin-bottom: 15px; text-decoration: none; font-weight: 500; transition: background-color 0.3s ease; }
        .sidebar .nav-button:hover { background-color: #e2e6ea; }
        .sidebar .nav-button.active { background-color: #dee2e6; font-weight: bold; }
        .main-content { margin-left: 280px; padding: 40px; }
        .kpi-card .card-title { font-size: 2.5rem; font-weight: 700; }
    </style>
</head>
<body>
<div class="d-flex">
    <div class="sidebar">
        <div class="profile-avatar">A</div>
        <h5>Administrador</h5>
        <a href="{{ url('/admin/dashboard') }}" class="nav-button">Ocorrências</a>
        <a href="{{ url('/admin/relatorios') }}" class="nav-button active">Relatórios</a>

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
        <h2 class="mb-4">Relatórios e Estatísticas</h2>

        <div class="card shadow-sm mb-4">
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="card kpi-card text-center shadow-sm h-100">
                    <div class="card-body">
                        <h3 class="card-title text-primary">{{ $totalOcorrencias ?? 0 }}</h3>
                        <p class="card-text text-muted">Total de Ocorrências</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card kpi-card text-center shadow-sm h-100">
                    <div class="card-body">
                        <h3 class="card-title text-success">{{ $ocorrenciasResolvidas ?? 0 }}</h3>
                        <p class="card-text text-muted">Resolvidas</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card kpi-card text-center shadow-sm h-100">
                    <div class="card-body">
                        <h3 class="card-title text-warning">{{ $ocorrenciasPendentes ?? 0 }}</h3>
                        <p class="card-text text-muted">Pendentes</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card kpi-card text-center shadow-sm h-100">
                    <div class="card-body">
                        <h3 class="card-title text-info" style="font-size: 1.8rem;">{{ $tempoMedioFormatado }}</h3>
                        <p class="card-text text-muted">Tempo Médio de Resolução</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header">
                        <h5>Ocorrências por Categoria</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header">
                        <h5>Top 5 Locais com Mais Relatos</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="locationChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Scripts dos gráficos (sem alterações)
    // --- Gráfico de Categorias (Pizza) ---
    const categoryData = @json($ocorrenciasPorCategoria ?? []);
    const categoryLabels = Object.keys(categoryData);
    const categoryCounts = Object.values(categoryData);

    const ctxCategory = document.getElementById('categoryChart').getContext('2d');
    new Chart(ctxCategory, {
        type: 'pie',
        data: {
            labels: categoryLabels.length > 0 ? categoryLabels : ['Nenhuma ocorrência registrada'],
            datasets: [{
                label: 'Ocorrências',
                data: categoryCounts.length > 0 ? categoryCounts : [1],
                backgroundColor: categoryLabels.length > 0 ? [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(153, 102, 255, 0.7)',
                    'rgba(255, 159, 64, 0.7)'
                ] : ['rgba(200, 200, 200, 0.7)'],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'top' } }
        }
    });

    // --- Gráfico de Locais (Barras) ---
    const locationData = @json($locaisMaisRelatos ?? []);
    const locationLabels = Object.keys(locationData);
    const locationCounts = Object.values(locationData);

    const ctxLocation = document.getElementById('locationChart').getContext('2d');
    new Chart(ctxLocation, {
        type: 'bar',
        data: {
            labels: locationLabels.length > 0 ? locationLabels : ['Nenhum local registrado'],
            datasets: [{
                label: 'Nº de Relatos',
                data: locationCounts.length > 0 ? locationCounts : [0],
                backgroundColor: 'rgba(75, 192, 192, 0.7)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true } },
            plugins: { legend: { display: false } }
        }
    });
</script>
</body>
</html>
