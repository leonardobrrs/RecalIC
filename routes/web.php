<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rotas Web
|--------------------------------------------------------------------------
|
| Aqui é onde você pode registrar as rotas para a sua aplicação.
| Estas rotas são carregadas pelo RouteServiceProvider.
|
*/

// --- ROTAS DE ACESSO PÚBLICO E DE USUÁRIOS ---

// Rota principal: leva para a tela de login do usuário.
Route::get('/', function () {
    return view('LoginUsuario.login');
});

// Rota explícita para o login do usuário.
Route::get('/login', function () {
    return view('LoginUsuario.login');
});

// Rota para a página de cadastro de novos usuários.
Route::get('/cadastro', function () {
    return view('CadastroUsuario.cadastro');
});


// --- ROTAS DO PAINEL DO USUÁRIO (exigirão login futuramente) ---

// Rota para o dashboard principal do usuário.
Route::get('/dashboard', function () {
    return view('DashboardUsuario.dashboard');
});

// Rota para a página de registro de uma nova ocorrência.
Route::get('/ocorrencias/registrar', function () {
    return view('DashboardUsuario.registro');
});

// Rota para visualizar os detalhes de um relato específico.
Route::get('/ocorrencias/relato', function () {
    // Nota: Futuramente, esta rota receberá um ID, ex: /ocorrencias/relato/{id}
    return view('DashboardUsuario.relato');
});

// Rota para visualizar o histórico de um relato.
Route::get('/ocorrencias/historico', function () {
    // Nota: Futuramente, esta rota também receberá um ID.
    return view('DashboardUsuario.detalhesRelato');
});


// --- ROTAS DA ÁREA ADMINISTRATIVA ---

// Rota para a tela de login do administrador.
Route::get('/admin/login', function () {
    return view('LoginAdmin.loginADM');
});

// Rota para a página de cadastro de novos administradores.
Route::get('/admin/cadastro', function () {
    return view('CadastroAdmin.cadastroADM');
});


// --- ROTAS DO PAINEL DO ADMINISTRADOR (exigirão login de admin futuramente) ---

// Rota para o dashboard principal do administrador.
Route::get('/admin/dashboard', function () {
    return view('DashboardAdmin.dashboardADM');
});

// Rota para visualizar os detalhes de uma ocorrência (visão do admin).
Route::get('/admin/ocorrencias/detalhes', function () {
    // Nota: Futuramente, esta rota receberá um ID.
    return view('DashboardAdmin.registros');
});

// Rota para a página de relatórios e estatísticas.
Route::get('/admin/relatorios', function () {
    return view('DashboardAdmin.relatorios');
});
