<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    // Exibe a tela de login do usuário comum
    public function showLoginForm()
    {
        return view('LoginUsuario.login');
    }

    // Exibe a tela de cadastro do usuário comum
    public function showRegistrationForm()
    {
        return view('CadastroUsuario.cadastro');
    }

    // Exibe a tela de login do administrador
    public function showAdminLoginForm()
    {
        return view('LoginAdmin.loginADM');
    }

    // Exibe a tela de cadastro do administrador
    public function showAdminRegistrationForm()
    {
        return view('CadastroAdmin.cadastroADM');
    }

    // Lógica de login (será implementada na Semana 2)
    public function login(Request $request)
    {
        // A ser implementado
    }

    // Lógica de cadastro (será implementada na Semana 2)
    public function register(Request $request)
    {
        // A ser implementado
    }

    // Lógica de logout (será implementada na Semana 2)
    public function logout(Request $request)
    {
        // A ser implementado
    }
}
