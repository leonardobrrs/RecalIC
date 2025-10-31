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
    // --- MÉTODOS DE EXIBIÇÃO ---
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
            'email' => 'required|string|email|min:10|max:255|unique:users',
            'senha' => 'required|string|min:8|max:255|confirmed',
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
        // 1. Validação dos dados (similar, mas com 'cis')
        $request->validate([
            'nomeCompleto' => 'required|string|max:255',
            'cis' => 'required|string|in:'.env('ADMIN_CIS_CODE'), // Validação do CIS (.env)
            'email' => 'required|string|email|min:10|max:255|unique:users',
            'senha' => 'required|string|min:8|max:255|confirmed',
        ]);

        // 2. Criação do usuário com o 'role' de 'admin'
        $user = User::create([
            'name' => strtoupper($request->nomeCompleto),
            'email' => $request->email,
            'cpf_cis' => 'ADM_' . uniqid() . '_' . time(), // CIS único no banco
            'password' => Hash::make($request->senha),
            'role' => 'admin', // Define o papel como 'admin'
        ]);

        // 3. Redireciona para a página de login do admin com uma mensagem de sucesso
        return redirect()->route('admin.login')->with('success', 'Conta administrativa criada com sucesso!');
    }

    public function adminLogin(Request $request)
    {
        // 1. Validação dos dados
        $request->validate(['email' => 'required|email', 'senha' => 'required']);

        // 2. Prepara as credenciais para a tentativa de login
        $credentials = ['email' => $request->email, 'password' => $request->senha];
        $remember = $request->filled('remember');

        // 3. Tenta autenticar o usuário
        if (Auth::attempt($credentials, $remember)) {
            // 4. VERIFICA SE O USUÁRIO LOGADO É UM ADMINISTRADOR
            if (Auth::user()->role === 'admin') {
                $request->session()->regenerate();
                return redirect()->intended(route('admin.dashboard'));
            }

            // Se for um usuário comum tentando logar aqui, faz o logout e retorna um erro
            Auth::logout();
        }

        // 5. Se falhar, volta para o login com uma mensagem de erro
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
