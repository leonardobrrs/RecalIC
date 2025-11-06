<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\StatusHistorico; // Importamos o Model que tem os dados

class OcorrenciaStatusAtualizado extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    // Propriedade pública para passar os dados para a view
    public StatusHistorico $historico;

    /**
     * Cria uma nova instância da mensagem.
     */
    public function __construct(StatusHistorico $historico)
    {
        // Recebe o registo do histórico quando o email é criado
        $this->historico = $historico;
    }

    /**
     * Define o "envelope" (Assunto e Remetente)
     */
    public function envelope(): Envelope
    {
        // Define o assunto do email dinamicamente
        $subject = 'Atualização da Ocorrência #' . $this->historico->ocorrencia_id . ': ' . $this->historico->status_novo;

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Define o conteúdo (o corpo do email)
     */
    public function content(): Content
    {
        // Aponta para o ficheiro de view que vamos criar no próximo passo
        return new Content(
            view: 'emails.ocorrencia-status',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate.Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}