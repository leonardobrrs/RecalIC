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
use Illuminate\Support\Facades\Mail; // Para enviar o email
use App\Mail\OcorrenciaStatusAtualizado; // O nosso "Envelope"
use Illuminate\Support\Facades\Log; // Para registar erros

class AdminController extends Controller
{
    /**
     * Exibe o dashboard principal do administrador com todas as ocorrências.
     */
    public function dashboard(Request $request)
    {
        // Inicia a query para buscar ocorrências com o relacionamento 'relator'
        $query = Ocorrencia::with('relator');

        // --- LÓGICA DE FILTRO DE STATUS ATUALIZADA ---

        // Verifica se o parâmetro 'status' foi enviado na URL
        if ($request->has('status')) {
            // Se foi enviado e NÃO está vazio (ex: "Resolvido", "Aberto")
            // A query filtra por esse status específico.
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            // Se 'status' foi enviado MAS está VAZIO (ex: o usuário selecionou "Todos")
            // não adicionamos nenhum filtro de status, mostrando TODOS.
        } else {
            // DEFAULT (Nenhum parâmetro 'status' na URL, primeiro acesso)
            // Mostra apenas ocorrências ativas ("Abertas" ou "Em Análise").
            $query->whereIn('status', ['Aberto', 'Em Análise']);
        }
        // --- FIM DA LÓGICA DE FILTRO DO STATUS ---

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
        // ALTERAÇÃO: Adicionado 'avaliacao' ao 'with()'
        $ocorrencia = Ocorrencia::with(['relator', 'anexos', 'historico.admin', 'avaliacao'])
            ->findOrFail($id);

        // --- NOVA LÓGICA ---
        // Verifica se o histórico contém um registro de que o relator já foi avaliado
        $relatorJaAvaliado = $ocorrencia->historico->contains(function ($item) {
            // Usamos 'status_novo' como um "log" de ação
            return $item->status_novo === 'Relator Avaliado';
        });
        // --- FIM DA NOVA LÓGICA ---

        return view('DashboardAdmin.registros', [
            'ocorrencia' => $ocorrencia,
            'relatorJaAvaliado' => $relatorJaAvaliado // Passa a flag para a view
        ]);
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

        // 3. Cria o registro no histórico E guarda numa variável
        $novoHistorico = StatusHistorico::create([
            'ocorrencia_id' => $ocorrencia->id,
            'user_id' => Auth::id(), // ID do administrador logado
            'status_anterior' => $statusAnterior,
            'status_novo' => $novoStatus,
            'comentario' => $validatedData['comentario'],
        ]);

        // 4. Atualiza o status na ocorrência principal
        $ocorrencia->status = $novoStatus;

        // --- LÓGICA DE PENALIDADE ---
        // Verifica se o novo status é 'Inválido' e se o status anterior não era 'Inválido'
        if ($novoStatus === 'Inválido' && $statusAnterior !== 'Inválido') {
            // Busca o usuário relator
            $relator = $ocorrencia->relator; // Usa o relacionamento que já existe
            if ($relator) {
                // Diminui a pontuação
                $relator->reputation_score -= 20;
                $relator->save(); // Salva a alteração no usuário
            }
        }
        // --- FIM DA LÓGICA DE PENALIDADE ---

        $ocorrencia->save();

        // --- ADIÇÃO: LÓGICA DE ENVIO DE EMAIL ---
        try {
            // Carrega os relacionamentos necessários para o email
            $novoHistorico->load('ocorrencia.relator');
            $relator = $novoHistorico->ocorrencia->relator;

            // Envia o email apenas se o relator existir e tiver um email
            if ($relator && $relator->email) {
                Mail::to($relator->email)->send(new OcorrenciaStatusAtualizado($novoHistorico));
            }
        } catch (\Exception $e) {
            // Se o envio de email falhar (ex: Mailtrap offline),
            // não quebra a aplicação, apenas regista o erro.
            Log::error('Falha ao enviar email de notificação de status: ' . $e->getMessage());
        }
        // --- FIM DA ADIÇÃO ---

        // 5. Redireciona de volta para a página de detalhes com mensagem de sucesso
        return redirect()->route('admin.ocorrencias.show', $ocorrencia->id)
                         ->with('success', 'Status da ocorrência atualizado! Notificação enviada ao utilizador.');
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

    public function avaliarRelator(Request $request, $id)
    {
        $request->validate([
            'nota' => 'required|integer|min:1|max:5',
        ]);

        // Carrega a ocorrência com as relações necessárias
        $ocorrencia = Ocorrencia::with(['relator', 'historico'])->findOrFail($id);

        // --- VERIFICAÇÃO DE REAVALIAÇÃO ---
        $jaAvaliado = $ocorrencia->historico->contains(function ($item) {
            return $item->status_novo === 'Relator Avaliado';
        });

        if ($jaAvaliado) {
            return redirect()->back()->withErrors(['error' => 'Este relator já foi avaliado para esta ocorrência.']);
        }
        // --- FIM DA VERIFICAÇÃO ---

        $usuario = $ocorrencia->relator;

        if (!$usuario || !$usuario->id) {
            return redirect()->back()->withErrors(['error' => 'Não é possível avaliar um usuário que foi excluído.']);
        }

        if ($usuario->id === auth()->id()) {
            return redirect()->back()->withErrors(['error' => 'Você não pode avaliar a si mesmo!']);
        }

        // Lógica da pontuação
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

        // --- LOGA A AVALIAÇÃO NO HISTÓRICO ---
        // Isso previne futuras reavaliações
        StatusHistorico::create([
            'ocorrencia_id' => $ocorrencia->id,
            'user_id' => Auth::id(), // Admin que avaliou
            'status_anterior' => $ocorrencia->status, // Status atual (Resolvido)
            'status_novo' => 'Relator Avaliado', // Nosso status "virtual" para log
            'comentario' => 'O relator recebeu a nota ' . $request->nota . ' (Score atualizado para: ' . $score . ')',
        ]);
        // --- FIM DO LOG ---

        return redirect()->back()->with('success', 'A avaliação do relator foi registrada e seu score de reputação atualizado!');
    }
}
