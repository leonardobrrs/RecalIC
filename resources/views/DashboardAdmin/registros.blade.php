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
            <div class="card-header fw-bold">Status Atual: Em Aberto</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-7">
                        <div class="row mb-3 align-items-center">
                            <div class="col-sm-4"><label class="form-label fw-semibold">Localização:</label></div>
                            <div class="col-sm-8"><p class="form-control-plaintext mb-0">Laboratório 01</p></div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <div class="col-sm-4"><label class="form-label fw-semibold">Categoria:</label></div>
                            <div class="col-sm-8"><p class="form-control-plaintext mb-0">Eletrônico</p></div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <div class="col-sm-4"><label class="form-label fw-semibold">Código do Patrimônio:</label></div>
                            <div class="col-sm-8"><p class="form-control-plaintext mb-0">123456789</p></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4"><label class="form-label fw-semibold">Descrição Detalhada:</label></div>
                            <div class="col-sm-8"><p class="form-control-plaintext mb-0" style="min-height: 80px;">O monitor do computador ao lado da janela na fileira 4 não está funcionando.</p></div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="row g-2">
                            <div class="col-6">
                                <img src="{{ asset('assets/foto1.jpg') }}" alt="Foto 1 do relato" class="img-thumbnail thumbnail-img thumbnail-clicavel" data-bs-toggle="modal" data-bs-target="#imagemModal">
                            </div>
                            <div class="col-6">
                                <img src="{{ asset('assets/foto2.jpg') }}" alt="Foto 2 do relato" class="img-thumbnail thumbnail-img thumbnail-clicavel" data-bs-toggle="modal" data-bs-target="#imagemModal">
                            </div>
                            <div class="col-6">
                                <img src="{{ asset('assets/foto3.jpg') }}" alt="Foto 3 do relato" class="img-thumbnail thumbnail-img thumbnail-clicavel" data-bs-toggle="modal" data-bs-target="#imagemModal">
                            </div>
                            <div class="col-6">
                                <img src="{{ asset('assets/foto4.jpg') }}" alt="Foto 4 do relato" class="img-thumbnail thumbnail-img thumbnail-clicavel" data-bs-toggle="modal" data-bs-target="#imagemModal">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4 border-top pt-3">
                    <div class="col d-flex justify-content-start gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#historicoModal">
                            <i class="bi bi-clock-history"></i> Exibir Histórico
                        </button>
                        <button id="excluirBtn" class="btn btn-danger" type="button">
                            <i class="bi bi-trash"></i> Excluir Ocorrência
                        </button>
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
                <form id="formAcoesAdmin">
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-3"><label class="form-label fw-semibold">Alterar Status:</label></div>
                        <div class="col-md-9">
                            <select class="form-select" required>
                                <option selected disabled value="">Selecione um novo status...</option>
                                <option value="analise">Em análise</option>
                                <option value="finalizado">Finalizado</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-3"><label class="form-label fw-semibold">Adicionar Comentário:</label></div>
                        <div class="col-md-9"><textarea class="form-control" placeholder="Adicione um comentário sobre a atualização..." rows="3" required></textarea></div>
                    </div>
                    <div class="row mt-4">
                        <div class="col d-flex justify-content-end">
                            <button class="btn btn-primary" type="submit"><i class="bi bi-check-circle"></i> Salvar Alterações</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="historicoModal" tabindex="-1" aria-labelledby="historicoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title" id="historicoModalLabel">Histórico da Ocorrência</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                <div class="modal-body"><ul class="list-group"><li class="list-group-item d-flex justify-content-between align-items-center"><div><span class="badge bg-success me-2">Resolvido</span>Técnico Fulano da Silva adicionou um comentário: "Reparo finalizado."</div><small class="text-muted">03/09/2025 - 14:30</small></li><li class="list-group-item d-flex justify-content-between align-items-center"><div><span class="badge bg-primary me-2">Em Análise</span>O status foi alterado.</div><small class="text-muted">02/09/2025 - 09:15</small></li><li class="list-group-item d-flex justify-content-between align-items-center"><div><span class="badge bg-secondary me-2">Aberto</span>Ocorrência criada pelo usuário.</div><small class="text-muted">01/09/2025 - 17:45</small></li></ul></div>
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

            // Lógica para o botão de EXCLUIR OCORRÊNCIA
            const excluirBtn = document.getElementById('excluirBtn');
            if(excluirBtn) {
                excluirBtn.addEventListener('click', function() {
                    const confirmacao = confirm('Tem certeza que deseja excluir esta ocorrência? Esta ação não pode ser desfeita.');
                    if (confirmacao) {
                        alert('A ocorrência foi excluída com sucesso!');
                        window.location.href = '{{ url('/admin/dashboard') }}';
                    }
                });
            }

            // Lógica para o formulário de AÇÕES ADMINISTRATIVAS
            const formAcoesAdmin = document.getElementById('formAcoesAdmin');
            if(formAcoesAdmin) {
                formAcoesAdmin.addEventListener('submit', function(event) {
                    event.preventDefault();
                    alert('ALTERAÇÕES SALVAS COM ÊXITO!');
                    setTimeout(function(){
                        window.location.href = '{{ url('/admin/dashboard') }}';
                    }, 1000);
                });
            }

            // Lógica para EXPANDIR A IMAGEM NO MODAL
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
