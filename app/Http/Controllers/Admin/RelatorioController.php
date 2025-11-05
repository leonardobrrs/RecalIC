<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ocorrencia;
use App\Models\Avaliacao; // <-- 1. IMPORTADO
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
        // 2. ALTERADO - Contagens agora separadas
        $ocorrenciasResolvidas = Ocorrencia::where('status', 'Resolvido')->count();
        $ocorrenciasPendentes = Ocorrencia::whereIn('status', ['Aberto', 'Em Análise'])->count();
        $totalInvalidas = Ocorrencia::where('status', 'Inválido')->count(); // <-- 3. ADICIONADO

        // Total de ocorrências VÁLIDAS (exclui inválidas)
        $totalOcorrencias = $ocorrenciasResolvidas + $ocorrenciasPendentes; // <-- 4. ALTERADO O CÁLCULO


        // --- CÁLCULO DO TEMPO MÉDIO DE RESOLUÇÃO ---
        // (Seu código original inalterado)
        $ocorrenciasResolvidasComHistorico = Ocorrencia::where('status', 'Resolvido')
            ->with('historico')
            ->get();

        $totalSegundosParaResolver = 0;
        $countResolvidasComHistorico = 0;
        $tempoMedioFormatado = "-"; 

        foreach ($ocorrenciasResolvidasComHistorico as $ocorrencia) {
            $historicoResolucao = $ocorrencia->historico
                ->where('status_novo', 'Resolvido')
                ->sortBy('created_at') 
                ->first();

            if ($historicoResolucao) {
                $tempoAbertura = $ocorrencia->created_at;
                $tempoResolucao = $historicoResolucao->created_at;

                $totalSegundosParaResolver += $tempoResolucao->diffInSeconds($tempoAbertura);
                $countResolvidasComHistorico++;
            }
        }

        if ($countResolvidasComHistorico > 0) {
            $mediaSegundos = $totalSegundosParaResolver / $countResolvidasComHistorico;
            $tempoMedioFormatado = Carbon::now()->subSeconds($mediaSegundos)->diffForHumans(Carbon::now(), true, true, 2);
        }
        // --- FIM DO CÁLCULO ---


        // --- 5. NOVA SEÇÃO: NOTA MÉDIA ---
        $notaMedia = Avaliacao::avg('nota');
        $totalAvaliacoes = Avaliacao::count();
        // --- FIM DA NOVA SEÇÃO ---


        // --- Dados para Gráficos ---
        // (Seu código original inalterado)
        $ocorrenciasPorCategoria = Ocorrencia::select('categoria', DB::raw('count(*) as total'))
            ->groupBy('categoria')
            ->pluck('total', 'categoria');
        $locaisMaisRelatos = Ocorrencia::select('localizacao', DB::raw('count(*) as total'))
            ->groupBy('localizacao')
            ->orderByDesc('total')
            ->limit(5)
            ->pluck('total', 'localizacao');

        // Envia todos os dados para a view
        // --- 6. ATUALIZADO O ARRAY DE RETORNO ---
        return view('DashboardAdmin.relatorios', [
            'totalOcorrencias' => $totalOcorrencias, // Este é o total VÁLIDO
            'ocorrenciasResolvidas' => $ocorrenciasResolvidas,
            'ocorrenciasPendentes' => $ocorrenciasPendentes,
            'totalInvalidas' => $totalInvalidas, // Variável nova
            'tempoMedioFormatado' => $tempoMedioFormatado,
            'ocorrenciasPorCategoria' => $ocorrenciasPorCategoria,
            'locaisMaisRelatos' => $locaisMaisRelatos,
            'notaMedia' => $notaMedia, // Variável nova
            'totalAvaliacoes' => $totalAvaliacoes, // Variável nova
        ]);
    }
}