<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ocorrencia;
use App\Models\OcorrenciaAnexo;
use Illuminate\Support\Facades\Auth;

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
}
