<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ocorrencia;
use App\Models\OcorrenciaAnexo;
use App\Models\Avaliacao;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class OcorrenciaController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $ocorrencias = Ocorrencia::where('user_id', $userId)->latest()->get();

        return view('DashboardUsuario.dashboard', ['ocorrencias' => $ocorrencias]);
    }

    public function create()
    {
        return view('DashboardUsuario.registro');
    }

    public function store(Request $request)
    {

        $limiterKey = 'create-occurrence:' . Auth::id();

        $maxAttempts = 3;
        $decayInSeconds = 3600;

        if (RateLimiter::tooManyAttempts($limiterKey, $maxAttempts)) {

            return redirect()->back()
                ->withInput()
                ->withErrors(['limite' => 'Você está tentando registrar ocorrências muito rápido. Por favor, aguarde antes de tentar novamente.']);
        }

        RateLimiter::hit($limiterKey, $decayInSeconds);

        if (Auth::user()->reputation_score <= 0) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['limite' => 'A sua conta foi bloqueada por registar ocorrências inválidas e não pode criar novos relatos.']);
        }

        $validatedData = $request->validate([
            'localizacao' => 'required|string|max:255',
            'localizacao_outra' => 'nullable|required_if:localizacao,Outro|string|max:255',
            'categoria' => 'required|string|max:255',
            'patrimonio_id' => 'nullable|string|max:255',
            'descricao' => 'required|string',
            'anexos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
        ]);

        $localizacaoFinal = $validatedData['localizacao'];
        if ($localizacaoFinal === 'Outro' && !empty($validatedData['localizacao_outra'])) {
            $localizacaoFinal = $validatedData['localizacao_outra'];
        }

        $ocorrencia = Ocorrencia::create([
            'user_id' => Auth::id(),
            'localizacao' => $localizacaoFinal,
            'categoria' => $validatedData['categoria'],
            'patrimonio_id' => $validatedData['patrimonio_id'],
            'descricao' => $validatedData['descricao'],
            'status' => 'Aberto',
        ]);

        if ($request->hasFile('anexos')) {
            foreach ($request->file('anexos') as $anexo) {
                $path = $anexo->store('anexos', 'public');
                OcorrenciaAnexo::create([
                    'ocorrencia_id' => $ocorrencia->id,
                    'file_path' => $path,
                ]);
            }
        }
        return redirect()->route('user.dashboard')->with('success', 'Ocorrência registrada com sucesso!');
    }

    public function show(string $id)
    {

        $ocorrencia = Ocorrencia::with([
            'anexos',
            'avaliacao',
            'historico.admin'
        ])
        ->where('user_id', auth()->id())
        ->findOrFail($id);

        $adminFeedback = $ocorrencia->historico->firstWhere('status_novo', 'Relator Avaliado');

        return view('DashboardUsuario.relato', [
            'ocorrencia' => $ocorrencia,
            'adminFeedback' => $adminFeedback
        ]);
    }

public function historico(string $id)
    {
        $ocorrencia = Ocorrencia::with([
            'historico.admin'
        ])
        ->where('user_id', auth()->id())
        ->findOrFail($id);

        $adminFeedback = $ocorrencia->historico->firstWhere('status_novo', 'Relator Avaliado');

        return view('DashboardUsuario.detalhesRelato', [
            'ocorrencia' => $ocorrencia,
            'adminFeedback' => $adminFeedback
        ]);
    }

    public function storeAvaliacao(Request $request, string $id)
    {
        $ocorrencia = Ocorrencia::findOrFail($id);

        if ($ocorrencia->user_id !== Auth::id() || $ocorrencia->status !== 'Resolvido' || $ocorrencia->avaliacao()->exists()) {
            return redirect()->route('ocorrencias.show', $id)->withErrors(['avaliacao' => 'Não é possível avaliar esta ocorrência.']);
        }

        $validatedData = $request->validate([
            'nota' => ['required', 'integer', Rule::in([1, 2, 3, 4, 5])],
            'comentario' => 'nullable|string|max:500',
        ]);

        Avaliacao::create([
            'ocorrencia_id' => $ocorrencia->id,
            'user_id' => Auth::id(),
            'nota' => $validatedData['nota'],
            'comentario' => $validatedData['comentario'],
        ]);

        return redirect()->route('ocorrencias.show', $id)->with('success', 'Avaliação registrada com sucesso!');
    }
}
