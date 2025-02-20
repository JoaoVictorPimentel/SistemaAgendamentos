<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return redirect()->route(auth()->user()->admin ? 'admin.dashboard' : 'usuario.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/agendamento', function () {
    return redirect()->route(auth()->user()->admin ? 'admin.agendamento' : 'usuario.agendamento');
})->middleware(['auth', 'verified'])->name('agendamento');


// Rotas para o Admin
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/agendamento', [AdminController::class, 'index'])->name('admin.agendamento');
    
});


//Rotas usuario
Route::prefix('usuario')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [UsuarioController::class, 'dashboard'])->name('usuario.dashboard');
    Route::get('/agendamento', [UsuarioController::class, 'index'])->name('usuario.agendamento');
    Route::post('/agendamento', [UsuarioController::class, 'store'])->name('usuario.agendamento.store');
    Route::delete('/agendamento/{agendamento}', [UsuarioController::class, 'destroy'])->name('usuario.agendamento.destroy');
    Route::put('/agendamento/{agendamento}', [UsuarioController::class, 'update'])->name('usuario.agendamento.update');
});

Route::get('/api/horarios-disponiveis', [UsuarioController::class, 'getHorasDisponiveisPorData'])->name('api.horarios-disponiveis');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
require __DIR__.'/auth.php';
