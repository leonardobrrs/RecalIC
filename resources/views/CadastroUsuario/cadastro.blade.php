<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <title>Cadastro - RecalIC</title>
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h2 class="mb-0"><i class="bi bi-person-plus"></i> Criar Conta</h2>
                    <p class="mb-0 mt-2">RecalIC - UFAL</p>
                </div>
                <div class="card-body">
                    <div class="mx-auto text-center p-3">
                        <img src="https://ufal.br/ufal/comunicacao/identidade-visual/brasao/somente-imagem/brasao-ufal.png" height="120" width="69" alt="Brasão UFAL" class="img-fluid">
                    </div>

                    <form id="cadastroForm" action="{{ url('/cadastro') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control @error('nomeCompleto') is-invalid @enderror" id="nomeCompleto" name="nomeCompleto" placeholder="Nome Completo" value="{{ old('nomeCompleto') }}" required>
                                    <label for="nomeCompleto"><i class="bi bi-person"></i> Nome Completo</label>
                                    @error('nomeCompleto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control @error('cpf') is-invalid @enderror" id="cpf" name="cpf" placeholder="CPF" value="{{ old('cpf') }}" required>
                                    <label for="cpf"><i class="bi bi-card-text"></i> CPF</label>
                                    @error('cpf')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="E-mail" value="{{ old('email') }}" required>
                                    <label for="email"><i class="bi bi-envelope"></i> E-mail</label>
                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control @error('senha') is-invalid @enderror" id="senha" name="senha" placeholder="Senha" required minlength="8">
                                    <label for="senha"><i class="bi bi-lock"></i> Senha</label>
                                    @error('senha')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control" id="confirmarSenha" name="senha_confirmation" placeholder="Confirmar Senha" required>
                                    <label for="confirmarSenha"><i class="bi bi-lock-fill"></i> Confirmar Senha</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mb-3">
                            <button class="btn btn-primary" type="submit">Criar Conta</button>
                        </div>

                        <div class="text-center">
                            <p>Já tem uma conta? <a href="{{ url('/login') }}" id="linkLogin">Fazer login</a></p>
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
