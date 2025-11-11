<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function showLoginForm() { return view('LoginUsuario.login'); }
    public function showRegistrationForm() { return view('CadastroUsuario.cadastro'); }
    public function showAdminLoginForm() { return view('LoginAdmin.loginADM'); }
    public function showAdminRegistrationForm() { return view('CadastroAdmin.cadastroADM'); }

    // --- LÓGICA DE USUÁRIO COMUM ---
    public function register(Request $request)
    {
        $request->validate([
            'nomeCompleto' => 'required|string|max:255',
            'cpf' => ['required', 'string', 'unique:users,cpf_cis', 'cpf'],
            'email' => ['required', 'string', 'email', 'min:10', 'max:30', 'unique:users', 'ends_with:@ic.ufal.br'],
            'senha' => 'required|string|min:8|max:255|confirmed',
        ], [
            'senha.confirmed' => 'Senhas não coincidem',
            'email.ends_with' => 'Apenas o e-mail institucional é permitido',
        ]);

        $user = User::create([
            'name' => strtoupper($request->nomeCompleto),
            'email' => $request->email,
            'cpf_cis' => preg_replace('/[^0-9]/', '', $request->cpf),
            'password' => Hash::make($request->senha),
            'role' => 'relator',
        ]);

        Auth::login($user);
        return redirect()->route('login');
    }

    public function login(Request $request)
    {
        $request->validate(['email' => 'required|email', 'senha' => 'required']);
        $credentials = ['email' => $request->email, 'password' => $request->senha];
        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('user.dashboard'));
        }

        return back()->withErrors(['email' => 'As credenciais fornecidas não correspondem aos nossos registros.'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    // --- LÓGICA DE ADMINISTRADOR ---
    public function adminRegister(Request $request)
    {
        $request->validate([
            'nomeCompleto' => 'required|string|max:255',
            'cis' => 'required|string|in:'.env('ADMIN_CIS_CODE'),
            'email' => ['required', 'string', 'email', 'min:10', 'max:30', 'unique:users', 'ends_with:@ic.ufal.br'],
            'senha' => 'required|string|min:8|max:255|confirmed',
        ], [
            'cis.in' => 'Código inválido',
            'senha.confirmed' => 'Senhas não coincidem',
            'email.ends_with' => 'Apenas o e-mail institucional é permitido',
        ]);

        $user = User::create([
            'name' => strtoupper($request->nomeCompleto),
            'email' => $request->email,
            'cpf_cis' => 'ADM_' . uniqid() . '_' . time(),
            'password' => Hash::make($request->senha),
            'role' => 'admin',
        ]);

        return redirect()->route('admin.login')->with('success', 'Conta administrativa criada com sucesso!');
    }

    public function adminLogin(Request $request)
    {

        $request->validate(['email' => 'required|email', 'senha' => 'required']);

        $credentials = ['email' => $request->email, 'password' => $request->senha];
        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            if (Auth::user()->role === 'admin') {
                $request->session()->regenerate();
                return redirect()->intended(route('admin.dashboard'));
            }

            Auth::logout();
        }

        return back()->withErrors(['email' => 'Credenciais de administrador inválidas.'])->onlyInput('email');
    }

    public function adminLogout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
