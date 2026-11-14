<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TranslatorController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request; // Importante para a rota de reset

// Rotas de Login Genérico
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    // Rotas do Tradutor
    Route::get('/tradutor', [TranslatorController::class, 'index'])->name('translator.dashboard');
    Route::post('/tradutor/adicionar', [TranslatorController::class, 'storeTranslation'])->name('translator.store');
    Route::post('/tradutor/saque', [TranslatorController::class, 'requestWithdrawal'])->name('translator.withdraw');
    Route::post('/tradutor/atualizar-idiomas', [TranslatorController::class, 'updateLanguages'])->name('translator.update-languages');
    Route::post('/tradutor/revisao/{translationId}', [TranslatorController::class, 'submitRevision'])->name('translator.submit-revision');
    Route::post('/tradutor/nova-candidatura', [TranslatorController::class, 'applyNewLanguage'])->name('translator.apply-new');
    Route::get('/tornar-tradutor', [TranslatorController::class, 'showApplyForm'])->name('translator.apply-form');
    Route::post('/tornar-tradutor', [TranslatorController::class, 'submitWebApplication'])->name('translator.apply-submit');
});

Route::get('/', function () {
    return view('welcome');
});

// === ROTAS DE LOGIN DO ADMIN (DEVEM FICAR FORA DO MIDDLEWARE AUTH) ===
Route::get('/admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login']);
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

// === ROTAS PROTEGIDAS DO ADMIN ===
Route::middleware(['auth', 'admin'])->group(function () {
    // Páginas Principais
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/admin/payments', [AdminController::class, 'payments'])->name('admin.payments');
    Route::get('/admin/moderation', [AdminController::class, 'moderation'])->name('admin.moderation');
    
    // Ações (Botões)
    Route::post('/admin/users/{id}/toggle-suspend', [AdminController::class, 'toggleSuspend'])->name('admin.toggle-suspend');
    Route::post('/admin/payments/{id}/handle', [AdminController::class, 'handlePayment'])->name('admin.handle-payment');
    Route::post('/admin/translators/{id}/{action}', [AdminController::class, 'approveTranslator'])->name('admin.approve-translator');
    Route::post('/admin/translations/{id}/{action}', [AdminController::class, 'handleTranslation'])->name('admin.handle-translation');

    Route::get('/admin/notifications', [AdminController::class, 'getNotifications'])->name('admin.notifications');
    Route::post('/admin/notifications/{id}/read', [AdminController::class, 'markNotificationRead'])->name('admin.notifications.read');
});

// Rota que o link do email vai abrir
Route::get('/reset-password', function (Request $request) {
    return response()->json([
        'mensagem' => 'Link válido! Copia o token e o email para usares na app.',
        'token' => $request->query('token'),
        'email' => $request->query('email')
    ]);
})->name('password.reset');