<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detalhes do Relato</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
<style>
    /* --- ESTILOS ADICIONADOS DO DASHBOARDADM --- */
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
        position: fixed; /* Fixa a sidebar */
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
        margin-left: 280px; /* Largura da sidebar */
        padding: 40px;
        width: calc(100% - 280px); /* Garante que o conteúdo principal ocupe o resto */
    }

    /* --- ESTILOS ORIGINAIS DE REGISTROS.BLADE.PHP --- */
    .form-control-plaintext {
        border: 1px solid #dee2e6;
        background-color: #e9ecef;
        padding: .375rem .75rem;
        border-radius: .375rem;
    }
    .thumbnail-img {
        width: 100%;
        height: 120px;
        object-fit: cover;
    }
    .thumbnail-clicavel {
        cursor: pointer;
    }
</style>

<div class="d-flex">

    <div class="sidebar">
        <div class="profile-avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
        <h5>{{ explode(' ', auth()->user()->name)[0] }}</h5>
        <a href="{{ url('/admin/dashboard') }}" class="nav-button">Ocorrências</a>
        <a href="{{ url('/admin/usuarios') }}" class="nav-button">Usuários</a>
        <a href="{{ url('/admin/relatorios') }}" class="nav-button">Relatórios</a>
        <div class="sidebar-footer">
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-button">
                    <i class="bi bi-power" style="font-size: 1.5rem;"></i>
                    <span>Sair</span>
                </button>
            </form>
        </div>
    </div>
    <div class="main-content flex-grow-1">

        <div class="container">
            <h2 class="mb-4">Detalhes do Relato</h2>
            <div class="card mb-4">
                <div class="card-header fw-bold">Status Atual: {{ $ocorrencia->status }}</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="row mb-3 align-items-center">
                                <div class="col-sm-4"><label class="form-label fw-semibold">Localização:</label></div>
                                <div class="col-sm-8"><p class="form-control-plaintext mb-0">{{ $ocorrencia->localizacao }}</p></div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <div class="col-sm-4"><label class="form-label fw-semibold">Categoria:</label></div>
                                <div class="col-sm-8"><p class="form-control-plaintext mb-0">{{ $ocorrencia->categoria }}</p></div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <div class="col-sm-4"><label class="form-label fw-semibold">Código do Patrimônio:</label></div>
                                <div class="col-sm-8"><p class="form-control-plaintext mb-0">{{ $ocorrencia->patrimonio_id ?? 'N/A' }}</p></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4"><label class="form-label fw-semibold">Descrição Detalhada:</label></div>
                                <div class="col-sm-8"><p class="form-control-plaintext mb-0" style="min-height: 80px;">{{ $ocorrencia->descricao }}</p></div>
                            </div>

                            <hr>
                            <div class="row mb-3 align-items-center">
                                <div class="col-sm-4"><label class="form-label fw-semibold">Relator:</label></div>
                                <div class="col-sm-8"><p class="form-control-plaintext mb-0">
                                        {{ $ocorrencia->relator->name ?? 'Usuário Deletado' }}

                                        @php
                                            $score = $ocorrencia->relator->reputation_score ?? 100;

                                            if ($score <= 0) {
                                                $reputacaoTexto = 'Bloqueado';
                                                $colorClass = 'text-danger fw-bold';
                                            } elseif ($score < 50) {
                                                $reputacaoTexto = 'Ruim';
                                                $colorClass = 'text-danger';
                                            } elseif ($score < 75) {
                                                $reputacaoTexto = 'Média';
                                                $colorClass = 'text-warning';
                                            } else {
                                                $reputacaoTexto = 'Boa';
                                                $colorClass = 'text-success';
                                            }
                                        @endphp

                                        (Reputação: <strong class="{{ $colorClass }}">{{ $reputacaoTexto }}</strong>)
                                    </p></div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="row g-2">
                                @forelse ($ocorrencia->anexos as $anexo)
                                    <div class="col-6">
                                        <img src="{{ asset('storage/' . $anexo->file_path) }}" alt="Anexo da ocorrência" class="img-thumbnail thumbnail-img thumbnail-clicavel" data-bs-toggle="modal" data-bs-target="#imagemModal">
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <p class="text-muted">Nenhum anexo enviado para esta ocorrência.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4 border-top pt-3">
                        <div class="col d-flex justify-content-start gap-2">
                            <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#historicoModal">
                                <i class="bi bi-clock-history"></i> Exibir Histórico
                            </button>
                            <form action="{{ route('admin.ocorrencias.destroy', $ocorrencia->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir esta ocorrência? Esta ação não pode ser desfeita.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-trash"></i> Excluir Ocorrência
                                </button>
                            </form>
                            @if($ocorrencia->relator && $ocorrencia->relator->reputation_score > 0)
                                <form action="{{ route('admin.user.block', $ocorrencia->relator->id) }}" method="POST" onsubmit="return confirm('Tem a certeza de que deseja bloquear este utilizador? A sua reputação será definida como 0.');">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger">
                                        <i class="bi bi-person-x-fill"></i> Bloquear Relator
                                    </button>
                                </form>
                            @endif
                        </div>
                        <div class="col d-flex justify-content-end">
                            <button type="button" class="btn btn-outline-secondary" onclick="window.location.href='{{ url('/admin/dashboard') }}'">
                                <i class="bi bi-arrow-left"></i> Voltar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header fw-bold">Ações Administrativas</div>
                <div class="card-body">
                    <form id="formAcoesAdmin" action="{{ route('admin.ocorrencias.updateStatus', $ocorrencia->id) }}" method="POST">
                        @csrf @method('PUT') <div class="row mb-3 align-items-center">
                            <div class="col-md-3"><label class="form-label fw-semibold">Alterar Status:</label></div>
                            <div class="col-md-9">
                                <select class="form-select" name="status" required>
                                    <option disabled value="">Selecione um novo status...</option> <option value="Aberto" {{ $ocorrencia->status == 'Aberto' ? 'selected' : '' }}>Aberto</option>
                                    <option value="Em Análise" {{ $ocorrencia->status == 'Em Análise' ? 'selected' : '' }}>Em Análise</option>
                                    <option value="Resolvido" {{ $ocorrencia->status == 'Resolvido' ? 'selected' : '' }}>Resolvido</option>
                                    <option value="Inválido" {{ $ocorrencia->status == 'Inválido' ? 'selected' : '' }}>Inválido</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-3"><label class="form-label fw-semibold">Adicionar Comentário:</label></div>
                            <div class="col-md-9">
                                <textarea class="form-control" name="comentario" placeholder="Adicione um comentário sobre a atualização (opcional)..." rows="3"></textarea>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col d-flex justify-content-end">
                                <button class="btn btn-dark" type="submit"><i class="bi bi-check-circle"></i> Salvar Alterações</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @if ($ocorrencia->status == 'Resolvido')
                
                @if (!$relatorJaAvaliado)
                    <div class="card mb-4">
                        <div class="card-header fw-bold">
                            Avaliar Relator (Opcional)
                        </div>
                        <div class="card-body">
                            <p>Após resolver esta ocorrência, você pode avaliar a qualidade do relato enviado pelo usuário <strong>{{ $ocorrencia->relator->name ?? 'Usuário Deletado' }}</strong>. Esta nota atualizará o "Score de Reputação" dele.</p>
                    
                            <form action="{{ route('admin.ocorrencias.avaliarRelator', $ocorrencia->id) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="nota" class="form-label fw-bold">Nota (1-5)</label>
                                    <select name="nota" id="nota" class="form-select" required 
                                            @if(!$ocorrencia->relator || $ocorrencia->relator->id === auth()->id()) disabled @endif>
                                        <option value="" disabled selected>Selecione uma nota...</option>
                                        <option value="1">1 - Péssimo (Relato inútil/spam. Define Score 0)</option>
                                        <option value="2">2 - Ruim (Relato com pouca informação. Define Score 25)</option>
                                        <option value="3">3 - Regular (Relato mediano. Define Score 50)</option>
                                        <option value="4">4 - Bom (Relato útil e claro. Define Score 75)</option>
                                        <option value="5">5 - Excelente (Relato perfeito e detalhado. Define Score 100)</option>
                                    </select>
                                </div>

                                @if(!$ocorrencia->relator)
                                    <div class="alert alert-warning small">O usuário original deste relato foi excluído. Não é possível avaliá-lo.</div>
                                @elseif($ocorrencia->relator->id === auth()->id())
                                    <div class="alert alert-warning small">Você não pode avaliar um relato feito por você mesmo.</div>
                                @else
                                    <button type="submit" class="btn btn-dark">
                                        <i class="bi bi-star-fill me-1"></i>
                                        Salvar Avaliação do Relator
                                    </button>
                                @endif
                            </form>
                        </div>
                    </div>
                @else
                    <div class="card mb-4">
                        <div class="card-header fw-bold">
                            Relator Avaliado
                        </div>
                        <div class="card-body">
                            <p class="text-success fw-bold">
                                <i class="bi bi-check-circle-fill me-1"></i>
                                Você já avaliou o relator para esta ocorrência.
                            </p>
                            @php
                                // Encontra o registro da avaliação no histórico para exibir os detalhes
                                $avaliacaoLog = $ocorrencia->historico->firstWhere('status_novo', 'Relator Avaliado');
                            @endphp
                            @if ($avaliacaoLog && $avaliacaoLog->comentario)
                                <p class="mb-0"><strong>Detalhes da Avaliação:</strong> {{ $avaliacaoLog->comentario }}</p>
                                <small class="text-muted">Avaliado por: {{ $avaliacaoLog->admin->name ?? 'Admin' }} em {{ $avaliacaoLog->created_at->format('d/m/Y H:i') }}</small>
                            @endif
                        </div>
                    </div>
                @endif
            @endif
            @if ($ocorrencia->status == 'Resolvido' || $ocorrencia->status == 'Fechado')
                <div class="card mb-4">
                    <div class="card-header fw-bold">Avaliação do Usuário</div>
                    <div class="card-body">
                        @if ($ocorrencia->avaliacao)
                            <div class="row mb-3 align-items-center">
                                <div class="col-sm-4"><label class="form-label fw-semibold">Nota:</label></div>
                                <div class="col-sm-8"><p class="form-control-plaintext mb-0 fs-5"><strong>{{ $ocorrencia->avaliacao->nota }} / 5</strong></p></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4"><label class="form-label fw-semibold">Comentário:</label></div>
                                <div class="col-sm-8"><p class="form-control-plaintext mb-0" style="min-height: 60px;">{{ $ocorrencia->avaliacao->comentario ?? 'Nenhum comentário fornecido.' }}</p></div>
                            </div>
                        @else
                            <p class="text-muted">Esta ocorrência foi resolvida, mas o usuário ainda não forneceu uma avaliação.</p>
                        @endif
                    </div>
                </div>
            @endif
            
            @if(session('success'))
                <div class="alert alert-success mt-3">
                    {{ session('success') }}
                </div>
            @endif
        </div> 
        </div>
    </div>
