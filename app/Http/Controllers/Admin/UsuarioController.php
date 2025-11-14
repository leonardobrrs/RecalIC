<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UsuarioController extends Controller
{

    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            switch ($request->status) {
                case 'bloqueado':
                    $query->where('reputation_score', '<=', 0);
                    break;
                case 'ruim':
                    $query->where('reputation_score', '>', 0)
                          ->where('reputation_score', '<', 50);
                    break;
                case 'media':
                    $query->where('reputation_score', '>=', 50)
                          ->where('reputation_score', '<', 75);
                    break;
                case 'boa':
                    $query->where('reputation_score', '>=', 75);
                    break;
            }
        }

        $usuarios = $query->orderBy('name')->paginate(15);

        return view('DashboardAdmin.usuarios', [
            'usuarios' => $usuarios,
            'filters' => $request->all()
        ]);
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.usuarios.index')
                ->withErrors(['error' => 'Você não pode excluir a sua própria conta!']);
        }
        $user->delete();
        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Utilizador excluído com sucesso.');
    }

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

    public function unblock(User $user)
    {
        $user->reputation_score = 100;
        $user->save();
        return redirect()->back()->with('success', 'Utilizador desbloqueado com sucesso.');
    }
}
