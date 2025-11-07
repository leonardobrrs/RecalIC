<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Status da Ocorrência Atualizado</title>
    <style>
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0; mso-table-rspace: 0; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }

        /* Estilos principais */
        body { font-family: 'Segoe UI', Arial, sans-serif; line-height: 1.6; margin: 0; padding: 0; -webkit-font-smoothing: antialiased; background-color: #f8f9fa; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; }
        .header { background: #0d6efd; color: white; padding: 30px 20px; text-align: center; }
        .content { padding: 30px; }
        .status-card { background: #f8f9fa; border-left: 4px solid #007bff; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .info-card { background: #e8f4ff; border: 1px solid #b3d9ff; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .btn-primary { background: #007bff; color: white; padding: 14px 28px; text-decoration: none; border-radius: 6px; display: inline-block; font-weight: 600; font-size: 16px; }
        .footer { text-align: center; padding: 20px; color: #6c757d; font-size: 14px; background: #f8f9fa; }
        .divider { height: 1px; background: #e9ecef; margin: 25px 0; }

        @media only screen and (max-width: 600px) {
            .container { width: 100% !important; }
            .content { padding: 20px !important; }
        }
    </style>
</head>
<body>
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f8f9fa;">
    <tr>
        <td align="center" style="padding: 40px 10px;">
            <!-- Container Principal -->
            <table class="container" border="0" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border-radius: 8px; overflow: hidden;">

                <!-- Header -->
                <tr>
                    <td class="header">
                        <h1 style="margin: 0; font-size: 32px; font-weight: bold;">RecalIC</h1>
                        <p style="margin: 10px 0 0 0; opacity: 0.9; font-size: 16px;">Mudança de Status de Ocorrência</p>
                    </td>
                </tr>

                <!-- Conteúdo -->
                <tr>
                    <td class="content">
                        <h2 style="color: #333; margin-top: 0;">Olá, {{ $ocorrencia->relator->name }}!</h2>

                        <p style="color: #666; font-size: 16px;">O status da sua ocorrência foi atualizado. Aqui estão os detalhes:</p>

                        <!-- Card de Status -->
                        <div class="status-card">
                            <h3 style="margin-top: 0; color: #333;">🔄 Status Atualizado</h3>
                            <table width="100%">
                                <tr>
                                    <td width="120" style="color: #666;"><strong>Ocorrência:</strong></td>
                                    <td><strong>{{ $ocorrencia->id }}</strong></td>
                                </tr>
                                <tr>
                                    <td style="color: #666;"><strong>De:</strong></td>
                                    <td>{{ $statusAntigo }}</td>
                                </tr>
                                <tr>
                                    <td style="color: #666;"><strong>Para:</strong></td>
                                    <td style="color: #007bff; font-weight: bold;">{{ $statusNovo }}</td>
                                </tr>
                            </table>
                        </div>

                        <!-- Card de Informações -->
                        <div class="info-card">
                            <h4 style="margin-top: 0; color: #333;">📝 Detalhes da Ocorrência</h4>
                            <table width="100%">
                                <tr>
                                    <td width="100" style="color: #666;"><strong>Local:</strong></td>
                                    <td>{{ $ocorrencia->localizacao }}</td>
                                </tr>
                                <tr>
                                    <td style="color: #666;"><strong>Categoria:</strong></td>
                                    <td>{{ $ocorrencia->categoria }}</td>
                                </tr>
                                <tr>
                                    <td style="color: #666;"><strong>Patrimônio:</strong></td>
                                    <td>{{ $ocorrencia->patrimonio_id ?? 'Não informado' }}</td>
                                </tr>
                                <tr>
                                    <td style="color: #666; vertical-align: top;"><strong>Descrição:</strong></td>
                                    <td>{{ $ocorrencia->descricao }}</td>
                                </tr>
                            </table>
                        </div>

                        <div class="divider"></div>

                        <p style="color: #666; text-align: center;">Para acompanhar todos os detalhes e atualizações desta ocorrência:</p>

                        <div style="text-align: center; margin: 25px 0;">
                            <a href="{{ config('app.url') }}/dashboard" class="btn-primary">
                                Acessar Meu Dashboard
                            </a>
                        </div>
                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td class="footer">
                        <p style="margin: 0 0 10px 0;">Este é um e-mail automático, por favor não responda.</p>
                        <p style="margin: 0; font-size: 12px;">
                            <span style="color: #999;">ID da ocorrência: {{ $ocorrencia->id }}</span>
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
