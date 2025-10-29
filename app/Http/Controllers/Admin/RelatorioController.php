<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ocorrencia; // Importa o Model Ocorrencia
use Illuminate\Support\Facades\DB; // Importa a facade DB para queries mais complexas

class RelatorioController extends Controller
{
    /**
     * Exibe a página de relatórios com os dados agregados.
     */
    public function index(Request $request)
    {
        // --- KPIs ---
        $totalOcorrencias = Ocorrencia::count();
        $ocorrenciasResolvidas = Ocorrencia::where('status', 'Resolvido')->count();
        $ocorrenciasPendentes = Ocorrencia::whereIn('status', ['Aberto', 'Em Análise'])->count();

        // --- Dados para Gráficos ---
        $ocorrenciasPorCategoria = Ocorrencia::select('categoria', DB::raw('count(*) as total'))
            ->groupBy('categoria')
            ->pluck('total', 'categoria');
        $locaisMaisRelatos = Ocorrencia::select('localizacao', DB::raw('count(*) as total'))
            ->groupBy('localizacao')
            ->orderByDesc('total')
            ->limit(5)
            ->pluck('total', 'localizacao');

        // A linha abaixo não será executada por causa do dd()
        return view('DashboardAdmin.relatorios', [
            'totalOcorrencias' => $totalOcorrencias,
            'ocorrenciasResolvidas' => $ocorrenciasResolvidas,
            'ocorrenciasPendentes' => $ocorrenciasPendentes,
            'ocorrenciasPorCategoria' => $ocorrenciasPorCategoria,
            'locaisMaisRelatos' => $locaisMaisRelatos,
        ]);
    }
}
