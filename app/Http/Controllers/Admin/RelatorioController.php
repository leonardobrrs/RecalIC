<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ocorrencia;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // Importar a classe Carbon para manipulação de datas

class RelatorioController extends Controller
{
    /**
     * Exibe a página de relatórios com os dados agregados.
     */
    public function index(Request $request)
    {
        // --- KPIs (Contagens) ---
        $totalOcorrencias = Ocorrencia::count();
        $ocorrenciasResolvidas = Ocorrencia::where('status', 'Resolvido')->count();
        $ocorrenciasPendentes = Ocorrencia::whereIn('status', ['Aberto', 'Em Análise'])->count();

        // --- CÁLCULO DO TEMPO MÉDIO DE RESOLUÇÃO ---

        // 1. Pega todas as ocorrências resolvidas, já carregando o seu histórico
        $ocorrenciasResolvidasComHistorico = Ocorrencia::where('status', 'Resolvido')
            ->with('historico')
            ->get();

        $totalSegundosParaResolver = 0;
        $countResolvidasComHistorico = 0;
        $tempoMedioFormatado = "-"; // Valor padrão caso não haja nenhuma resolvida

        foreach ($ocorrenciasResolvidasComHistorico as $ocorrencia) {
            // 2. Para cada ocorrência, encontra o primeiro registo no histórico que a marcou como "Resolvido"
            $historicoResolucao = $ocorrencia->historico
                ->where('status_novo', 'Resolvido')
                ->sortBy('created_at') // Ordena para garantir que pegamos o primeiro
                ->first();

            if ($historicoResolucao) {
                // 3. Se encontrou o registo, calcula a diferença em segundos
                $tempoAbertura = $ocorrencia->created_at;
                $tempoResolucao = $historicoResolucao->created_at;

                $totalSegundosParaResolver += $tempoResolucao->diffInSeconds($tempoAbertura);
                $countResolvidasComHistorico++;
            }
        }

        // 4. Calcula a média e formata
        if ($countResolvidasComHistorico > 0) {
            $mediaSegundos = $totalSegundosParaResolver / $countResolvidasComHistorico;

            // Converte os segundos para um formato legível (ex: "2 dias, 4 horas" ou "30 minutos")
            // diffForHumans(Carbon::now(), true, true, 2)
            // true = remove "atrás/depois"
            // true = sintaxe absoluta (sem "um")
            // 2 = duas partes (ex: 2 dias 4 horas)
            $tempoMedioFormatado = Carbon::now()->subSeconds($mediaSegundos)->diffForHumans(Carbon::now(), true, true, 2);
        }
        // --- FIM DO CÁLCULO ---

        // --- Dados para Gráficos ---
        $ocorrenciasPorCategoria = Ocorrencia::select('categoria', DB::raw('count(*) as total'))
            ->groupBy('categoria')
            ->pluck('total', 'categoria');
        $locaisMaisRelatos = Ocorrencia::select('localizacao', DB::raw('count(*) as total'))
            ->groupBy('localizacao')
            ->orderByDesc('total')
            ->limit(5)
            ->pluck('total', 'localizacao');

        // Envia todos os dados para a view
        return view('DashboardAdmin.relatorios', [
            'totalOcorrencias' => $totalOcorrencias,
            'ocorrenciasResolvidas' => $ocorrenciasResolvidas,
            'ocorrenciasPendentes' => $ocorrenciasPendentes,
            'tempoMedioFormatado' => $tempoMedioFormatado, // <- Passa a nova variável
            'ocorrenciasPorCategoria' => $ocorrenciasPorCategoria,
            'locaisMaisRelatos' => $locaisMaisRelatos,
        ]);
    }
}
