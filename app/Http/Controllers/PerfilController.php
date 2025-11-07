<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class PerfilController extends Controller
{
    /**
     * Mostra o formulário de edição de perfil.
     */
    public function edit()
    {
        // Pega o usuário autenticado
        $user = Auth::user();
        
        // Retorna a view com os dados do usuário
        return view('DashboardUsuario.perfil', ['user' => $user]);
    }

    /**
     * Atualiza os dados do perfil do usuário.
     */
    public function update(Request $request)
    {
        // Pega o usuário autenticado
        $user = Auth::user();

        // Validação dos dados
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id) // Ignora o email do próprio usuário na verificação de unicidade
            ],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()], // Senha é opcional
        ]);

        // Atualiza os dados do usuário
        $user->name = $request->name;
        $user->email = $request->email;

        // Atualiza a senha apenas se ela foi preenchida
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save(); // Salva as alterações no banco

        // Redireciona de volta para a página de perfil com uma mensagem de sucesso
        return redirect()->route('perfil.edit')->with('success', 'Perfil atualizado com sucesso!');
    }
}