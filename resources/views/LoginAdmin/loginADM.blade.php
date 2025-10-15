<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <title>Administrativo - RecalIC</title>
    <title>Administrativo - RecalIC</title>
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow">
                <div class="card-header bg-dark text-white text-center py-4">
                    <h2 class="mb-0"><i class="bi bi-shield-check"></i> RecalIC</h2>
                    <h6 class="mb-0" style="color:#eb3434;">Entrada Administrativa</h6>
                </div>
                <div class="card-body">
                    <div class="mx-auto text-center p-3">
                        <img src="https://ufal.br/ufal/comunicacao/identidade-visual/brasao/somente-imagem/brasao-ufal.png" height="168.78" width="97.04" alt="Brasão UFAL">
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger mx-auto" style="width: 300px;">
                            {{ $errors->first('email') }}
                        </div>
                    @endif

                    <form action="{{ url('/admin/login') }}" method="POST">
                        @csrf
                        <div class="form-floating mb-3 mx-auto" style="width: 300px;">
                            <input type="email" class="form-control" id="floatingUser" name="email" placeholder="E-mail" value="{{ old('email') }}" required>
                            <label for="floatingUser">E-mail</label>
                        </div>
                        <div class="form-floating mb-3 mx-auto" style="width: 300px;">
                            <input type="password" class="form-control" id="floatingPassword" name="senha" placeholder="Senha" required>
                            <label for="floatingPassword">Senha</label>
                        </div>
                        <div class="text-center mb-3">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="remember" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">Lembrar-me</label>
                            </div>
                        </div>
                        <div class="d-grid col-2 mx-auto">
                            <button class="btn btn-dark" type="submit">Entrar</button>
                        </div>
                        <div class="my-4 text-center">
                            <span class="text-muted">ou</span>
                        </div>
                        <div class="d-flex justify-content-between px-3">
                            <a href="{{ url('/') }}" class="btn btn-outline-secondary mb-3"><i class="bi bi-person"></i> Entrar como Usuário</a>
                            <a href="{{ url('/admin/cadastro') }}" class="btn btn-outline-dark mb-3"><i class="bi bi-person-plus"></i> Criar Conta</a>
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
</body>
</html>
