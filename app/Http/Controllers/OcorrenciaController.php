<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ocorrencia;
use App\Models\OcorrenciaAnexo;
use Illuminate\Support\Facades\Auth;

class OcorrenciaController extends Controller
{
    public function index()
    {
        return view('DashboardUsuario.dashboard');
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
        // 1. Validação dos dados
        $validatedData = $request->validate([
            'localizacao' => 'required|string|max:255',
            'categoria' => 'required|string|max:255',
            'patrimonio_id' => 'nullable|string|max:255',
            'descricao' => 'required|string',
            'anexos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048' // Valida cada anexo
        ]);

        // 2. Cria a ocorrência principal
        $ocorrencia = Ocorrencia::create([
            'user_id' => Auth::id(), // Pega o ID do usuário logado
            'localizacao' => $validatedData['localizacao'],
            'categoria' => $validatedData['categoria'],
            'patrimonio_id' => $validatedData['patrimonio_id'],
            'descricao' => $validatedData['descricao'],
            'status' => 'Aberto', // Define o status inicial
        ]);

        // 3. Processa e salva os anexos, se houver
        if ($request->hasFile('anexos')) {
            foreach ($request->file('anexos') as $anexo) {
                // Salva o arquivo em 'storage/app/public/anexos' e obtém o caminho
                $path = $anexo->store('anexos', 'public');

                // Cria o registro no banco de dados
                OcorrenciaAnexo::create([
                    'ocorrencia_id' => $ocorrencia->id,
                    'file_path' => $path,
                ]);
            }
        }

        // 4. Redireciona para o dashboard com uma mensagem de sucesso
        return redirect()->route('user.dashboard')->with('success', 'Ocorrência registrada com sucesso!');
    }

    public function show(string $id)
    {
        return view('DashboardUsuario.relato');
    }

    public function historico(string $id)
    {
        return view('DashboardUsuario.detalhesRelato');
    }
}
