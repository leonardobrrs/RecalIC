<!DOCTYPE html>
<html lang="pt-BR" style="font-family: Arial, sans-serif; line-height: 1.6;">
<head>
    <meta charset="UTF-8">
    <title>Atualização de Ocorrência</title>
    <style>
        .container { width: 90%; max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        .comment { background-color: #f4f4f4; padding: 15px; border-radius: 5px; font-style: italic; }
        .status { font-weight: bold; padding: 5px 10px; border-radius: 15px; color: white; }
        .status-analise { background-color: #ffc107; color: #333; }
        .status-resolvido { background-color: #28a745; }
        .status-invalido { background-color: #dc3545; }
        .status-aberto { background-color: #0d6efd; }
        .status-fechado { background-color: #6c757d; }
    </style>
</head>
<body>
    <div class="container">
        <p>Olá, {{ $historico->ocorrencia->relator->name }},</p>

        <p>O status da sua ocorrência (<b>ID: {{ $historico->ocorrencia_id }}</b>) para a localização "<b>{{ $historico->ocorrencia->localizacao }}</b>" foi atualizado.</p>

        <p>
            Status Anterior: {{ $historico->status_anterior }} <br>
            <strong>Novo Status: {{ $historico->status_novo }}</strong>
        </p>

        @if ($historico->comentario)
            <p><strong>Comentário do Administrador:</strong></p>
            <div class="comment">
                "{{ $historico->comentario }}"
            </div>
        @endif

        <p style="margin-top: 30px; font-size: 0.9em; color: #777;">
            Obrigado por usar o RecalIC.
        </p>
    </div>
</body>
</html>