<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ocorrencia;

class AdminController extends Controller
{
    /**
     * Exibe o dashboard principal do administrador com todas as ocorrências.
     * AGORA COM LÓGICA DE FILTRO E ORDENAÇÃO.
     */
    public function dashboard(Request $request)
    {
        // Inicia a query para buscar ocorrências com o relacionamento 'relator'
        $query = Ocorrencia::with('relator');

        // Aplica os filtros (lógica já existente)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
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

        // --- NOVA LÓGICA DE ORDENAÇÃO ---
        // Verifica se o parâmetro 'sort' foi enviado, senão, usa 'desc' (mais recentes) como padrão.
        $sortDirection = $request->input('sort', 'desc');

        // Aplica a ordenação pela data de criação
        $query->orderBy('created_at', $sortDirection);
        // --- FIM DA NOVA LÓGICA ---

        // Executa a query com os filtros e ordenação
        $ocorrencias = $query->get();

        // Retorna a view, passando as ocorrências e a direção da ordenação atual
        return view('DashboardAdmin.dashboardADM', [
            'ocorrencias' => $ocorrencias,
            'currentSort' => $sortDirection // Envia a ordenação atual para a view
        ]);
    }

    public function showOcorrencia(string $id)
    {
        $ocorrencia = Ocorrencia::findOrFail($id);
        return view('DashboardAdmin.registros', ['ocorrencia' => $ocorrencia]);
    }

    public function relatorios()
    {
        return view('DashboardAdmin.relatorios');
    }
}
