<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ocorrencia;
use App\Models\Avaliacao; // Certifique-se que Avaliacao está importado
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
        // Contagens separadas
        $ocorrenciasResolvidas = Ocorrencia::where('status', 'Resolvido')->count();
        $ocorrenciasPendentes = Ocorrencia::whereIn('status', ['Aberto', 'Em Análise'])->count();
        $totalInvalidas = Ocorrencia::where('status', 'Inválido')->count();

        // Total VÁLIDO (como solicitado)
        $totalOcorrencias = $ocorrenciasResolvidas + $ocorrenciasPendentes;

        // --- CÁLCULO DO TEMPO MÉDIO DE RESOLUÇÃO ---
        // (Seu código original inalterado)
        $ocorrenciasResolvidasComHistorico = Ocorrencia::where('status', 'Resolvido')
            ->with('historico')
            ->get();

        $totalSegundosParaResolver = 0;
        $countResolvidasComHistorico = 0;
        $tempoMedioFormatado = "-"; // Valor padrão

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

        // --- NOTA MÉDIA (NOVO) ---
        $notaMedia = Avaliacao::avg('nota');
        $totalAvaliacoes = Avaliacao::count();
        // --- FIM NOTA MÉDIA ---


        // --- INÍCIO DA ALTERAÇÃO ---

        // --- Dados para Gráficos (Filtrando "Inválido") ---
        $ocorrenciasPorCategoria = Ocorrencia::where('status', '!=', 'Inválido')
            ->select('categoria', DB::raw('count(*) as total'))
            ->groupBy('categoria')
            ->pluck('total', 'categoria');

        $locaisMaisRelatos = Ocorrencia::where('status', '!=', 'Inválido')
            ->select('localizacao', DB::raw('count(*) as total'))
            ->groupBy('localizacao')
            ->orderByDesc('total')
            ->limit(5)
            ->pluck('total', 'localizacao');
            
        // --- FIM DA ALTERAÇÃO ---

        // Envia todos os dados para a view
        return view('DashboardAdmin.relatorios', [
            'totalOcorrencias' => $totalOcorrencias, // Válido
            'ocorrenciasResolvidas' => $ocorrenciasResolvidas,
            'ocorrenciasPendentes' => $ocorrenciasPendentes,
            'totalInvalidas' => $totalInvalidas, // Novo
            'tempoMedioFormatado' => $tempoMedioFormatado,
            'ocorrenciasPorCategoria' => $ocorrenciasPorCategoria, // Filtrado
            'locaisMaisRelatos' => $locaisMaisRelatos, // Filtrado
            'notaMedia' => $notaMedia, // Novo
            'totalAvaliacoes' => $totalAvaliacoes, // Novo
        ]);
    }
}