<?php

namespace App\Observers;

use App\Models\Ocorrencia;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\StatusOcorrenciaAtualizado;

class OcorrenciaObserver
{
    public function updated(Ocorrencia $ocorrencia): void
    {
        if ($ocorrencia->isDirty('status')) {
            $statusAntigo = $ocorrencia->getOriginal('status');
            $statusNovo = $ocorrencia->status;

            $user = User::find($ocorrencia->user_id);

            if ($user && $user->email) {
                try {
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
