<?php

use App\Http\Controllers\Admin\RelatorioController;
use App\Http\Controllers\Admin\UsuarioController;
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

// --- ROTAS DE AUTENTICAÇÃO DE USUÁRIO (PÚBLICAS) ---
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/cadastro', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/cadastro', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// --- ROTAS DO PAINEL DO USUÁRIO (PROTEGIDAS POR AUTH) ---
Route::get('/dashboard', [OcorrenciaController::class, 'index'])->name('user.dashboard');
Route::get('/ocorrencias/registrar', [OcorrenciaController::class, 'create'])->name('ocorrencias.create');
Route::post('/ocorrencias/registrar', [OcorrenciaController::class, 'store'])->name('ocorrencias.store')->middleware('throttle:3,60');
Route::get('/ocorrencias/{id}', [OcorrenciaController::class, 'show'])->name('ocorrencias.show');
Route::get('/ocorrencias/{id}/historico', [OcorrenciaController::class, 'historico'])->name('ocorrencias.historico');
Route::post('/ocorrencias/{id}/avaliar', [OcorrenciaController::class, 'storeAvaliacao'])->name('ocorrencias.avaliar');


// --- ROTAS DE LOGIN/CADASTRO DO ADMIN (PÚBLICAS) ---
// Estas ficam FORA do grupo de middleware 'admin'
Route::get('/admin/login', [AuthController::class, 'showAdminLoginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'adminLogin']);

Route::get('/admin/cadastro', [AuthController::class, 'showAdminRegistrationForm'])->name('admin.register');
Route::post('/admin/cadastro', [AuthController::class, 'adminRegister']);


// --- INÍCIO DA ALTERAÇÃO ---

// --- ROTAS PROTEGIDAS DO PAINEL DO ADMINISTRADOR ---
// Todas as rotas neste grupo exigem que o usuário esteja logado E tenha a role 'admin'
Route::middleware('admin')->group(function () {

    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/ocorrencias/{id}', [AdminController::class, 'showOcorrencia'])->name('admin.ocorrencias.show');
    Route::get('/admin/relatorios', [RelatorioController::class, 'index'])->name('admin.relatorios');
    Route::post('/admin/logout', [AuthController::class, 'adminLogout'])->name('admin.logout');

    // --- ROTAS DE GESTÃO DE UTILIZADORES ---
    Route::get('/admin/usuarios', [UsuarioController::class, 'index'])->name('admin.usuarios.index');
    Route::delete('/admin/usuarios/{user}', [UsuarioController::class, 'destroy'])->name('admin.usuarios.destroy');
    Route::post('/admin/usuarios/{user}/toggle-role', [UsuarioController::class, 'toggleRole'])->name('admin.usuarios.toggleRole');
    Route::post('/admin/usuarios/{user}/block', [UsuarioController::class, 'block'])->name('admin.usuarios.block');
    Route::post('/admin/usuarios/{user}/unblock', [UsuarioController::class, 'unblock'])->name('admin.usuarios.unblock');

    // --- ROTAS DE GESTÃO DE OCORRÊNCIAS/BLOQUEIO ---
    Route::post('/admin/users/{id}/block', [AdminController::class, 'blockUser'])->name('admin.user.block');
    Route::put('/admin/ocorrencias/{id}/status', [AdminController::class, 'updateOcorrenciaStatus'])->name('admin.ocorrencias.updateStatus');
    Route::post('/admin/ocorrencias/{id}/avaliar-relator', [AdminController::class, 'avaliarRelator'])->name('admin.ocorrencias.avaliarRelator');
    Route::delete('/admin/ocorrencias/{id}', [AdminController::class, 'destroyOcorrencia'])->name('admin.ocorrencias.destroy');

});

// ROTA TEMPORÁRIA
Route::get('/debug-email-config', function() {
    try {
        echo "=== DEBUG CONFIGURAÇÃO E-MAIL ===<br>";
        echo "MAIL_MAILER: " . config('mail.default') . "<br>";
        echo "MAIL_HOST: " . config('mail.mailers.smtp.host') . "<br>";
        echo "MAIL_PORT: " . config('mail.mailers.smtp.port') . "<br>";
        echo "MAIL_USERNAME: " . config('mail.mailers.smtp.username') . "<br>";
        echo "MAIL_FROM: " . config('mail.from.address') . "<br>";

        // Teste envio
        $user = App\Models\User::first();
        if ($user) {
            \Mail::raw('Teste de configuração RecalIC', function($message) use ($user) {
                $message->to($user->email)->subject('✅ Teste RecalIC - Configuração');
            });
            echo "<br>✅ E-mail teste ENVIADO para: " . $user->email;
        } else {
            echo "<br>❌ Nenhum usuário encontrado";
        }

    } catch (\Exception $e) {
        echo "<br>❌ ERRO: " . $e->getMessage();
    }
});
