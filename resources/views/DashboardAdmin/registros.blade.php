<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detalhes do Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
<style>
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

<div class="container-fluid bg-dark text-white text-center p-3 mb-4">
    <h2 class="mb-0">DETALHES DO RELATO</h2>
</div>

<div class="container">
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
                                    } elseif ($score < 55) {
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
                                <img src="{{ Storage::url($anexo->file_path) }}" alt="Anexo da ocorrência" class="img-thumbnail thumbnail-img thumbnail-clicavel" data-bs-toggle="modal" data-bs-target="#imagemModal">
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
                        <button type"submit" class="btn btn-danger">
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
                        <button class="btn btn-primary" type="submit"><i class="bi bi-check-circle"></i> Salvar Alterações</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif
</div> <div class="modal fade" id="historicoModal" tabindex="-1" aria-labelledby="historicoModalLabel" aria-hidden="true">
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


        // Lógica para EXPANDIR A IMAGEM NO MODAL (mantida)
        const thumbnails = document.querySelectorAll('.thumbnail-clicavel');
        const imagemNoModal = document.getElementById('imagemExpandida');
        thumbnails.forEach(function(thumb) {
            thumb.addEventListener('click', function() {
                const imgSrc = this.getAttribute('src');
                imagemNoModal.setAttribute('src', imgSrc);
            });
        });
    });
</script>
</body>
</html>
