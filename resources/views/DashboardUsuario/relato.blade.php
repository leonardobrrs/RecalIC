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
        .sidebar h5 { margin-bottom: 40px; }
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
                    <div class="d-flex justify-content-between align-items-start">
                        <h2 class="card-title mb-4">Detalhes da Ocorrência</h2>
                        <span class="badge bg-secondary fs-6">ID: 0002</span>
                    </div>

                    <p><strong>Status:</strong> <span class="badge bg-warning text-dark">Em análise</span></p>
                    <p><strong>Data de envio:</strong> 22/08/2025</p>
                    <hr>
                    <p><strong>Localização:</strong> Auditório CEPETEC</p>
                    <p><strong>Categoria:</strong> Infraestrutura</p>
                    <p><strong>Descrição:</strong> Uma das cadeiras da fileira B está com o encosto quebrado.</p>
                    <hr>

                    <p class="mb-2"><strong>Anexos:</strong></p>
                    <div class="row g-2" style="max-width: 400px;">
                        <div class="col-4">
                            <img src="{{ asset('assets/foto1.jpg') }}" alt="Foto 1 do relato" class="img-thumbnail thumbnail-img thumbnail-clicavel" data-bs-toggle="modal" data-bs-target="#imagemModal">
                        </div>
                        <div class="col-4">
                            <img src="{{ asset('assets/foto2.jpg') }}" alt="Foto 2 do relato" class="img-thumbnail thumbnail-img thumbnail-clicavel" data-bs-toggle="modal" data-bs-target="#imagemModal">
                        </div>
                        <div class="col-4">
                            <img src="{{ asset('assets/foto3.jpg') }}" alt="Foto 3 do relato" class="img-thumbnail thumbnail-img thumbnail-clicavel" data-bs-toggle="modal" data-bs-target="#imagemModal">
                        </div>
                    </div>

                    <hr>
                    <div class="d-flex justify-content-end">
                         <a href="{{ url('/ocorrencias/historico') }}" class="btn btn-primary">
                            <i class="bi bi-clock-history"></i> Ver histórico
                        </a>
                    </div>
                </div>
            </div>
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
