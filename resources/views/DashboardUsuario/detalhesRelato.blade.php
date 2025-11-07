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
        .sidebar h5 { margin-bottom: 0.5rem; }
        .sidebar .nav-button { background-color: #f8f9fa; color: #343a40; border: none; border-radius: 20px; padding: 10px 20px; width: 90%; text-align: center; margin-bottom: 15px; text-decoration: none; font-weight: 500; transition: background-color 0.3s ease; }
        .sidebar .nav-button:hover { background-color: #e2e6ea; }
        .main-content { margin-left: 280px; padding: 40px; }
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
        <p class="text-white" style="margin-bottom: 40px;">Reputação: <span class="{{ $colorClass }}">{{ $reputacaoTexto }}</span></p>
        <a href="{{ url('/dashboard') }}" class="nav-button">Meus Relatos</a>
        <a href="{{ url('/perfil') }}" class="nav-button">Meu Perfil</a>
        <a href="{{ url('/ocorrencias/registrar') }}" class="nav-button">Registrar nova ocorrência</a>
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
        <div class="card shadow-sm border-0" style="border-radius: 15px;">
            <div class="card-body p-4">
                <h2 class="card-title mb-4">Histórico da Ocorrência (ID: {{ str_pad($ocorrencia->id, 4, '0', STR_PAD_LEFT) }})</h2>

                <p><strong>Status:</strong></p>

                @php
                    $status = $ocorrencia->status;
                    $progressWidth = 0;
                    $progressClass = 'bg-danger';
                    if ($status == 'Aberto') {
                        $progressWidth = 33;
                        $progressClass = 'bg-primary';
                    } elseif ($status == 'Em Análise') {
                        $progressWidth = 66;
                        $progressClass = 'bg-warning';
                    } elseif (in_array($status, ['Resolvido'])) {
                        $progressWidth = 100;
                        $progressClass = 'bg-success';
                    } elseif ($status == 'Inválido') {
                        $progressWidth = 100;
                        $progressClass = 'bg-danger';
                    }
                @endphp
                <div class="progress mb-4" style="height: 30px; font-size: 1rem;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated {{ $progressClass }}" role="progressbar" style="width: {{ $progressWidth }}%" aria-valuenow="{{ $progressWidth }}" aria-valuemin="0" aria-valuemax="100">
                        {{ $status }}
                    </div>
                </div>

                <p><strong>Atualizações:</strong></p>
                <ul class="list-group">
                    @forelse ($ocorrencia->historico->sortByDesc('created_at') as $historico)
                        <li class="list-group-item">
                            <strong>{{ $historico->created_at->format('d/m/Y - H:i') }}:</strong>
                            @if ($historico->comentario)
                                @if ($historico->status_novo == 'Relator Avaliado')
                                    O administrador avaliou a qualidade do seu relato.
                                @else
                                    O status foi alterado para "{{ $historico->status_novo }}" pelo administrador com o seguinte comentário: <em>"{{ $historico->comentario }}"</em>
                                @endif
                            @else
                                Status alterado para "{{ $historico->status_novo }}".
                            @endif
                        </li>
                    @empty
                        <li class="list-group-item">Nenhum histórico de atualizações para esta ocorrência.</li>
                    @endforelse
                    <li class="list-group-item"><strong>{{ $ocorrencia->created_at->format('d/m/Y - H:i') }}:</strong> Relato recebido e registrado com o status "Aberto".</li>
                </ul>
            </div>
        </div>

        @if (isset($adminFeedback) && $adminFeedback)
        <div class="card shadow-sm border-0 mt-4" style="border-radius: 15px;">
            <div class="card-header bg-primary text-white" style="border-radius: 15px 15px 0 0;">
                <h5 class="mb-0">
                    <i class="bi bi-person-check-fill me-2"></i>
                    Feedback do Administrador sobre o seu Relato
                </h5>
            </div>
            <div class="card-body">
                <p>O administrador avaliou a qualidade do seu relato. Este feedback influencia sua reputação na plataforma.</p>
                
                <div class="alert alert-info">
                    <p class="fw-bold mb-1">Comentário da Administração:</p>
                    <p class="mb-0"><em>"{{ $adminFeedback->comentario }}"</em></p>
                </div>
                
                <small class="text-muted">
                    Avaliado por: {{ $adminFeedback->admin->name ?? 'Admin' }} 
                    em {{ $adminFeedback->created_at->format('d/m/Y H:i') }}
                </small>
            </div>
        </div>
        @endif
        <a href="{{ route('ocorrencias.show', $ocorrencia->id) }}" class="btn btn-secondary mt-4">
            <i class="bi bi-arrow-left"></i> Voltar para os Detalhes
        </a>
    </div>
</div>
</body>
</html>