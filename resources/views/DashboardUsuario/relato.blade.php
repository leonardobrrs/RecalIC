<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Relato - Sistema de Recall</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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

        .thumbnail-img {
            width: 100%;
            height: 120px;
            object-fit: cover;
        }
        .thumbnail-clicavel {
            cursor: pointer;
            transition: transform 0.2s ease;
        }
        .thumbnail-clicavel:hover {
            transform: scale(1.05);
        }
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
        <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <h2 class="card-title mb-4">Detalhes da Ocorrência</h2>
                    <span class="badge bg-secondary fs-6">ID: {{ str_pad($ocorrencia->id, 4, '0', STR_PAD_LEFT) }}</span>
                </div>

                <p><strong>Status:</strong> <span class="badge {{ $ocorrencia->status == 'Resolvido' ? 'bg-success' : ($ocorrencia->status == 'Aberto' ? 'bg-danger' : 'bg-warning text-dark') }}">{{ $ocorrencia->status }}</span></p>
                <p><strong>Data de envio:</strong> {{ $ocorrencia->created_at->format('d/m/Y') }}</p>
                <hr>
                <p><strong>Localização:</strong> {{ $ocorrencia->localizacao }}</p>
                <p><strong>Categoria:</strong> {{ $ocorrencia->categoria }}</p>
                @if($ocorrencia->patrimonio_id)
                    <p><strong>Nº Patrimônio:</strong> {{ $ocorrencia->patrimonio_id }}</p>
                @endif
                <p><strong>Descrição:</strong> {{ $ocorrencia->descricao }}</p>
                <hr>

                <p class="mb-2"><strong>Anexos:</strong></p>
                <div class="row g-2" style="max-width: 400px;">
                    @forelse ($ocorrencia->anexos as $anexo)
                        <div class="col-4">
                            <img src="{{ asset('storage/' . $anexo->file_path) }}" alt="Anexo da ocorrência" class="img-thumbnail thumbnail-img thumbnail-clicavel" data-bs-toggle="modal" data-bs-target="#imagemModal">
                        </div>
                    @empty
                        <div class="col-12">
                            <p class="text-muted">Nenhum anexo enviado.</p>
                        </div>
                    @endforelse
                </div>

                <hr>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('ocorrencias.historico', $ocorrencia->id) }}" class="btn btn-primary">
                        <i class="bi bi-clock-history"></i> Ver histórico
                    </a>
                </div>
            </div>
        </div>

        @if ($ocorrencia->status == 'Resolvido' && !$ocorrencia->avaliacao)
            <div class="card mt-4 shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <h5 class="card-title mb-3">Avalie a resolução desta ocorrência</h5>
                    @error('avaliacao')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                    <form action="{{ route('ocorrencias.avaliar', $ocorrencia->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Sua nota (1 a 5):</label>
                            <div>
                                @for ($i = 1; $i <= 5; $i++)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="nota" id="nota{{ $i }}" value="{{ $i }}" {{ old('nota') == $i ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="nota{{ $i }}">{{ $i }}</label>
                                    </div>
                                @endfor
                            </div>
                            @error('nota')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="comentarioAvaliacao" class="form-label">Seu comentário (opcional):</label>
                            <textarea class="form-control @error('comentario') is-invalid @enderror" id="comentarioAvaliacao" name="comentario" rows="3">{{ old('comentario') }}</textarea>
                            @error('comentario')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-success">Enviar Avaliação</button>
                    </form>
                </div>
            </div>
        @elseif ($ocorrencia->avaliacao)
            <div class="card mt-4 bg-light border-success" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <h5 class="card-title mb-3 text-success"><i class="bi bi-check-circle-fill"></i> Sua Avaliação</h5>
                    <p class="mb-1"><strong>Nota:</strong> {{ $ocorrencia->avaliacao->nota }} / 5</p>
                    @if($ocorrencia->avaliacao->comentario)
                        <p class="mb-0"><strong>Comentário:</strong> "{{ $ocorrencia->avaliacao->comentario }}"</p>
                    @endif
                </div>
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ url('/dashboard') }}" class="btn btn-outline-secondary mt-4">
            <i class="bi bi-arrow-left"></i> Voltar para Meus Relatos
        </a>
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
    // Script para modal de imagem (sem alterações)
    document.addEventListener('DOMContentLoaded', function() {
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
