<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ocorrencia; // Model de Ocorrência importado

class AdminController extends Controller
{
    /**
     * Exibe o dashboard principal do administrador com todas as ocorrências.
     */
    public function dashboard()
    {
        // 1. Busca TODAS as ocorrências do banco de dados, ordenadas da mais nova para a mais antiga.
        //    'with('relator')' é uma otimização que já busca os dados do usuário que fez o relato (o Model User).
        $ocorrencias = Ocorrencia::with('relator')->latest()->get();

        // 2. Retorna a view do dashboard do admin e passa a variável 'ocorrencias' para ela.
        return view('DashboardAdmin.dashboardADM', ['ocorrencias' => $ocorrencias]);
    }

    /**
     * Exibe os detalhes de uma ocorrência para o administrador.
     */
    public function showOcorrencia(string $id)
    {
        // Busca a ocorrência pelo ID ou falha (mostra erro 404 se não encontrar)
        $ocorrencia = Ocorrencia::findOrFail($id);

        // Retorna a view e passa a ocorrência encontrada para ela
        return view('DashboardAdmin.registros', ['ocorrencia' => $ocorrencia]);
    }

    /**
     * Atualiza o status de uma ocorrência.
     */
    public function updateOcorrenciaStatus(Request $request, string $id)
    {
        // Lógica de atualização será implementada na Semana 3
    }

    /**
     * Exclui uma ocorrência.
     */
    public function destroyOcorrencia(string $id)
    {
        // Lógica de exclusão será implementada na Semana 3
    }

    /**
     * Exibe a página de relatórios.
     */
    public function relatorios()
    {
        return view('DashboardAdmin.relatorios');
    }
}
