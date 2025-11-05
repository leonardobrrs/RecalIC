<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ocorrencia;
use App\Models\OcorrenciaAnexo;
use App\Models\Avaliacao; // Importar o Model Avaliacao
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule; // Para validação in

class OcorrenciaController extends Controller
{
    /**
     * Exibe a lista de ocorrências do usuário (Dashboard).
     */
    public function index()
    {
        // 1. Pega o ID do usuário atualmente logado.
        $userId = Auth::id();

        // 2. Busca no banco de dados todas as ocorrências onde 'user_id' é igual ao do usuário logado.
        //    'latest()' ordena os resultados do mais novo para o mais antigo.
        $ocorrencias = Ocorrencia::where('user_id', $userId)->latest()->get();

        // 3. Retorna a view do dashboard e passa a variável 'ocorrencias' para ela.
        return view('DashboardUsuario.dashboard', ['ocorrencias' => $ocorrencias]);
    }

    public function create()
    {
        return view('DashboardUsuario.registro');
    }

    /**
     * Salva uma nova ocorrência no banco de dados.
     */
    public function store(Request $request)
    {
        if (Auth::user()->reputation_score <= 0) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['limite' => 'A sua conta foi bloqueada por registar ocorrências inválidas e não pode criar novos relatos.']);
        }

        // --- ALTERAÇÃO: ADICIONADA VALIDAÇÃO PARA 'localizacao_outra' ---
        $validatedData = $request->validate([
            'localizacao' => 'required|string|max:255',
            'localizacao_outra' => 'nullable|required_if:localizacao,Outro|string|max:255', // Obrigatório se 'localizacao' for 'Outro'
            'categoria' => 'required|string|max:255',
            'patrimonio_id' => 'nullable|string|max:255',
            'descricao' => 'required|string',
            'anexos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
        ]);

        // --- ALTERAÇÃO: LÓGICA PARA PEGAR O VALOR FINAL DA LOCALIZAÇÃO ---
        $localizacaoFinal = $validatedData['localizacao'];
        if ($localizacaoFinal === 'Outro' && !empty($validatedData['localizacao_outra'])) {
            $localizacaoFinal = $validatedData['localizacao_outra'];
        }

        // 2. Cria a ocorrência principal
        $ocorrencia = Ocorrencia::create([
            'user_id' => Auth::id(),
            'localizacao' => $localizacaoFinal, // <-- Usa a variável final
            'categoria' => $validatedData['categoria'],
            'patrimonio_id' => $validatedData['patrimonio_id'],
            'descricao' => $validatedData['descricao'],
            'status' => 'Aberto',
        ]);

        // ... (resto do método store, lidando com anexos e redirecionamento) ...

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
        // Busca a ocorrência pelo ID ou falha (mostra erro 404 se não encontrar)
        $ocorrencia = Ocorrencia::findOrFail($id);

        // Retorna a view e passa a ocorrência encontrada para ela
        return view('DashboardUsuario.relato', ['ocorrencia' => $ocorrencia]);
    }

    public function historico(string $id)
    {
        $ocorrencia = Ocorrencia::findOrFail($id);

        return view('DashboardUsuario.detalhesRelato', ['ocorrencia' => $ocorrencia]);
    }

    public function storeAvaliacao(Request $request, string $id)
    {
        // 1. Encontra a ocorrência ou falha
        $ocorrencia = Ocorrencia::findOrFail($id);

        // 2. Verifica as permissões
        //    - O usuário logado é o dono da ocorrência?
        //    - O status é 'Resolvido'?
        //    - Já existe uma avaliação para esta ocorrência?
        if ($ocorrencia->user_id !== Auth::id() || $ocorrencia->status !== 'Resolvido' || $ocorrencia->avaliacao()->exists()) {
            // Se alguma condição falhar, redireciona com erro (ou pode lançar uma exceção 403 Forbidden)
            return redirect()->route('ocorrencias.show', $id)->withErrors(['avaliacao' => 'Não é possível avaliar esta ocorrência.']);
        }

        // 3. Valida os dados do formulário de avaliação
        $validatedData = $request->validate([
            'nota' => ['required', 'integer', Rule::in([1, 2, 3, 4, 5])], // Nota obrigatória de 1 a 5
            'comentario' => 'nullable|string|max:500', // Comentário opcional
        ]);

        // 4. Cria a avaliação no banco de dados
        Avaliacao::create([
            'ocorrencia_id' => $ocorrencia->id,
            'user_id' => Auth::id(),
            'nota' => $validatedData['nota'],
            'comentario' => $validatedData['comentario'],
        ]);

        // 5. Redireciona de volta para a página de detalhes com mensagem de sucesso
        return redirect()->route('ocorrencias.show', $id)->with('success', 'Avaliação registrada com sucesso!');
    }
}
