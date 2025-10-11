<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Exibe o dashboard principal do administrador com todas as ocorrências.
     */
    public function dashboard()
    {
        return view('DashboardAdmin.dashboardADM');
    }

    /**
     * Exibe os detalhes de uma ocorrência para o administrador.
     */
    public function showOcorrencia(string $id)
    {
        return view('DashboardAdmin.registros');
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
