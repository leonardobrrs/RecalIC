<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\OcorrenciaController;
use App\Http\Controllers\Admin\AdminController;

// --- ROTAS DE ACESSO PÚBLICO E DE USUÁRIOS ---
Route::get('/', [AuthController::class, 'showLoginForm']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/cadastro', [AuthController::class, 'showRegistrationForm'])->name('register');

// --- ROTAS DO PAINEL DO USUÁRIO ---
Route::get('/dashboard', [OcorrenciaController::class, 'index'])->name('user.dashboard');
Route::get('/ocorrencias/registrar', [OcorrenciaController::class, 'create'])->name('ocorrencias.create');
Route::get('/ocorrencias/{id}', [OcorrenciaController::class, 'show'])->name('ocorrencias.show');
Route::get('/ocorrencias/{id}/historico', [OcorrenciaController::class, 'historico'])->name('ocorrencias.historico');

// --- ROTAS DA ÁREA ADMINISTRATIVA ---
Route::get('/admin/login', [AuthController::class, 'showAdminLoginForm'])->name('admin.login');
Route::get('/admin/cadastro', [AuthController::class, 'showAdminRegistrationForm'])->name('admin.register');

// --- ROTAS DO PAINEL DO ADMINISTRADOR ---
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::get('/admin/ocorrencias/{id}', [AdminController::class, 'showOcorrencia'])->name('admin.ocorrencias.show');
Route::get('/admin/relatorios', [AdminController::class, 'relatorios'])->name('admin.relatorios');
