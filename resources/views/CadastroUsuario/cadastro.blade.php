<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <title>Cadastro - Sistema de Recall</title>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center py-4">
                        <h2 class="mb-0"><i class="bi bi-person-plus"></i> Criar Conta</h2>
                        <p class="mb-0 mt-2">Sistema de Recall - UFAL</p>
                    </div>
                    <div class="card-body">
                        <div class="mx-auto text-center p-3">
                            <img src="https://ufal.br/ufal/comunicacao/identidade-visual/brasao/somente-imagem/brasao-ufal.png" height="120" width="69" alt="Brasão UFAL" class="img-fluid">
                        </div>

                        <form id="cadastroForm" class="needs-validation" novalidate>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="nomeCompleto" placeholder="Nome Completo" required>
                                        <label for="nomeCompleto"><i class="bi bi-person"></i> Nome Completo</label>
                                        <div class="invalid-feedback">
                                            Por favor, informe seu nome completo.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="cpf" placeholder="CPF" required pattern="\d{3}\.\d{3}\.\d{3}-\d{2}">
                                        <label for="cpf"><i class="bi bi-card-text"></i> CPF</label>
                                        <div class="invalid-feedback">
                                            Por favor, informe um CPF válido.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="email" class="form-control" id="email" placeholder="E-mail" required>
                                        <label for="email"><i class="bi bi-envelope"></i> E-mail</label>
                                        <div class="invalid-feedback">
                                            Por favor, informe um e-mail válido.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="password" class="form-control" id="senha" placeholder="Senha" required minlength="8">
                                        <label for="senha"><i class="bi bi-lock"></i> Senha</label>
                                        <div class="invalid-feedback">
                                            A senha deve ter pelo menos 8 caracteres.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="password" class="form-control" id="confirmarSenha" placeholder="Confirmar Senha" required>
                                        <label for="confirmarSenha"><i class="bi bi-lock-fill"></i> Confirmar Senha</label>
                                        <div class="invalid-feedback">
                                            As senhas não coincidem.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid gap-2 mb-3">
                                <button class="btn btn-primary" type="submit">
                                    Criar Conta
                                </button>
                            </div>

                            <div class="text-center">
                                <p>Já tem uma conta? <a href="{{ url('/') }}" id="linkLogin">Fazer login</a></p>
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
        // Validação de CPF
        document.getElementById('cpf').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 11) value = value.slice(0, 11);

            if (value.length > 9) {
                value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, "$1.$2.$3-$4");
            } else if (value.length > 6) {
                value = value.replace(/(\d{3})(\d{3})(\d+)/, "$1.$2.$3");
            } else if (value.length > 3) {
                value = value.replace(/(\d{3})(\d+)/, "$1.$2");
            }
            e.target.value = value;
        });

        // Validação de confirmação de senha
        const senhaInput = document.getElementById('senha');
        const confirmarSenhaInput = document.getElementById('confirmarSenha');

        function validarSenhas() {
            if (senhaInput.value !== confirmarSenhaInput.value) {
                confirmarSenhaInput.setCustomValidity('As senhas não coincidem');
            } else {
                confirmarSenhaInput.setCustomValidity('');
            }
        }

        senhaInput.addEventListener('change', validarSenhas);
        confirmarSenhaInput.addEventListener('keyup', validarSenhas);

        // Validação do formulário
        document.getElementById('cadastroForm').addEventListener('submit', function(event) {
            event.preventDefault();

            if (!this.checkValidity()) {
                event.stopPropagation();
                this.classList.add('was-validated');
                return;
            }

            // Simulação de cadastro bem-sucedido
            alert('CONTA CRIADA COM SUCESSO!');
            // Redireciona para a página de login após 1 segundo
            setTimeout(function() {
                window.location.href = '{{ url('/') }}';
            }, 1000);
        });
    </script>
</body>
</html>
