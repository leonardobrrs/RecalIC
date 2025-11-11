<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Ocorrencia;
use App\Models\StatusHistorico;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{

    public function dashboard(Request $request)
    {
        $query = Ocorrencia::with('relator');

        if ($request->has('status')) {

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

        } else {

            $query->whereIn('status', ['Aberto', 'Em Análise']);
        }

        if ($request->filled('category')) {
            $query->where('categoria', $request->category);
        }
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('descricao', 'like', '%' . $request->search . '%')
                    ->orWhereHas('relator', function($q_user) use ($request) {
                        $q_user->where('name', 'like', '%' . $request->search . '%');
                    });
            });
        }

        $sortDirection = $request->input('sort', 'desc');

        $query->orderBy('created_at', $sortDirection);

        $ocorrencias = $query->get();

        return view('DashboardAdmin.dashboardADM', [
            'ocorrencias' => $ocorrencias,
            'currentSort' => $sortDirection
        ]);
    }

    public function showOcorrencia(string $id)
    {
        $ocorrencia = Ocorrencia::with(['relator', 'anexos', 'historico.admin', 'avaliacao'])
            ->findOrFail($id);

        $relatorJaAvaliado = $ocorrencia->historico->contains(function ($item) {

            return $item->status_novo === 'Relator Avaliado';
        });

        return view('DashboardAdmin.registros', [
            'ocorrencia' => $ocorrencia,
            'relatorJaAvaliado' => $relatorJaAvaliado
        ]);
    }

    public function updateOcorrenciaStatus(Request $request, string $id)
    {

        $ocorrencia = Ocorrencia::findOrFail($id);

        $validatedData = $request->validate([
            'status' => [
                'required',
                Rule::in(['Aberto', 'Em Análise', 'Resolvido', 'Inválido']),
            ],
            'comentario' => 'nullable|string|max:1000',
        ]);

        $statusAnterior = $ocorrencia->status;
        $novoStatus = $validatedData['status'];

        StatusHistorico::create([
            'ocorrencia_id' => $ocorrencia->id,
            'user_id' => Auth::id(),
            'status_anterior' => $statusAnterior,
            'status_novo' => $novoStatus,
            'comentario' => $validatedData['comentario'],
        ]);

        $ocorrencia->status = $novoStatus;


        if ($novoStatus === 'Inválido' && $statusAnterior !== 'Inválido') {

            $relator = $ocorrencia->relator;
            if ($relator) {

                $relator->reputation_score -= 20;
                $relator->save();
            }
        }

        $ocorrencia->save();

        return redirect()->route('admin.ocorrencias.show', $ocorrencia->id)
                         ->with('success', 'Status da ocorrência atualizado com sucesso!');
    }

    public function destroyOcorrencia(string $id)
    {

        $ocorrencia = Ocorrencia::with('anexos')->findOrFail($id);

        foreach ($ocorrencia->anexos as $anexo) {
            Storage::disk('public')->delete($anexo->file_path);
        }

        $ocorrencia->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Ocorrência (ID: ' . $id . ') foi excluída com sucesso.');
    }

    public function relatorios()
    {
        return view('DashboardAdmin.relatorios');
    }

    public function blockUser(string $id)
    {
        $user = User::findOrFail($id);
        $user->reputation_score = 0;
        $user->save();

        return redirect()->back()->with('success', 'Utilizador bloqueado com sucesso. A sua reputação foi definida como 0.');
    }

    public function avaliarRelator(Request $request, $id)
    {
        $request->validate([
            'nota' => 'required|integer|min:1|max:5',
        ]);

        $ocorrencia = Ocorrencia::with(['relator', 'historico'])->findOrFail($id);

        $jaAvaliado = $ocorrencia->historico->contains(function ($item) {
            return $item->status_novo === 'Relator Avaliado';
        });

        if ($jaAvaliado) {
            return redirect()->back()->withErrors(['error' => 'Este relator já foi avaliado para esta ocorrência.']);
        }

        $usuario = $ocorrencia->relator;

        if (!$usuario || !$usuario->id) {
            return redirect()->back()->withErrors(['error' => 'Não é possível avaliar um usuário que foi excluído.']);
        }

        if ($usuario->id === auth()->id()) {
            return redirect()->back()->withErrors(['error' => 'Você não pode avaliar a si mesmo!']);
        }

        $score = 0;
        switch ($request->nota) {
            case 1: $score = 0; break;
            case 2: $score = 25; break;
            case 3: $score = 50; break;
            case 4: $score = 75; break;
            case 5: $score = 100; break;
        }

        $usuario->reputation_score = $score;
        $usuario->save();

        StatusHistorico::create([
            'ocorrencia_id' => $ocorrencia->id,
            'user_id' => Auth::id(),
            'status_anterior' => $ocorrencia->status,
            'status_novo' => 'Relator Avaliado',
            'comentario' => 'O relator recebeu a nota ' . $request->nota . ' (Score atualizado para: ' . $score . ')',
        ]);

        return redirect()->back()->with('success', 'A avaliação do relator foi registrada e seu score de reputação atualizado!');
    }
}
