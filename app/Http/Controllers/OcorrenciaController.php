<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OcorrenciaController extends Controller
{
    /**
     * Exibe a lista de ocorrências do usuário (Dashboard).
     */
    public function index()
    {
        return view('DashboardUsuario.dashboard');
    }

    /**
     * Exibe o formulário para criar uma nova ocorrência.
     */
    public function create()
    {
        return view('DashboardUsuario.registro');
    }

    /**
     * Salva uma nova ocorrência no banco de dados.
     */
    public function store(Request $request)
    {
        // Lógica de salvamento será implementada na Semana 2
    }

    /**
     * Exibe os detalhes de uma ocorrência específica.
     */
    public function show(string $id)
    {
        return view('DashboardUsuario.relato');
    }

    /**
     * Exibe o histórico de uma ocorrência específica.
     * (Criamos um método separado para a tela de histórico)
     */
    public function historico(string $id)
    {
        return view('DashboardUsuario.detalhesRelato');
    }
}
