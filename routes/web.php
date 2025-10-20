<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\OcorrenciaController;
use App\Http\Controllers\Admin\AdminController;

/*
|--------------------------------------------------------------------------
| Rotas Web
|--------------------------------------------------------------------------
*/

// Rota principal da aplicação: redireciona para a tela de login.
Route::get('/', function () {
    return redirect()->route('login');
});

// --- ROTAS DE AUTENTICAÇÃO DE USUÁRIO ---
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/cadastro', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/cadastro', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// --- ROTAS DO PAINEL DO USUÁRIO ---
Route::get('/dashboard', [OcorrenciaController::class, 'index'])->name('user.dashboard');
Route::get('/ocorrencias/registrar', [OcorrenciaController::class, 'create'])->name('ocorrencias.create');

// ROTA ADICIONADA: PARA SALVAR A NOVA OCORRÊNCIA                                                                    // até 5 ocorrências a cada 60 minutos
Route::post('/ocorrencias/registrar', [OcorrenciaController::class, 'store'])->name('ocorrencias.store')->middleware('throttle:5,10');

Route::get('/ocorrencias/{id}', [OcorrenciaController::class, 'show'])->name('ocorrencias.show');
Route::get('/ocorrencias/{id}/historico', [OcorrenciaController::class, 'historico'])->name('ocorrencias.historico');


// --- ROTAS DA ÁREA ADMINISTRATIVA ---
Route::get('/admin/login', [AuthController::class, 'showAdminLoginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'adminLogin']);

Route::get('/admin/cadastro', [AuthController::class, 'showAdminRegistrationForm'])->name('admin.register');
Route::post('/admin/cadastro', [AuthController::class, 'adminRegister']);


// --- ROTAS DO PAINEL DO ADMINISTRADOR ---
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::get('/admin/ocorrencias/{id}', [AdminController::class, 'showOcorrencia'])->name('admin.ocorrencias.show');
Route::get('/admin/relatorios', [AdminController::class, 'relatorios'])->name('admin.relatorios');

Route::post('/admin/logout', [AuthController::class, 'adminLogout'])->name('admin.logout');