<div class="modal fade" id="historicoModal" tabindex="-1" aria-labelledby="historicoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title" id="historicoModalLabel">Histórico da Ocorrência</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
            <div class="modal-body">
                <ul class="list-group">
                    @forelse ($ocorrencia->historico->sortByDesc('created_at') as $historico)
                        <li class="list-group-item">
                            <div>
                                <span class="badge bg-primary me-2">{{ $historico->status_novo }}</span>
                                @if ($historico->comentario)
                                    <strong>{{ $historico->admin->name ?? 'Admin' }}</strong> comentou: "{{ $historico->comentario }}"
                                @else
                                    O status foi alterado por <strong>{{ $historico->admin->name ?? 'Admin' }}</strong>.
                                @endif
                            </div>
                            <small class="text-muted">{{ $historico->created_at->format('d/m/Y - H:i') }}</small>
                        </li>
                    @empty
                        <li class="list-group-item">Nenhum histórico de status para esta ocorrência.</li>
                    @endforelse
                </ul>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button></div>
        </div>
    </div>
</div>

<div class="modal fade" id="imagemModal" tabindex="-1" aria-labelledby="imagemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body p-0">
                <img id="imagemExpandida" src="" class="img-fluid w-100" alt="Imagem expandida do relato">
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // CORREÇÃO: A lógica 'confirm' já está no 'onsubmit' dos formulários,
        // então o JavaScript extra para 'excluirBtn' não é necessário e estava incompleto.
        // A lógica 'onsubmit' no HTML já cuida disso.

        // Lógica para EXPANDIR A IMAGEM NO MODAL (mantida)
        const thumbnails = document.querySelectorAll('.thumbnail-clicavel');
        const imagemNoModal = document.getElementById('imagemExpandida');
        
        if (imagemNoModal) {
            thumbnails.forEach(function(thumb) {
                thumb.addEventListener('click', function() {
                    const imgSrc = this.getAttribute('src');
                    imagemNoModal.setAttribute('src', imgSrc);
                });
            });
        }
    });
</script>
</body>
</html>