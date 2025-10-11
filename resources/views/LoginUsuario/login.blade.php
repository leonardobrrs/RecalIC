<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <title>Sistema de Recall</title>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center py-4">
                        <h2 class="mb-0"><i class="bi bi-shield-check"></i> Sistema de Recall</h2>
                    </div>
                    <div class="card-body">
                        <div class="mx-auto text-center p-3">
                            <img src="https://ufal.br/ufal/comunicacao/identidade-visual/brasao/somente-imagem/brasao-ufal.png" height="168.78" width="97.04" alt="Brasão UFAL">
                        </div>
                        <form>
                            <div class="form-floating mb-3 mx-auto" style="width: 300px;">
                                <input type="text" class="form-control" id="floatingUser" placeholder="Usuario" required>
                                <label for="floatingUser">Usuario</label>
                            </div>
                            <div class="form-floating mb-3 mx-auto" style="width: 300px;">
                                <input type="password" class="form-control" id="floatingPassword" placeholder="Senha" required>
                                <label for="floatingPassword">Senha</label>
                            </div>
                            <div class="text-center mb-3">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Lembrar-me
                                    </label>
                                </div>
                            </div>
                            <div class="d-grid col-2 mx-auto">
                                <button class="btn btn-light rounded-pill shadow-sm border-light-subtle" type="submit">Entrar</button>
                            </div>
                            <div class="my-4 text-center">
                                <span class="text-muted">ou</span>
                            </div>
                            <div class="d-flex justify-content-between px-3">
                                <a href="{{ url('/admin/login') }}" class="btn btn-outline-secondary mb-3">
                                    <i class="bi bi-person-check"></i> Entrada Administrativa
                                </a>
                                <a href="{{ url('/cadastro') }}" class="btn btn-outline-primary mb-3">
                                    <i class="bi bi-person-plus"></i> Criar Conta
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <p class="text-muted">Sistema para registro de ocorrências de avarias</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Adicionando funcionalidade ao formulário de login
        document.querySelector('form').addEventListener('submit', function(event) {
            event.preventDefault();

            const usuario = document.getElementById('floatingUser').value;
            const senha = document.getElementById('floatingPassword').value;

            if (usuario && senha) {
                // Redirecionamento após login (substitua pela URL correta)
                setTimeout(function() {
                    window.location.href = '{{ url('/dashboard') }}';
                }, 1000);
            }
        });
    </script>
</body>
</html>
