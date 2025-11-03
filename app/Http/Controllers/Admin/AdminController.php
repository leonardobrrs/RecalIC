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
    /**
     * Exibe o dashboard principal do administrador com todas as ocorrências.
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

        // Verifica se o parâmetro 'sort' foi enviado, senão, usa 'desc' (mais recentes) como padrão.
        $sortDirection = $request->input('sort', 'desc');

        // Aplica a ordenação pela data de criação
        $query->orderBy('created_at', $sortDirection);

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
        $ocorrencia = Ocorrencia::with('relator')->findOrFail($id);
        // 1. Busca a ocorrência pelo ID
        // 2. 'with()' carrega os relacionamentos:
        //    'anexos' -> busca todas as fotos na tabela 'ocorrencia_anexos'
        //    'historico.admin' -> busca o histórico E o nome do admin que fez a alteração
        $ocorrencia = Ocorrencia::with(['anexos', 'historico.admin'])
                                ->findOrFail($id);

        // 3. Retorna a view e passa a ocorrência completa com seus relacionamentos
        return view('DashboardAdmin.registros', ['ocorrencia' => $ocorrencia]);
    }

    /**
     * Atualiza o status de uma ocorrência e registra no histórico.
     */
    public function updateOcorrenciaStatus(Request $request, string $id)
    {
        // 1. Encontra a ocorrência ou falha (erro 404 se não existir)
        $ocorrencia = Ocorrencia::findOrFail($id);

        // 2. Valida os dados recebidos do formulário
        $validatedData = $request->validate([
            'status' => [ // Valida o campo 'status'
                'required', // É obrigatório
                Rule::in(['Aberto', 'Em Análise', 'Resolvido', 'Inválido']), // Deve ser um dos valores permitidos
            ],
            'comentario' => 'nullable|string|max:1000', // Comentário é opcional
        ]);

        // Guarda o status antigo para o histórico
        $statusAnterior = $ocorrencia->status;
        $novoStatus = $validatedData['status'];

        // 3. Cria o registro no histórico ANTES de atualizar a ocorrência
        StatusHistorico::create([
            'ocorrencia_id' => $ocorrencia->id,
            'user_id' => Auth::id(), // ID do administrador logado
            'status_anterior' => $statusAnterior,
            'status_novo' => $novoStatus,
            'comentario' => $validatedData['comentario'],
        ]);

        // 4. Atualiza o status na ocorrência principal
        $ocorrencia->status = $novoStatus;

        // --- NOVA LÓGICA DE PENALIDADE ---
        // Verifica se o novo status é 'Inválido' e se o status anterior não era 'Inválido' (para não penalizar múltiplas vezes)
        if ($novoStatus === 'Inválido' && $statusAnterior !== 'Inválido') {
            // Busca o usuário relator
            $relator = $ocorrencia->relator; // Usa o relacionamento que já existe
            if ($relator) {
                // Diminui a pontuação (ajuste o valor da penalidade conforme necessário)
                $relator->reputation_score -= 10;
                $relator->save(); // Salva a alteração no usuário
            }
        }
        // --- FIM DA NOVA LÓGICA ---

        $ocorrencia->save();

        // 5. Redireciona de volta para a página de detalhes com mensagem de sucesso
        return redirect()->route('admin.ocorrencias.show', $ocorrencia->id)
                         ->with('success', 'Status da ocorrência atualizado com sucesso!');
    }

    public function destroyOcorrencia(string $id)
    {
        // 1. Encontra a ocorrência (com seus anexos) ou falha
        $ocorrencia = Ocorrencia::with('anexos')->findOrFail($id);

        // 2. Apaga os arquivos físicos do disco
        foreach ($ocorrencia->anexos as $anexo) {
            Storage::disk('public')->delete($anexo->file_path);
        }

        // 3. Apaga o registro da ocorrência do banco de dados
        //    (O banco de dados cuidará de apagar os anexos, histórico e avaliações
        //     relacionados, graças ao 'onDelete(cascade)' nas migrations)
        $ocorrencia->delete();

        // 4. Redireciona para o dashboard do admin com mensagem de sucesso
        return redirect()->route('admin.dashboard')->with('success', 'Ocorrência (ID: ' . $id . ') foi excluída com sucesso.');
    }

    public function relatorios()
    {
        return view('DashboardAdmin.relatorios');
    }

    /**
     * Define a reputação de um utilizador como 0 (Bloqueado).
     */
    public function blockUser(string $id)
    {
        $user = User::findOrFail($id);
        $user->reputation_score = 0;
        $user->save();

        // Redireciona de volta para a página anterior (a de detalhes da ocorrência)
        return redirect()->back()->with('success', 'Utilizador bloqueado com sucesso. A sua reputação foi definida como 0.');
    }
}
