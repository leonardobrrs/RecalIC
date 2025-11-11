<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ocorrencia;
use App\Models\Avaliacao;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RelatorioController extends Controller
{

    public function index(Request $request)
    {

        $ocorrenciasResolvidas = Ocorrencia::where('status', 'Resolvido')->count();
        $ocorrenciasPendentes = Ocorrencia::whereIn('status', ['Aberto', 'Em Análise'])->count();
        $totalInvalidas = Ocorrencia::where('status', 'Inválido')->count();

        $totalOcorrencias = $ocorrenciasResolvidas + $ocorrenciasPendentes;

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

        $notaMedia = Avaliacao::avg('nota');
        $totalAvaliacoes = Avaliacao::count();

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


        return view('DashboardAdmin.relatorios', [
            'totalOcorrencias' => $totalOcorrencias,
            'ocorrenciasResolvidas' => $ocorrenciasResolvidas,
            'ocorrenciasPendentes' => $ocorrenciasPendentes,
            'totalInvalidas' => $totalInvalidas,
            'tempoMedioFormatado' => $tempoMedioFormatado,
            'ocorrenciasPorCategoria' => $ocorrenciasPorCategoria,
            'locaisMaisRelatos' => $locaisMaisRelatos,
            'notaMedia' => $notaMedia,
            'totalAvaliacoes' => $totalAvaliacoes,
        ]);
    }
}
