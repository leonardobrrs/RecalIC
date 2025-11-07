<?php

namespace App\Observers;

use App\Models\Ocorrencia;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\StatusOcorrenciaAtualizado;

class OcorrenciaObserver
{
    /**
     * Handle the Ocorrencia "updated" event.
     */
    public function updated(Ocorrencia $ocorrencia): void
    {
        // Verifica se o status foi alterado
        if ($ocorrencia->isDirty('status')) {
            $statusAntigo = $ocorrencia->getOriginal('status');
            $statusNovo = $ocorrencia->status;

            // Busca o usuário relator da ocorrência
            $user = User::find($ocorrencia->user_id);

            if ($user && $user->email) {
                try {
                    // Envia o e-mail de notificação
                    Mail::to($user->email)->send(
                        new StatusOcorrenciaAtualizado($ocorrencia, $statusAntigo, $statusNovo)
                    );

                    \Log::info("E-mail enviado para: {$user->email}");
                } catch (\Exception $e) {
                    \Log::error("Erro ao enviar e-mail: " . $e->getMessage());
                }
            }
        }
    }
}
