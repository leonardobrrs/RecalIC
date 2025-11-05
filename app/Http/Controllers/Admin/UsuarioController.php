<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UsuarioController extends Controller
{
    /**
     * Exibe a lista de todos os utilizadores (com filtros).
     */
    public function index(Request $request)
    {
        $query = User::query();

        // --- LÓGICA DE FILTROS ADICIONADA ---
        // 1. Filtro de Pesquisa (Nome ou Email)
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // 2. Filtro de Papel (Role)
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // 3. Filtro de Status (Bloqueado)
        if ($request->filled('status') && $request->status == 'bloqueado') {
            $query->where('reputation_score', '<=', 0);
        }
        // --- FIM DOS FILTROS ---

        // Pagina o resultado
        $usuarios = $query->orderBy('name')->paginate(15);

        // Retorna a view, passando os utilizadores E os filtros atuais (para manter os campos preenchidos)
        return view('DashboardAdmin.usuarios', [
            'usuarios' => $usuarios,
            'filters' => $request->all() // Passa os inputs do request de volta para a view
        ]);
    }

    /**
     * Exclui um utilizador do sistema.
     */
    public function destroy(User $user) // Usando Route-Model Binding
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.usuarios.index')
                ->withErrors(['error' => 'Você não pode excluir a sua própria conta!']);
        }
        $user->delete();
        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Utilizador excluído com sucesso.');
    }

    // --- NOVOS MÉTODOS DE AÇÃO ADICIONADOS ---

    /**
     * Promove ou Rebaixa um utilizador.
     */
    public function toggleRole(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.usuarios.index')
                ->withErrors(['error' => 'Você não pode alterar o seu próprio papel!']);
        }

        $user->role = ($user->role === 'admin') ? 'relator' : 'admin';
        $user->save();

        return redirect()->back()->with('success', 'Papel do utilizador alterado com sucesso.');
    }

    /**
     * Bloqueia um utilizador (define reputação como 0).
     */
    public function block(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.usuarios.index')
                ->withErrors(['error' => 'Você não pode bloquear a si mesmo!']);
        }

        $user->reputation_score = 0;
        $user->save();
        return redirect()->back()->with('success', 'Utilizador bloqueado com sucesso.');
    }

    /**
     * Desbloqueia um utilizador (restaura reputação para 100).
     */
    public function unblock(User $user)
    {
        $user->reputation_score = 100;
        $user->save();
        return redirect()->back()->with('success', 'Utilizador desbloqueado com sucesso.');
    }
}
