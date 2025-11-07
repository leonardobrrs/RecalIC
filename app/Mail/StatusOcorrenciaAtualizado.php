<?php

namespace App\Mail;

use App\Models\Ocorrencia;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StatusOcorrenciaAtualizado extends Mailable
{
    use Queueable, SerializesModels;

    public $ocorrencia;
    public $statusAntigo;
    public $statusNovo;

    public function __construct(Ocorrencia $ocorrencia, $statusAntigo, $statusNovo)
    {
        $this->ocorrencia = $ocorrencia;
        $this->statusAntigo = $statusAntigo;
        $this->statusNovo = $statusNovo;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Status da Ocorrência Atualizado - #' . $this->ocorrencia->id,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.statusAtualizado',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
